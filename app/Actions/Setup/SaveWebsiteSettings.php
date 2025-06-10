<?php

namespace App\Actions\Setup;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Log;

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
                // Basic information
                'site_name' => $data['site_name'] ?? '',
                'slogan' => $data['slogan'] ?? '',
                'footer_description' => $data['footer_description'] ?? '',

                // SEO information
                'seo_title' => $data['seo_title'] ?? $data['site_name'] ?? '',
                'seo_description' => $data['seo_description'] ?? '',

                // Contact information
                'email' => $data['email'] ?? '',
                'hotline' => $data['hotline'] ?? '',
                'address' => $data['address'] ?? '',
                'working_hours' => $data['working_hours'] ?? '',

                // Social media links
                'facebook_link' => $data['facebook_link'] ?? '',
                'zalo_link' => $data['zalo_link'] ?? '',
                'youtube_link' => $data['youtube_link'] ?? '',
                'tiktok_link' => $data['tiktok_link'] ?? '',
                'messenger_link' => $data['messenger_link'] ?? '',

                // System fields
                'status' => 'active',
                'order' => 1,
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
                'data' => $settingsData,
                'next_step' => 'module-selection'
            ];

        } catch (Exception $e) {
            // Log detailed error for debugging
            Log::error('SaveWebsiteSettings Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'error' => 'Không thể cấu hình website: ' . $e->getMessage(),
                'debug_info' => app()->environment('local') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ] : null
            ];
        }
    }

    /**
     * Validate dữ liệu đầu vào
     */
    public static function validate(array $data): array
    {
        $errors = [];

        // Required fields
        if (empty($data['site_name'])) {
            $errors['site_name'] = 'Vui lòng nhập tên website';
        }

        // Email validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        // URL validation for social media links
        $urlFields = ['facebook_link', 'zalo_link', 'youtube_link', 'tiktok_link', 'messenger_link'];
        foreach ($urlFields as $field) {
            if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                $errors[$field] = 'URL không hợp lệ';
            }
        }

        // Length validation for SEO fields
        if (!empty($data['seo_title']) && strlen($data['seo_title']) > 60) {
            $errors['seo_title'] = 'Tiêu đề SEO không nên quá 60 ký tự';
        }

        if (!empty($data['seo_description']) && strlen($data['seo_description']) > 160) {
            $errors['seo_description'] = 'Mô tả SEO không nên quá 160 ký tự';
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
            // Basic information
            'site_name' => 'Website của tôi',
            'slogan' => 'Được xây dựng với Core Framework',
            'footer_description' => 'Website chuyên nghiệp được xây dựng với Core Laravel Framework',

            // SEO information
            'seo_title' => '',
            'seo_description' => 'Website chuyên nghiệp với đầy đủ tính năng quản lý nội dung',

            // Contact information
            'email' => '',
            'hotline' => '',
            'address' => '',
            'working_hours' => 'Thứ 2 - Thứ 6: 8:00 - 17:00',

            // Social media links
            'facebook_link' => '',
            'zalo_link' => '',
            'youtube_link' => '',
            'tiktok_link' => '',
            'messenger_link' => '',
        ];
    }
}
