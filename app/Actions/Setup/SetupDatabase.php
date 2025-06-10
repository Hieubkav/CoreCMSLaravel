<?php

namespace App\Actions\Setup;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Setup Database Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Setup database cho dự án mới
 * Thay thế logic phức tạp trong SetupController
 */
class SetupDatabase
{
    use AsAction;

    /**
     * Test database connection
     */
    public function handle(bool $testOnly = false): array
    {
        try {
            // Test connection
            DB::connection()->getPdo();
            
            if ($testOnly) {
                return [
                    'success' => true,
                    'message' => 'Kết nối database thành công!'
                ];
            }

            // Bước 1: Chạy migrations cơ bản (core tables)
            $this->runCoreMigrations();

            // Bước 2: Sinh migrations và models cho bảng cấu hình
            $generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('database');

            // Bước 3: Chạy migrations vừa sinh
            if ($generateResult['success']) {
                $this->runGeneratedMigrations();
            }

            return [
                'success' => true,
                'message' => 'Tạo bảng database thành công! Đã sinh và chạy migrations cho bảng cấu hình.',
                'details' => [
                    'core_tables' => 'Đã tạo các bảng cơ bản (users, settings, permissions, etc.)',
                    'generated_files' => $generateResult['success'] ? 'Đã sinh migrations và models cho frontend_configurations, admin_configurations' : 'Lỗi sinh files',
                    'config_tables' => 'Đã chạy migrations cho bảng cấu hình',
                    'next_steps' => 'Các bước tiếp theo sẽ chỉ tạo dữ liệu vào các bảng này'
                ],
                'generate_result' => $generateResult,
                'next_step' => 'admin'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $testOnly 
                    ? 'Không thể kết nối database: ' . $e->getMessage()
                    : 'Không thể tạo bảng: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Chỉ chạy migrations cơ bản cần thiết cho setup
     */
    private function runCoreMigrations(): void
    {
        $coreMigrations = [
            '2014_10_12_000000_create_users_table',
            '2014_10_12_100000_create_password_reset_tokens_table',
            '2019_08_19_000000_create_failed_jobs_table',
            '2019_12_14_000001_create_personal_access_tokens_table',
            '2025_05_09_112506_create_settings_table',
            '2025_05_31_131928_update_settings_table_remove_dmca_add_messenger',
            '2025_06_03_195415_add_placeholder_image_to_settings_table',
            '2025_06_06_000028_create_notifications_table',
            '2025_06_06_122558_create_visitors_table',
            '2025_06_09_011044_create_setup_modules_table',
            '2025_06_09_105528_create_permission_tables',
        ];

        foreach ($coreMigrations as $migration) {
            try {
                Artisan::call('migrate', [
                    '--path' => 'database/migrations/' . $migration . '.php',
                    '--force' => true
                ]);
            } catch (Exception $e) {
                // Log lỗi nhưng tiếp tục với migration khác
                Log::warning("Không thể chạy migration {$migration}: " . $e->getMessage());
            }
        }
    }

    /**
     * Chạy migrations vừa được sinh
     */
    private function runGeneratedMigrations(): void
    {
        // Chạy migrate để tạo bảng từ migrations vừa sinh
        Artisan::call('migrate', [
            '--force' => true,
            '--path' => 'database/migrations'
        ]);
    }

    /**
     * Chỉ test connection
     */
    public static function testConnection(): array
    {
        return static::run(true);
    }

    /**
     * Chạy migration
     */
    public static function runMigrations(): array
    {
        return static::run(false);
    }
}
