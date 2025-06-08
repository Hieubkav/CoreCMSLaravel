<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
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
