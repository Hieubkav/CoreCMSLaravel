<?php

namespace App\Actions\Setup;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Artisan;
use Exception;

/**
 * Import Sample Data Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Import dữ liệu mẫu cho dự án mới
 * Thay thế logic phức tạp trong SetupController
 */
class ImportSampleData
{
    use AsAction;

    /**
     * Import dữ liệu mẫu
     */
    public function handle(bool $importSample = true): array
    {
        try {
            if ($importSample) {
                // Run seeders
                Artisan::call('db:seed', ['--force' => true]);
                
                return [
                    'success' => true,
                    'message' => 'Import dữ liệu mẫu thành công!',
                    'data_imported' => true
                ];
            }

            return [
                'success' => true,
                'message' => 'Bỏ qua import dữ liệu mẫu',
                'data_imported' => false
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Không thể import dữ liệu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Chỉ import dữ liệu mẫu
     */
    public static function import(): array
    {
        return static::run(true);
    }

    /**
     * Bỏ qua import
     */
    public static function skip(): array
    {
        return static::run(false);
    }

    /**
     * Kiểm tra xem có dữ liệu mẫu không
     */
    public static function hasSampleData(): bool
    {
        try {
            // Kiểm tra các model cơ bản của Core Framework
            $postCount = \App\Models\Post::count();
            $userCount = \App\Models\User::count();
            $categoryCount = \App\Models\CatPost::count();

            return $postCount > 0 || $userCount > 1 || $categoryCount > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Lấy thống kê dữ liệu hiện tại
     */
    public static function getDataStats(): array
    {
        try {
            return [
                'posts' => \App\Models\Post::count(),
                'users' => \App\Models\User::count(),
                'categories' => \App\Models\CatPost::count(),
                'settings' => \App\Models\Setting::count(),
            ];
        } catch (Exception $e) {
            return [
                'posts' => 0,
                'users' => 0,
                'categories' => 0,
                'settings' => 0,
            ];
        }
    }
}
