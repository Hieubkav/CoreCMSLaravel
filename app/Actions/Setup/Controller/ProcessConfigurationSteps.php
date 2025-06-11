<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\SaveAdvancedConfiguration;
use App\Actions\Setup\SaveFrontendConfiguration;
use App\Actions\Setup\SaveAdminConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProcessConfigurationSteps
{
    /**
     * Xử lý bước cấu hình nâng cao (legacy)
     */
    public static function processConfigurationStep(Request $request): array
    {
        $data = $request->all();
        return SaveAdvancedConfiguration::saveWithValidation($data);
    }

    /**
     * Xử lý bước cấu hình frontend
     */
    public static function processFrontendConfigStep(Request $request): array
    {
        $data = $request->all();

        try {
            // Bảng frontend_configurations đã được tạo sẵn trong bước database
            // Không cần tạo bảng nữa, chỉ cần lưu dữ liệu

            // Bước 1: Tự động sử dụng default favicon (không cần upload trong setup)
            $defaultFaviconResult = UseDefaultAssets::useDefaultFavicon();
            if ($defaultFaviconResult['success']) {
                $data['favicon_path'] = $defaultFaviconResult['path'];
            }

            // Bước 2: Xử lý error_pages nếu là JSON string
            if (isset($data['error_pages']) && is_string($data['error_pages'])) {
                $data['error_pages'] = json_decode($data['error_pages'], true);
            }

            // Bước 3: Lưu cấu hình vào bảng frontend_configurations
            $result = SaveFrontendConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['frontend_config' => $data]);

                // Sinh code cho Filament page để quản lý frontend configuration
                $generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('frontend-config');

                $message = 'Đã lưu cấu hình frontend vào bảng frontend_configurations thành công!';
                if (isset($faviconResult) && $faviconResult['success']) {
                    $message .= ' Favicon đã được upload và copy vào public/favicon.ico.';
                }

                if ($generateResult['success']) {
                    $message .= ' Đã tạo Filament Frontend Configuration page để quản lý.';
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'config_id' => $result['config']->id ?? null,
                    'table_created' => true,
                    'favicon_uploaded' => isset($faviconResult) && $faviconResult['success'],
                    'generate_result' => $generateResult
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình frontend'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý bước cấu hình admin
     */
    public static function processAdminConfigStep(Request $request): array
    {
        $data = $request->all();

        try {
            // Bảng admin_configurations đã được tạo sẵn trong bước database
            // Chỉ cần lưu dữ liệu vào bảng

            // Lưu cấu hình vào database
            $result = SaveAdminConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['admin_config' => $data]);

                return [
                    'success' => true,
                    'message' => 'Đã lưu cấu hình admin dashboard vào bảng admin_configurations thành công!',
                    'config_id' => $result['config']->id ?? null,
                    'note' => 'Bảng admin_configurations đã được tạo sẵn trong bước database'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình admin'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Đảm bảo bảng system_configurations tồn tại
     */
    private static function ensureSystemConfigurationsTableExists(): void
    {
        if (!Schema::hasTable('system_configurations')) {
            Schema::create('system_configurations', function ($table) {
                $table->id();
                $table->string('theme_name')->default('default');
                $table->json('color_scheme')->nullable();
                $table->json('typography')->nullable();
                $table->string('favicon_path')->nullable();
                $table->json('error_pages')->nullable();
                $table->json('analytics')->nullable();
                $table->json('performance')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Đảm bảo bảng admin_configurations tồn tại
     */
    private static function ensureAdminConfigurationsTableExists(): void
    {
        if (!Schema::hasTable('admin_configurations')) {
            Schema::create('admin_configurations', function ($table) {
                $table->id();
                $table->string('panel_name')->default('Admin Panel');
                $table->json('sidebar_config')->nullable();
                $table->json('dashboard_widgets')->nullable();
                $table->json('user_preferences')->nullable();
                $table->json('notification_settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }
}
