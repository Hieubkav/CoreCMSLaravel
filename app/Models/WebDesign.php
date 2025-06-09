<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        // Hero Banner
        'hero_banner_enabled',
        'hero_banner_order',
        'hero_banner_title',
        'hero_banner_description',
        'hero_banner_bg_color',
        
        // Courses Overview
        'courses_overview_enabled',
        'courses_overview_order',
        'courses_overview_title',
        'courses_overview_description',
        'courses_overview_bg_color',
        
        // Album Timeline
        'album_timeline_enabled',
        'album_timeline_order',
        'album_timeline_title',
        'album_timeline_description',
        'album_timeline_bg_color',
        
        // Course Groups
        'course_groups_enabled',
        'course_groups_order',
        'course_groups_title',
        'course_groups_description',
        'course_groups_bg_color',
        
        // Course Categories
        'course_categories_enabled',
        'course_categories_order',
        'course_categories_title',
        'course_categories_description',
        'course_categories_bg_color',
        
        // Testimonials
        'testimonials_enabled',
        'testimonials_order',
        'testimonials_title',
        'testimonials_description',
        'testimonials_bg_color',
        
        // FAQ
        'faq_enabled',
        'faq_order',
        'faq_title',
        'faq_description',
        'faq_bg_color',
        
        // Partners
        'partners_enabled',
        'partners_order',
        'partners_title',
        'partners_description',
        'partners_bg_color',
        
        // Blog Posts
        'blog_posts_enabled',
        'blog_posts_order',
        'blog_posts_title',
        'blog_posts_description',
        'blog_posts_bg_color',
        
        // Homepage CTA
        'homepage_cta_enabled',
        'homepage_cta_order',
        'homepage_cta_title',
        'homepage_cta_description',
        'homepage_cta_primary_button_text',
        'homepage_cta_primary_button_link',
        'homepage_cta_secondary_button_text',
        'homepage_cta_secondary_button_link',
        'homepage_cta_bg_color',
        
        // Global Settings
        'animations_enabled',
        'animation_speed',
        'enabled_animations',
        'lazy_loading_enabled',
        'cache_duration',
        
        // Meta
        'last_updated_at',
        'updated_by',
    ];

    protected $casts = [
        // Boolean fields
        'hero_banner_enabled' => 'boolean',
        'courses_overview_enabled' => 'boolean',
        'album_timeline_enabled' => 'boolean',
        'course_groups_enabled' => 'boolean',
        'course_categories_enabled' => 'boolean',
        'testimonials_enabled' => 'boolean',
        'faq_enabled' => 'boolean',
        'partners_enabled' => 'boolean',
        'blog_posts_enabled' => 'boolean',
        'homepage_cta_enabled' => 'boolean',
        'animations_enabled' => 'boolean',
        'lazy_loading_enabled' => 'boolean',
        
        // Integer fields
        'hero_banner_order' => 'integer',
        'courses_overview_order' => 'integer',
        'album_timeline_order' => 'integer',
        'course_groups_order' => 'integer',
        'course_categories_order' => 'integer',
        'testimonials_order' => 'integer',
        'faq_order' => 'integer',
        'partners_order' => 'integer',
        'blog_posts_order' => 'integer',
        'homepage_cta_order' => 'integer',
        'cache_duration' => 'integer',
        
        // JSON fields
        'enabled_animations' => 'array',
        
        // Datetime fields
        'last_updated_at' => 'datetime',
    ];

    /**
     * Relationship với User (người cập nhật cuối)
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Lấy instance WebDesign (singleton pattern)
     */
    public static function getInstance()
    {
        return Cache::remember('web_design_settings', 3600, function () {
            return static::first() ?? static::create([
                'hero_banner_enabled' => true,
                'courses_overview_enabled' => true,
                'album_timeline_enabled' => true,
                'course_groups_enabled' => true,
                'course_categories_enabled' => true,
                'testimonials_enabled' => true,
                'faq_enabled' => true,
                'partners_enabled' => true,
                'blog_posts_enabled' => true,
                'homepage_cta_enabled' => true,
            ]);
        });
    }

    /**
     * Lấy danh sách sections được sắp xếp theo order
     */
    public function getSortedSections()
    {
        $sections = [
            'hero_banner' => [
                'enabled' => $this->hero_banner_enabled,
                'order' => $this->hero_banner_order,
                'title' => $this->hero_banner_title,
                'description' => $this->hero_banner_description,
                'bg_color' => $this->hero_banner_bg_color,
                'name' => 'Hero Banner',
                'icon' => 'fas fa-image',
            ],
            'courses_overview' => [
                'enabled' => $this->courses_overview_enabled,
                'order' => $this->courses_overview_order,
                'title' => $this->courses_overview_title,
                'description' => $this->courses_overview_description,
                'bg_color' => $this->courses_overview_bg_color,
                'name' => 'Tổng quan Khóa học',
                'icon' => 'fas fa-graduation-cap',
            ],
            'album_timeline' => [
                'enabled' => $this->album_timeline_enabled,
                'order' => $this->album_timeline_order,
                'title' => $this->album_timeline_title,
                'description' => $this->album_timeline_description,
                'bg_color' => $this->album_timeline_bg_color,
                'name' => 'Album Timeline',
                'icon' => 'fas fa-images',
            ],
            'course_groups' => [
                'enabled' => $this->course_groups_enabled,
                'order' => $this->course_groups_order,
                'title' => $this->course_groups_title,
                'description' => $this->course_groups_description,
                'bg_color' => $this->course_groups_bg_color,
                'name' => 'Nhóm Khóa học',
                'icon' => 'fas fa-layer-group',
            ],
            'course_categories' => [
                'enabled' => $this->course_categories_enabled,
                'order' => $this->course_categories_order,
                'title' => $this->course_categories_title,
                'description' => $this->course_categories_description,
                'bg_color' => $this->course_categories_bg_color,
                'name' => 'Danh mục Khóa học',
                'icon' => 'fas fa-th-large',
            ],
            'testimonials' => [
                'enabled' => $this->testimonials_enabled,
                'order' => $this->testimonials_order,
                'title' => $this->testimonials_title,
                'description' => $this->testimonials_description,
                'bg_color' => $this->testimonials_bg_color,
                'name' => 'Đánh giá Học viên',
                'icon' => 'fas fa-quote-left',
            ],
            'faq' => [
                'enabled' => $this->faq_enabled,
                'order' => $this->faq_order,
                'title' => $this->faq_title,
                'description' => $this->faq_description,
                'bg_color' => $this->faq_bg_color,
                'name' => 'Câu hỏi thường gặp',
                'icon' => 'fas fa-question-circle',
            ],
            'partners' => [
                'enabled' => $this->partners_enabled,
                'order' => $this->partners_order,
                'title' => $this->partners_title,
                'description' => $this->partners_description,
                'bg_color' => $this->partners_bg_color,
                'name' => 'Đối tác',
                'icon' => 'fas fa-handshake',
            ],
            'blog_posts' => [
                'enabled' => $this->blog_posts_enabled,
                'order' => $this->blog_posts_order,
                'title' => $this->blog_posts_title,
                'description' => $this->blog_posts_description,
                'bg_color' => $this->blog_posts_bg_color,
                'name' => 'Tin tức Blog',
                'icon' => 'fas fa-blog',
            ],
            'homepage_cta' => [
                'enabled' => $this->homepage_cta_enabled,
                'order' => $this->homepage_cta_order,
                'title' => $this->homepage_cta_title,
                'description' => $this->homepage_cta_description,
                'bg_color' => $this->homepage_cta_bg_color,
                'name' => 'Call to Action',
                'icon' => 'fas fa-bullhorn',
                'primary_button_text' => $this->homepage_cta_primary_button_text,
                'primary_button_link' => $this->homepage_cta_primary_button_link,
                'secondary_button_text' => $this->homepage_cta_secondary_button_text,
                'secondary_button_link' => $this->homepage_cta_secondary_button_link,
            ],
        ];

        // Sắp xếp theo order
        uasort($sections, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $sections;
    }

    /**
     * Lấy số lượng sections đang enabled
     */
    public function getEnabledSectionsCount()
    {
        return collect($this->getSortedSections())->where('enabled', true)->count();
    }

    /**
     * Lấy tổng số sections
     */
    public function getTotalSectionsCount()
    {
        return count($this->getSortedSections());
    }

    /**
     * Clear cache khi model được cập nhật
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('web_design_settings');
        });

        static::deleted(function () {
            Cache::forget('web_design_settings');
        });
    }
}
