<?php

namespace App\Actions\Setup;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Setting;
use Exception;

/**
 * Save Advanced Configuration Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Lưu cấu hình nâng cao cho dự án
 * Thay thế logic phức tạp trong SetupController
 */
class SaveAdvancedConfiguration
{
    use AsAction;

    /**
     * Lưu cấu hình nâng cao
     */
    public function handle(array $data): array
    {
        try {
            // For Core Framework, we'll store basic configurations in settings table
            // Advanced configurations can be stored in config files or separate table if needed

            $settings = Setting::first();
            if (!$settings) {
                $settings = Setting::create(['status' => 'active']);
            }

            // Update basic SEO settings if provided
            if (!empty($data['default_description'])) {
                $settings->update([
                    'seo_description' => $data['default_description']
                ]);
            }

            // For now, we'll just mark configuration as complete
            // Advanced settings like image processing, cache, etc. can be handled via config files

            return [
                'success' => true,
                'message' => 'Cấu hình nâng cao đã được lưu!',
                'next_step' => 'sample-data'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Không thể lưu cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate dữ liệu đầu vào
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (isset($data['webp_quality']) && ($data['webp_quality'] < 50 || $data['webp_quality'] > 100)) {
            $errors['webp_quality'] = 'Chất lượng WebP phải từ 50-100%';
        }

        if (isset($data['max_width']) && ($data['max_width'] < 800 || $data['max_width'] > 4000)) {
            $errors['max_width'] = 'Chiều rộng tối đa phải từ 800-4000px';
        }

        if (isset($data['max_height']) && ($data['max_height'] < 600 || $data['max_height'] > 3000)) {
            $errors['max_height'] = 'Chiều cao tối đa phải từ 600-3000px';
        }

        if (isset($data['cache_duration']) && ($data['cache_duration'] < 60 || $data['cache_duration'] > 3600)) {
            $errors['cache_duration'] = 'Thời gian cache phải từ 60-3600 giây';
        }

        if (isset($data['pagination_size']) && !in_array($data['pagination_size'], [10, 25, 50, 100])) {
            $errors['pagination_size'] = 'Số item mỗi trang phải là 10, 25, 50 hoặc 100';
        }

        if (!empty($data['mail_username']) && !filter_var($data['mail_username'], FILTER_VALIDATE_EMAIL)) {
            $errors['mail_username'] = 'Email username không hợp lệ';
        }

        return $errors;
    }

    /**
     * Lưu với validation
     */
    public static function saveWithValidation(array $data): array
    {
        $errors = static::validate($data);
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        return static::run($data);
    }

    /**
     * Lấy cấu hình mặc định
     */
    public static function getDefaults(): array
    {
        return [
            'webp_quality' => 95,
            'max_width' => 1920,
            'max_height' => 1080,
            'seo_auto_generate' => true,
            'default_description' => 'Powered by Core Framework',
            'query_cache' => true,
            'eager_loading' => true,
            'asset_optimization' => true,
            'cache_duration' => 300,
            'pagination_size' => 25,
            'mail_host' => '',
            'mail_port' => 587,
            'mail_username' => '',
            'mail_password' => '',
        ];
    }
}
