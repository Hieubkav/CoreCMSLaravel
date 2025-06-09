<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic site information
            $table->string('site_name')->default('Core Framework')->comment('Tên website');
            $table->string('site_tagline')->nullable()->comment('Slogan website');
            $table->text('site_description')->nullable()->comment('Mô tả website');
            $table->text('site_keywords')->nullable()->comment('Từ khóa SEO');
            $table->string('site_logo')->nullable()->comment('Logo website');
            $table->string('site_favicon')->nullable()->comment('Favicon website');
            
            // Contact information
            $table->string('contact_email')->nullable()->comment('Email liên hệ');
            $table->string('contact_phone')->nullable()->comment('Số điện thoại');
            $table->text('contact_address')->nullable()->comment('Địa chỉ');
            $table->text('contact_working_hours')->nullable()->comment('Giờ làm việc');
            
            // Social media links
            $table->string('social_facebook')->nullable()->comment('Facebook URL');
            $table->string('social_twitter')->nullable()->comment('Twitter URL');
            $table->string('social_instagram')->nullable()->comment('Instagram URL');
            $table->string('social_youtube')->nullable()->comment('YouTube URL');
            $table->string('social_linkedin')->nullable()->comment('LinkedIn URL');
            $table->string('social_tiktok')->nullable()->comment('TikTok URL');
            
            // Maintenance mode
            $table->boolean('maintenance_mode')->default(false)->comment('Chế độ bảo trì');
            $table->text('maintenance_message')->nullable()->comment('Thông báo bảo trì');
            $table->json('maintenance_allowed_ips')->nullable()->comment('IP được phép truy cập khi bảo trì');
            
            // SEO settings
            $table->string('seo_title_template')->nullable()->comment('Template tiêu đề SEO');
            $table->string('seo_description_template')->nullable()->comment('Template mô tả SEO');
            $table->string('seo_og_image')->nullable()->comment('Ảnh Open Graph mặc định');
            
            // Analytics
            $table->string('analytics_google_id')->nullable()->comment('Google Analytics ID');
            $table->string('analytics_facebook_pixel')->nullable()->comment('Facebook Pixel ID');
            $table->string('analytics_gtm_id')->nullable()->comment('Google Tag Manager ID');
            
            // Email settings
            $table->string('smtp_host')->nullable()->comment('SMTP Host');
            $table->integer('smtp_port')->default(587)->comment('SMTP Port');
            $table->string('smtp_username')->nullable()->comment('SMTP Username');
            $table->string('smtp_password')->nullable()->comment('SMTP Password');
            $table->enum('smtp_encryption', ['tls', 'ssl', 'none'])->default('tls')->comment('SMTP Encryption');
            $table->string('smtp_from_address')->nullable()->comment('Email gửi đi');
            $table->string('smtp_from_name')->nullable()->comment('Tên người gửi');
            
            // Localization
            $table->string('currency_code', 3)->default('VND')->comment('Mã tiền tệ');
            $table->string('currency_symbol', 10)->default('₫')->comment('Ký hiệu tiền tệ');
            $table->string('timezone')->default('Asia/Ho_Chi_Minh')->comment('Múi giờ');
            $table->string('date_format')->default('d/m/Y')->comment('Định dạng ngày');
            $table->string('time_format')->default('H:i')->comment('Định dạng giờ');
            $table->string('language', 2)->default('vi')->comment('Ngôn ngữ');
            
            // System settings
            $table->integer('items_per_page')->default(12)->comment('Số item mỗi trang');
            $table->integer('max_upload_size')->default(10240)->comment('Kích thước upload tối đa (KB)');
            $table->json('allowed_file_types')->nullable()->comment('Loại file được phép upload');
            
            // Backup settings
            $table->enum('backup_frequency', ['daily', 'weekly', 'monthly', 'manual'])->default('weekly')->comment('Tần suất backup');
            $table->integer('backup_retention_days')->default(30)->comment('Số ngày lưu backup');
            
            // Performance settings
            $table->boolean('cache_enabled')->default(true)->comment('Bật cache');
            $table->integer('cache_duration')->default(3600)->comment('Thời gian cache (giây)');
            $table->boolean('compression_enabled')->default(true)->comment('Bật nén');
            $table->boolean('minify_css')->default(false)->comment('Minify CSS');
            $table->boolean('minify_js')->default(false)->comment('Minify JS');
            $table->boolean('lazy_loading')->default(true)->comment('Lazy loading ảnh');
            $table->string('cdn_url')->nullable()->comment('CDN URL');
            
            // Custom code
            $table->longText('custom_css')->nullable()->comment('CSS tùy chỉnh');
            $table->longText('custom_js')->nullable()->comment('JavaScript tùy chỉnh');
            $table->longText('custom_head_code')->nullable()->comment('Code trong <head>');
            $table->longText('custom_footer_code')->nullable()->comment('Code trước </body>');
            
            // Status and ordering
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->integer('order')->default(0)->comment('Thứ tự sắp xếp');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('is_active');
            $table->index(['is_active', 'order']);
            $table->index('maintenance_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
