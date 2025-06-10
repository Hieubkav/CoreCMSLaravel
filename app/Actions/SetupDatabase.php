<?php

namespace App\Actions;

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

            // Chỉ chạy migrations cơ bản (core tables)
            $this->runCoreMigrations();
            
            return [
                'success' => true,
                'message' => 'Tạo bảng database thành công!',
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
