<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\SetupModule;
use App\Actions\GenerateModuleCode;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class InstallSelectedModules
{
    use AsAction;

    /**
     * Cài đặt các modules đã được chọn
     */
    public function handle(): array
    {
        try {
            $selectedModules = ProcessModuleSelection::getSelectedModules();
            $installSampleData = ProcessModuleSelection::shouldInstallSampleData();

            if (empty($selectedModules)) {
                return [
                    'success' => false,
                    'message' => 'Không có modules nào được chọn để cài đặt'
                ];
            }

            $results = [];
            $totalModules = count($selectedModules);
            $installedCount = 0;

            foreach ($selectedModules as $module) {
                try {
                    $result = $this->installModule($module['name'], $installSampleData);
                    $results[$module['name']] = $result;

                    if ($result['success']) {
                        $installedCount++;

                        // Cập nhật trạng thái cài đặt
                        SetupModule::where('module_name', $module['name'])->update([
                            'is_installed' => true,
                            'installed_at' => now(),
                            'installation_notes' => $result['message'] ?? null
                        ]);
                    }

                } catch (\Exception $e) {
                    $results[$module['name']] = [
                        'success' => false,
                        'error' => $e->getMessage()
                    ];

                    Log::error("Failed to install module: {$module['name']}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Generate code cho modules đã được enable
            $codeGenerationResult = GenerateModuleCode::run();

            return [
                'success' => $installedCount > 0,
                'message' => "Đã cài đặt thành công {$installedCount}/{$totalModules} modules",
                'total_modules' => $totalModules,
                'installed_count' => $installedCount,
                'failed_count' => $totalModules - $installedCount,
                'results' => $results,
                'sample_data_installed' => $installSampleData,
                'code_generation' => $codeGenerationResult
            ];

        } catch (\Exception $e) {
            Log::error('Module installation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Lỗi khi cài đặt modules: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt một module cụ thể
     */
    private function installModule(string $moduleName, bool $installSampleData): array
    {
        switch ($moduleName) {
            case 'system_configuration':
                return $this->installSystemConfiguration($installSampleData);

            case 'user_roles':
                return $this->installUserRoles($installSampleData);

            case 'blog_posts':
                return $this->installBlogPosts($installSampleData);

            case 'staff':
                return $this->installStaff($installSampleData);

            case 'content_sections':
                return $this->installContentSections($installSampleData);

            case 'layout_components':
                return $this->installLayoutComponents($installSampleData);

            case 'ecommerce':
                return $this->installEcommerce($installSampleData);

            case 'settings_expansion':
                return $this->installSettingsExpansion($installSampleData);

            case 'web_design_management':
                return $this->installWebDesignManagement($installSampleData);

            default:
                return [
                    'success' => false,
                    'message' => "Module không được hỗ trợ: {$moduleName}"
                ];
        }
    }

    /**
     * Cài đặt System Configuration module
     */
    private function installSystemConfiguration(bool $installSampleData): array
    {
        try {
            // Tạo Filament resource nếu chưa có
            if (!class_exists('App\Filament\Admin\Resources\SystemConfigurationResource')) {
                $this->createSystemConfigurationResource();
            }

            // Tạo dữ liệu mẫu nếu được yêu cầu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'SystemConfigurationSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt System Configuration module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt System Configuration: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt User Roles module
     */
    private function installUserRoles(bool $installSampleData): array
    {
        try {
            // Cài đặt Spatie Permission package
            Artisan::call('vendor:publish', [
                '--provider' => 'Spatie\Permission\PermissionServiceProvider'
            ]);

            // Chạy migration permissions
            Artisan::call('migrate', ['--force' => true]);

            // Tạo roles và permissions mặc định
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt User Roles module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt User Roles: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Blog Posts module
     */
    private function installBlogPosts(bool $installSampleData): array
    {
        try {
            // Tạo Filament resources nếu chưa có
            $this->createBlogFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'BlogModuleSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Blog Posts module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Blog Posts: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Staff module
     */
    private function installStaff(bool $installSampleData): array
    {
        try {
            // Tạo Filament resource
            $this->createStaffFilamentResource();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'StaffSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Staff module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Staff: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Content Sections module
     */
    private function installContentSections(bool $installSampleData): array
    {
        try {
            // Tạo tất cả Filament resources cho content sections
            $this->createContentSectionsFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'ContentSectionsSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Content Sections module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Content Sections: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Layout Components module
     */
    private function installLayoutComponents(bool $installSampleData): array
    {
        try {
            // Tạo Filament resource cho Menu Items
            $this->createMenuItemFilamentResource();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'MenuItemSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Layout Components module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Layout Components: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt E-commerce module
     */
    private function installEcommerce(bool $installSampleData): array
    {
        try {
            // Tạo tất cả Filament resources cho e-commerce
            $this->createEcommerceFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'EcommerceSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt E-commerce module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt E-commerce: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Settings Expansion module
     */
    private function installSettingsExpansion(bool $installSampleData): array
    {
        try {
            // Cập nhật Settings Filament resource
            $this->updateSettingsFilamentResource();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'SettingsExpansionSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Settings Expansion module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Settings Expansion: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Web Design Management module
     */
    private function installWebDesignManagement(bool $installSampleData): array
    {
        try {
            // Tạo Filament resources cho web design management
            $this->createWebDesignFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'WebDesignManagementSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Web Design Management module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Web Design Management: ' . $e->getMessage()
            ];
        }
    }

    // Helper methods để tạo Filament resources
    private function createSystemConfigurationResource()
    {
        // SystemConfigurationResource đã tồn tại
        return true;
    }

    private function createBlogFilamentResources()
    {
        // PostResource và PostCategoryResource đã tồn tại
        return true;
    }

    private function createStaffFilamentResource()
    {
        // StaffResource đã tồn tại
        return true;
    }

    private function createContentSectionsFilamentResources()
    {
        // Tất cả content section resources đã tồn tại
        return true;
    }

    private function createMenuItemFilamentResource()
    {
        // MenuItemResource đã tồn tại
        return true;
    }

    private function createEcommerceFilamentResources()
    {
        // Tất cả ecommerce resources đã tồn tại
        return true;
    }

    private function updateSettingsFilamentResource()
    {
        // Settings đã được mở rộng
        return true;
    }

    private function createWebDesignFilamentResources()
    {
        // ManageWebDesign page đã được tạo
        return true;
    }
}
