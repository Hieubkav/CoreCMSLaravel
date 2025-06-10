<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Module\CheckModuleVisibility;
use App\Actions\Setup\GenerateModuleCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Exception;

class ProcessModuleSteps
{
    /**
     * Xử lý các bước module với generation code ngay lập tức
     */
    public static function processModuleStepWithGeneration(Request $request, string $moduleKey): array
    {
        $data = $request->all();
        $data['module_key'] = $moduleKey;

        // Lưu ngay vào database thay vì session
        $result = self::saveModuleConfiguration($moduleKey, $data);

        if ($result['success']) {
            // Vẫn lưu vào session để backup
            $moduleConfigs = session('module_configs', []);
            $moduleConfigs[$moduleKey] = $data;
            session(['module_configs' => $moduleConfigs]);

            // Clear module visibility cache khi có thay đổi
            CheckModuleVisibility::clearModuleCache();

            $generationResults = [];

            // Nếu module được enable, generate code ngay lập tức
            if ($data['enable_module'] ?? false) {
                try {
                    // Generate code cho module
                    $generateResult = GenerateModuleCode::handle($moduleKey, $data);

                    if ($generateResult['success']) {
                        $generationResults['generation'] = $generateResult['results'];

                        // Chạy migrations nếu có
                        if (!empty($generateResult['results']['migrations'])) {
                            Artisan::call('migrate', ['--force' => true]);
                            $generationResults['migration'] = 'Migrations executed successfully';
                        }

                        // Clear cache sau khi generate
                        Artisan::call('cache:clear');
                        Artisan::call('config:clear');

                    } else {
                        $generationResults['generation_error'] = $generateResult['error'];
                    }

                } catch (Exception $e) {
                    $generationResults['generation_error'] = $e->getMessage();
                }
            }

            $message = $data['enable_module'] ?? false
                ? "Đã lưu cấu hình module {$moduleKey} vào database và generate code thành công!"
                : "Đã lưu trạng thái bỏ qua module {$moduleKey} vào database!";

            return [
                'success' => true,
                'message' => $message,
                'module_key' => $moduleKey,
                'enabled' => $data['enable_module'] ?? false,
                'module_id' => $result['module_id'] ?? null,
                'generation_results' => $generationResults
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['message'] ?? "Có lỗi xảy ra khi lưu cấu hình module {$moduleKey}"
            ];
        }
    }

    /**
     * Xử lý các bước module (legacy method)
     */
    public static function processModuleStep(Request $request, string $moduleKey): array
    {
        $data = $request->all();
        $data['module_key'] = $moduleKey;

        // Lưu ngay vào database thay vì session
        $result = self::saveModuleConfiguration($moduleKey, $data);

        if ($result['success']) {
            // Vẫn lưu vào session để backup
            $moduleConfigs = session('module_configs', []);
            $moduleConfigs[$moduleKey] = $data;
            session(['module_configs' => $moduleConfigs]);

            // Clear module visibility cache khi có thay đổi
            CheckModuleVisibility::clearModuleCache();

            $message = $data['enable_module'] ?? false
                ? "Đã lưu cấu hình module {$moduleKey} vào database thành công!"
                : "Đã lưu trạng thái bỏ qua module {$moduleKey} vào database!";

            return [
                'success' => true,
                'message' => $message,
                'module_key' => $moduleKey,
                'enabled' => $data['enable_module'] ?? false,
                'module_id' => $result['module_id'] ?? null
            ];
        } else {
            return [
                'success' => false,
                'message' => $result['message'] ?? "Có lỗi xảy ra khi lưu cấu hình module {$moduleKey}"
            ];
        }
    }

    /**
     * Lưu cấu hình module vào database
     */
    private static function saveModuleConfiguration(string $moduleKey, array $data): array
    {
        try {
            // Tạo bảng setup_modules nếu chưa có
            if (!\Illuminate\Support\Facades\Schema::hasTable('setup_modules')) {
                \Illuminate\Support\Facades\Schema::create('setup_modules', function ($table) {
                    $table->id();
                    $table->string('module_key')->unique();
                    $table->string('module_name');
                    $table->boolean('is_enabled')->default(false);
                    $table->json('configuration')->nullable();
                    $table->timestamps();
                });
            }

            // Lưu hoặc cập nhật module configuration
            $module = \App\Models\SetupModule::updateOrCreate(
                ['module_key' => $moduleKey],
                [
                    'module_name' => $data['module_name'] ?? ucfirst(str_replace('_', ' ', $moduleKey)),
                    'is_enabled' => $data['enable_module'] ?? false,
                    'configuration' => $data
                ]
            );

            return [
                'success' => true,
                'module_id' => $module->id,
                'message' => "Module {$moduleKey} đã được lưu thành công"
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Lỗi khi lưu module {$moduleKey}: " . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý bước tổng quan modules
     */
    public static function processModulesSummaryStep(Request $request): array
    {
        try {
            // Lấy danh sách modules đã được cấu hình
            $modules = \App\Models\SetupModule::all();
            
            return [
                'success' => true,
                'message' => 'Đã tải danh sách modules thành công',
                'modules' => $modules->toArray()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tải danh sách modules: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý bước cài đặt cuối cùng
     */
    public static function processInstallationStep(Request $request): array
    {
        try {
            // Chạy các migrations cuối cùng
            Artisan::call('migrate', ['--force' => true]);
            
            // Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            
            return [
                'success' => true,
                'message' => 'Cài đặt hoàn tất thành công!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi hoàn tất cài đặt: ' . $e->getMessage()
            ];
        }
    }
}
