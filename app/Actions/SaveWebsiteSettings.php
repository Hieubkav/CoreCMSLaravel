<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Setting;
use Exception;

/**
 * Save Website Settings Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Lưu cài đặt website cơ bản
 * Thay thế logic phức tạp trong SetupController
 */
class SaveWebsiteSettings
{
    use AsAction;

    /**
     * Lưu cài đặt website
     */
    public function handle(array $data): array
    {
        try {
            // Map data to settings table columns
            $settingsData = [
                'site_name' => $data['site_name'] ?? '',
                'seo_description' => $data['site_description'] ?? '',
                'email' => $data['contact_email'] ?? '',
                'hotline' => $data['contact_phone'] ?? '',
                'address' => $data['contact_address'] ?? '',
                'status' => 'active',
            ];

            // Get first settings record or create new one
            $settings = Setting::first();

            if ($settings) {
                $settings->update($settingsData);
            } else {
                Setting::create($settingsData);
            }

            return [
                'success' => true,
                'message' => 'Cấu hình website thành công!',
                'next_step' => 'configuration'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Không thể cấu hình website: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate dữ liệu đầu vào
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['site_name'])) {
            $errors['site_name'] = 'Vui lòng nhập tên website';
        }

        if (!empty($data['contact_email']) && !filter_var($data['contact_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['contact_email'] = 'Email không hợp lệ';
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
     * Lấy cài đặt mặc định
     */
    public static function getDefaults(): array
    {
        return [
            'site_name' => 'Website của tôi',
            'site_description' => 'Được xây dựng với Core Framework',
            'site_keywords' => 'website, laravel, core framework',
            'contact_email' => '',
            'contact_phone' => '',
            'contact_address' => '',
        ];
    }
}
