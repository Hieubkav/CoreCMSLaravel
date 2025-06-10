<?php

namespace App\Actions\Setup\Controller;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Exception;

class CompleteSetup
{
    /**
     * Hoàn thành quá trình setup
     */
    public static function handle(): array
    {
        try {
            // Đánh dấu đã hoàn thành setup bằng cách tạo/cập nhật settings
            $settings = Setting::first();
            if ($settings) {
                // Có thể thêm một field setup_completed vào settings table
                // Hoặc đơn giản là đảm bảo có ít nhất 1 record settings
                $settings->update(['status' => 'active']);
            } else {
                Setting::create([
                    'site_name' => 'Core Framework',
                    'status' => 'active'
                ]);
            }

            // Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return [
                'success' => true,
                'message' => 'Cài đặt hoàn tất!',
                'redirect' => route('storeFront')
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Không thể hoàn thành cài đặt: ' . $e->getMessage()
            ];
        }
    }
}
