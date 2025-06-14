<?php

namespace App\Actions;

use App\Models\SetupModule;
use Illuminate\Support\Facades\Log;

class ProcessModuleSelection
{
    /**
     * Xử lý việc chọn modules để cài đặt
     */
    public static function run(array $data): array
    {
        try {
            $selectedModules = $data['selected_modules'] ?? [];
            $installSampleData = $data['install_sample_data'] ?? false;

            // Validate selected modules
            $availableModules = SetupModule::getAvailableModules();
            $validModules = [];

            foreach ($selectedModules as $moduleName) {
                if (isset($availableModules[$moduleName])) {
                    $validModules[] = $moduleName;
                }
            }

            // Thêm các modules bắt buộc nếu chưa có
            $requiredModules = ['system_configuration', 'layout_components', 'settings_expansion', 'web_design_management'];
            foreach ($requiredModules as $requiredModule) {
                if (!in_array($requiredModule, $validModules)) {
                    $validModules[] = $requiredModule;
                }
            }

            // Lưu thông tin modules được chọn vào database
            foreach ($availableModules as $moduleName => $moduleInfo) {
                $isSelected = in_array($moduleName, $validModules);
                
                SetupModule::updateOrCreate(
                    ['module_name' => $moduleName],
                    [
                        'module_title' => $moduleInfo['title'],
                        'module_description' => $moduleInfo['description'],
                        'is_required' => $moduleInfo['required'],
                        'is_installed' => false, // Sẽ được cập nhật khi cài đặt thực tế
                        'configuration' => [
                            'selected' => $isSelected,
                            'install_sample_data' => $installSampleData
                        ]
                    ]
                );
            }

            // Tạo danh sách modules sẽ được cài đặt
            $modulesToInstall = [];
            foreach ($validModules as $moduleName) {
                $modulesToInstall[] = [
                    'name' => $moduleName,
                    'title' => $availableModules[$moduleName]['title'],
                    'description' => $availableModules[$moduleName]['description']
                ];
            }

            Log::info('Module selection processed', [
                'selected_modules' => $validModules,
                'install_sample_data' => $installSampleData
            ]);

            // Lưu lựa chọn modules vào session để sử dụng trong các bước tiếp theo
            session(['selected_modules' => $validModules]);
            session(['install_sample_data' => $installSampleData]);

            return [
                'success' => true,
                'message' => 'Đã lưu lựa chọn modules thành công!',
                'modules_to_install' => $modulesToInstall,
                'selected_modules' => $validModules,
                'install_sample_data' => $installSampleData,
                'next_step' => 'frontend-config'
            ];

        } catch (\Exception $e) {
            Log::error('Error processing module selection', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý lựa chọn modules: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách modules đã được chọn
     */
    public static function getSelectedModules(): array
    {
        return SetupModule::where(function($query) {
                $query->whereJsonContains('configuration->selected', true)
                      ->orWhereJsonContains('configuration->enabled', true);
            })
            ->get()
            ->map(function ($module) {
                return [
                    'name' => $module->module_name,
                    'title' => $module->module_title,
                    'description' => $module->module_description,
                    'is_required' => $module->is_required,
                    'is_installed' => $module->is_installed
                ];
            })
            ->toArray();
    }

    /**
     * Kiểm tra có cài đặt dữ liệu mẫu không
     */
    public static function shouldInstallSampleData(): bool
    {
        $module = SetupModule::where(function($query) {
                $query->whereJsonContains('configuration->selected', true)
                      ->orWhereJsonContains('configuration->enabled', true);
            })->first();

        if (!$module || !$module->configuration) {
            return false;
        }

        return $module->configuration['install_sample_data'] ??
               $module->configuration['create_sample_data'] ?? false;
    }
}
