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
        Schema::create('web_designs', function (Blueprint $table) {
            $table->id();
            
            // Hero Banner Section
            $table->boolean('hero_banner_enabled')->default(true);
            $table->integer('hero_banner_order')->default(1);
            $table->string('hero_banner_title')->nullable();
            $table->text('hero_banner_description')->nullable();
            $table->string('hero_banner_bg_color')->default('bg-white');
            
            // Courses Overview Section
            $table->boolean('courses_overview_enabled')->default(true);
            $table->integer('courses_overview_order')->default(2);
            $table->string('courses_overview_title')->default('Khóa học nổi bật');
            $table->text('courses_overview_description')->nullable();
            $table->string('courses_overview_bg_color')->default('bg-gray-50');
            
            // Album Timeline Section
            $table->boolean('album_timeline_enabled')->default(true);
            $table->integer('album_timeline_order')->default(3);
            $table->string('album_timeline_title')->default('Hình ảnh hoạt động');
            $table->text('album_timeline_description')->nullable();
            $table->string('album_timeline_bg_color')->default('bg-white');
            
            // Course Groups Section
            $table->boolean('course_groups_enabled')->default(true);
            $table->integer('course_groups_order')->default(4);
            $table->string('course_groups_title')->default('Nhóm khóa học');
            $table->text('course_groups_description')->nullable();
            $table->string('course_groups_bg_color')->default('bg-gray-50');
            
            // Course Categories Section
            $table->boolean('course_categories_enabled')->default(true);
            $table->integer('course_categories_order')->default(5);
            $table->string('course_categories_title')->default('Danh mục khóa học');
            $table->text('course_categories_description')->nullable();
            $table->string('course_categories_bg_color')->default('bg-white');
            
            // Testimonials Section
            $table->boolean('testimonials_enabled')->default(true);
            $table->integer('testimonials_order')->default(6);
            $table->string('testimonials_title')->default('Đánh giá học viên');
            $table->text('testimonials_description')->nullable();
            $table->string('testimonials_bg_color')->default('bg-red-50');
            
            // FAQ Section
            $table->boolean('faq_enabled')->default(true);
            $table->integer('faq_order')->default(7);
            $table->string('faq_title')->default('Câu hỏi thường gặp');
            $table->text('faq_description')->nullable();
            $table->string('faq_bg_color')->default('bg-white');
            
            // Partners Section
            $table->boolean('partners_enabled')->default(true);
            $table->integer('partners_order')->default(8);
            $table->string('partners_title')->default('Đối tác');
            $table->text('partners_description')->nullable();
            $table->string('partners_bg_color')->default('bg-gray-50');
            
            // Blog Posts Section
            $table->boolean('blog_posts_enabled')->default(true);
            $table->integer('blog_posts_order')->default(9);
            $table->string('blog_posts_title')->default('Tin tức mới nhất');
            $table->text('blog_posts_description')->nullable();
            $table->string('blog_posts_bg_color')->default('bg-white');
            
            // Homepage CTA Section
            $table->boolean('homepage_cta_enabled')->default(true);
            $table->integer('homepage_cta_order')->default(10);
            $table->string('homepage_cta_title')->default('Bắt đầu học ngay hôm nay');
            $table->text('homepage_cta_description')->default('Tham gia cùng hàng nghìn học viên đã tin tưởng chúng tôi');
            $table->string('homepage_cta_primary_button_text')->default('Xem khóa học');
            $table->string('homepage_cta_primary_button_link')->default('/courses');
            $table->string('homepage_cta_secondary_button_text')->default('Đăng ký ngay');
            $table->string('homepage_cta_secondary_button_link')->default('/register');
            $table->string('homepage_cta_bg_color')->default('bg-red-600');
            
            // Global Settings
            $table->boolean('animations_enabled')->default(true);
            $table->string('animation_speed')->default('normal'); // slow, normal, fast
            $table->json('enabled_animations')->default('["fade", "slide"]');
            $table->boolean('lazy_loading_enabled')->default(true);
            $table->integer('cache_duration')->default(3600); // seconds
            
            // Meta
            $table->timestamp('last_updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['hero_banner_enabled', 'hero_banner_order'], 'idx_hero_banner');
            $table->index(['courses_overview_enabled', 'courses_overview_order'], 'idx_courses_overview');
            $table->index(['testimonials_enabled', 'testimonials_order'], 'idx_testimonials');
            $table->index('last_updated_at', 'idx_last_updated');
            
            // Foreign key
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_designs');
    }
};
