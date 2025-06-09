<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Generated\Models\SystemConfiguration;

class SystemConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎨 Bắt đầu tạo cấu hình hệ thống...');

        // Xóa dữ liệu cũ
        SystemConfiguration::truncate();

        // Cấu hình mặc định - Minimalist Red Theme
        $config1 = SystemConfiguration::create([
            'name' => 'Cấu hình mặc định',
            'description' => 'Cấu hình theme minimalist với màu đỏ chủ đạo, phù hợp cho đa số website',
            'theme_mode' => 'light',
            'primary_color' => '#dc2626',
            'secondary_color' => '#6b7280',
            'accent_color' => '#3b82f6',
            'font_family' => 'Inter',
            'font_size' => 'base',
            'design_style' => 'minimalist',
            'icon_system' => 'fontawesome',
            'error_pages' => [
                '404' => [
                    'title' => 'Trang không tìm thấy',
                    'message' => 'Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển.',
                    'show_search' => true,
                    'show_home_link' => true,
                ],
                '500' => [
                    'title' => 'Lỗi máy chủ',
                    'message' => 'Đã xảy ra lỗi không mong muốn. Chúng tôi đang khắc phục sự cố.',
                    'show_contact' => true,
                ],
                '503' => [
                    'title' => 'Bảo trì hệ thống',
                    'message' => 'Website đang được bảo trì để cải thiện trải nghiệm. Vui lòng quay lại sau.',
                    'show_countdown' => false,
                ],
            ],
            'analytics_config' => [
                'google_analytics' => null,
                'google_tag_manager' => null,
                'facebook_pixel' => null,
                'hotjar' => null,
                'custom_scripts' => null,
            ],
            'is_active' => true,
            'order' => 1,
        ]);

        // Cấu hình Corporate - Professional Blue
        $config2 = SystemConfiguration::create([
            'name' => 'Corporate Professional',
            'description' => 'Cấu hình dành cho doanh nghiệp với màu xanh chuyên nghiệp',
            'theme_mode' => 'light',
            'primary_color' => '#1e40af',
            'secondary_color' => '#64748b',
            'accent_color' => '#059669',
            'font_family' => 'Source Sans Pro',
            'font_size' => 'base',
            'design_style' => 'corporate',
            'icon_system' => 'heroicons',
            'is_active' => false,
            'order' => 2,
        ]);

        // Cấu hình E-commerce - Modern Shopping
        $config3 = SystemConfiguration::create([
            'name' => 'E-commerce Modern',
            'description' => 'Cấu hình tối ưu cho website bán hàng với UX/UI hiện đại',
            'theme_mode' => 'light',
            'primary_color' => '#059669',
            'secondary_color' => '#6b7280',
            'accent_color' => '#dc2626',
            'font_family' => 'Roboto',
            'font_size' => 'base',
            'design_style' => 'modern',
            'icon_system' => 'heroicons',
            'is_active' => false,
            'order' => 3,
        ]);

        $this->command->info('✅ Đã tạo 3 cấu hình hệ thống mẫu');
        $this->command->info('🎉 Hoàn thành tạo cấu hình hệ thống!');
    }
}
