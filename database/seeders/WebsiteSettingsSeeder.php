<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Generated\Models\WebsiteSettings;

class WebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Bắt đầu tạo website settings...');

        // Xóa dữ liệu cũ
        WebsiteSettings::truncate();

        // Tạo cài đặt mặc định
        $this->createDefaultSettings();
        
        // Tạo cài đặt cho E-commerce
        $this->createEcommerceSettings();
        
        // Tạo cài đặt cho Blog
        $this->createBlogSettings();

        $this->command->info('✅ Đã tạo website settings thành công!');
    }

    private function createDefaultSettings()
    {
        $this->command->info('🏠 Tạo cài đặt mặc định...');

        WebsiteSettings::create([
            'site_name' => 'Core Framework',
            'site_tagline' => 'Framework mạnh mẽ cho mọi dự án',
            'site_description' => 'Core Framework là một framework Laravel mạnh mẽ và linh hoạt, được thiết kế để phát triển nhanh chóng các ứng dụng web hiện đại.',
            'site_keywords' => 'laravel, framework, php, web development, core framework, cms',
            
            // Contact information
            'contact_email' => 'info@coreframework.com',
            'contact_phone' => '+84 123 456 789',
            'contact_address' => '123 Đường ABC, Quận 1, TP.HCM, Việt Nam',
            'contact_working_hours' => "Thứ 2 - Thứ 6: 8:00 - 17:00\nThứ 7: 8:00 - 12:00\nChủ nhật: Nghỉ",
            
            // Social media
            'social_facebook' => 'https://facebook.com/coreframework',
            'social_twitter' => 'https://twitter.com/coreframework',
            'social_instagram' => 'https://instagram.com/coreframework',
            'social_youtube' => 'https://youtube.com/coreframework',
            'social_linkedin' => 'https://linkedin.com/company/coreframework',
            
            // SEO
            'seo_title_template' => '{title} - {site_name}',
            'seo_description_template' => '{description} | {site_name}',
            
            // Analytics
            'analytics_google_id' => 'G-XXXXXXXXXX',
            'analytics_facebook_pixel' => '123456789012345',
            'analytics_gtm_id' => 'GTM-XXXXXXX',
            
            // Email settings
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'smtp_from_address' => 'noreply@coreframework.com',
            'smtp_from_name' => 'Core Framework',
            
            // Localization
            'currency_code' => 'VND',
            'currency_symbol' => '₫',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'language' => 'vi',
            
            // System settings
            'items_per_page' => 12,
            'max_upload_size' => 10240, // 10MB
            'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'doc', 'docx', 'zip'],
            
            // Backup
            'backup_frequency' => 'weekly',
            'backup_retention_days' => 30,
            
            // Performance
            'cache_enabled' => true,
            'cache_duration' => 3600,
            'compression_enabled' => true,
            'minify_css' => false,
            'minify_js' => false,
            'lazy_loading' => true,
            
            // Maintenance
            'maintenance_mode' => false,
            'maintenance_message' => 'Website đang được bảo trì để cải thiện trải nghiệm. Vui lòng quay lại sau.',
            'maintenance_allowed_ips' => ['127.0.0.1', '::1'],
            
            // Status
            'is_active' => true,
            'order' => 1,
        ]);
    }

    private function createEcommerceSettings()
    {
        $this->command->info('🛒 Tạo cài đặt E-commerce...');

        WebsiteSettings::create([
            'site_name' => 'Core Shop',
            'site_tagline' => 'Mua sắm trực tuyến dễ dàng',
            'site_description' => 'Core Shop - Nền tảng thương mại điện tử hiện đại với đầy đủ tính năng cho việc mua bán trực tuyến.',
            'site_keywords' => 'ecommerce, online shop, mua sắm, thương mại điện tử, core shop',
            
            // Contact information
            'contact_email' => 'support@coreshop.com',
            'contact_phone' => '+84 987 654 321',
            'contact_address' => '456 Đường XYZ, Quận 3, TP.HCM, Việt Nam',
            'contact_working_hours' => "Thứ 2 - Chủ nhật: 8:00 - 22:00\nHỗ trợ 24/7 qua chat",
            
            // Social media
            'social_facebook' => 'https://facebook.com/coreshop',
            'social_instagram' => 'https://instagram.com/coreshop',
            'social_youtube' => 'https://youtube.com/coreshop',
            'social_tiktok' => 'https://tiktok.com/@coreshop',
            
            // SEO
            'seo_title_template' => '{title} | {site_name} - Mua sắm trực tuyến',
            'seo_description_template' => '{description} ✓ Giao hàng nhanh ✓ Đổi trả dễ dàng | {site_name}',
            
            // Email settings
            'smtp_from_address' => 'orders@coreshop.com',
            'smtp_from_name' => 'Core Shop',
            
            // Localization - E-commerce focused
            'currency_code' => 'VND',
            'currency_symbol' => '₫',
            'items_per_page' => 20, // More products per page
            
            // Performance - Optimized for e-commerce
            'cache_enabled' => true,
            'cache_duration' => 1800, // 30 minutes for dynamic content
            'lazy_loading' => true,
            'compression_enabled' => true,
            
            // Status
            'is_active' => false,
            'order' => 2,
        ]);
    }

    private function createBlogSettings()
    {
        $this->command->info('📝 Tạo cài đặt Blog...');

        WebsiteSettings::create([
            'site_name' => 'Core Blog',
            'site_tagline' => 'Chia sẻ kiến thức và kinh nghiệm',
            'site_description' => 'Core Blog - Nơi chia sẻ những kiến thức, kinh nghiệm và xu hướng mới nhất trong lĩnh vực công nghệ và phát triển web.',
            'site_keywords' => 'blog, technology, web development, programming, tutorials, tips',
            
            // Contact information
            'contact_email' => 'editor@coreblog.com',
            'contact_phone' => '+84 555 123 456',
            'contact_address' => '789 Đường DEF, Quận 7, TP.HCM, Việt Nam',
            'contact_working_hours' => "Thứ 2 - Thứ 6: 9:00 - 18:00\nLiên hệ qua email bất cứ lúc nào",
            
            // Social media
            'social_facebook' => 'https://facebook.com/coreblog',
            'social_twitter' => 'https://twitter.com/coreblog',
            'social_linkedin' => 'https://linkedin.com/company/coreblog',
            'social_youtube' => 'https://youtube.com/coreblog',
            
            // SEO - Blog optimized
            'seo_title_template' => '{title} | {site_name}',
            'seo_description_template' => '{description} - Đọc thêm tại {site_name}',
            
            // Email settings
            'smtp_from_address' => 'newsletter@coreblog.com',
            'smtp_from_name' => 'Core Blog',
            
            // System settings - Blog focused
            'items_per_page' => 8, // Fewer posts per page for better reading
            'max_upload_size' => 5120, // 5MB for blog images
            'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            
            // Performance - Content focused
            'cache_enabled' => true,
            'cache_duration' => 7200, // 2 hours for articles
            'lazy_loading' => true,
            'compression_enabled' => true,
            'minify_css' => true, // Enable for blog
            'minify_js' => true,
            
            // Status
            'is_active' => false,
            'order' => 3,
        ]);
    }
}
