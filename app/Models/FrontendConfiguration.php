<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        // Theme Configuration
        'theme_mode',
        'design_style',
        'icon_system',
        
        // Color Scheme
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        
        // Typography
        'font_family',
        'font_size',
        'font_weight',
        
        // Layout Settings
        'container_width',
        'enable_breadcrumbs',
        'enable_back_to_top',
        'enable_loading_spinner',
        
        // Navigation
        'sticky_navbar',
        'show_search_bar',
        'show_language_switcher',
        'menu_style',
        
        // Footer
        'show_footer_social',
        'show_footer_newsletter',
        'footer_copyright',
        
        // Performance & SEO
        'enable_lazy_loading',
        'enable_image_optimization',
        'enable_minification',
        'enable_caching',
        
        // Error Pages
        'error_pages',
        
        // Custom CSS/JS
        'custom_css',
        'custom_js',
        'custom_head_tags',
        
        // Metadata
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'enable_breadcrumbs' => 'boolean',
        'enable_back_to_top' => 'boolean',
        'enable_loading_spinner' => 'boolean',
        'sticky_navbar' => 'boolean',
        'show_search_bar' => 'boolean',
        'show_language_switcher' => 'boolean',
        'show_footer_social' => 'boolean',
        'show_footer_newsletter' => 'boolean',
        'enable_lazy_loading' => 'boolean',
        'enable_image_optimization' => 'boolean',
        'enable_minification' => 'boolean',
        'enable_caching' => 'boolean',
        'error_pages' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Lấy cấu hình active hiện tại
     */
    public static function getActiveConfig()
    {
        return static::where('is_active', true)->first() ?? static::getDefaultConfig();
    }

    /**
     * Tạo hoặc cập nhật cấu hình
     */
    public static function updateOrCreateConfig(array $data)
    {
        // Deactivate all existing configs
        static::query()->update(['is_active' => false]);

        // Create new active config
        return static::create(array_merge($data, ['is_active' => true]));
    }

    /**
     * Lấy cấu hình mặc định
     */
    public static function getDefaultConfig()
    {
        return (object) [
            'theme_mode' => 'light',
            'design_style' => 'minimalist',
            'icon_system' => 'fontawesome',
            'primary_color' => '#dc2626',
            'secondary_color' => '#f97316',
            'accent_color' => '#059669',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'font_family' => 'Inter',
            'font_size' => 'base',
            'font_weight' => 'normal',
            'container_width' => 'max-w-7xl',
            'enable_breadcrumbs' => true,
            'enable_back_to_top' => true,
            'enable_loading_spinner' => true,
            'sticky_navbar' => true,
            'show_search_bar' => true,
            'show_language_switcher' => false,
            'menu_style' => 'horizontal',
            'show_footer_social' => false,
            'show_footer_newsletter' => false,
            'footer_copyright' => null,
            'enable_lazy_loading' => true,
            'enable_image_optimization' => true,
            'enable_minification' => true,
            'enable_caching' => true,
            'error_pages' => ['404', '500'],
            'custom_css' => null,
            'custom_js' => null,
            'custom_head_tags' => null,
            'is_active' => true,
        ];
    }

    /**
     * Scope để lấy config active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Lấy CSS variables từ cấu hình
     */
    public function getCssVariables(): string
    {
        return "
            :root {
                --primary-color: {$this->primary_color};
                --secondary-color: {$this->secondary_color};
                --accent-color: {$this->accent_color};
                --background-color: {$this->background_color};
                --text-color: {$this->text_color};
                --font-family: '{$this->font_family}', sans-serif;
            }
        ";
    }

    /**
     * Kiểm tra xem có feature nào được enable không
     */
    public function hasFeature(string $feature): bool
    {
        return $this->getAttribute($feature) === true;
    }
}
