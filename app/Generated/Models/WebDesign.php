<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class WebDesign extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'theme_type',
        'color_scheme',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'link_color',
        'border_color',
        'font_family_primary',
        'font_family_secondary',
        'font_size_base',
        'font_weight_normal',
        'font_weight_bold',
        'line_height',
        'letter_spacing',
        'border_radius',
        'box_shadow',
        'transition_duration',
        'animation_style',
        'layout_style',
        'container_width',
        'sidebar_width',
        'header_height',
        'footer_height',
        'spacing_unit',
        'grid_columns',
        'breakpoint_mobile',
        'breakpoint_tablet',
        'breakpoint_desktop',
        'hero_style',
        'hero_height',
        'hero_overlay',
        'hero_text_align',
        'button_style',
        'button_size',
        'button_radius',
        'card_style',
        'card_shadow',
        'card_radius',
        'form_style',
        'input_style',
        'input_radius',
        'navigation_style',
        'navigation_position',
        'navigation_background',
        'footer_style',
        'footer_background',
        'custom_css',
        'custom_js',
        'preview_image',
        'is_active',
        'is_default',
        'order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hero_overlay' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
        'font_size_base' => 'integer',
        'font_weight_normal' => 'integer',
        'font_weight_bold' => 'integer',
        'header_height' => 'integer',
        'footer_height' => 'integer',
        'spacing_unit' => 'integer',
        'grid_columns' => 'integer',
        'breakpoint_mobile' => 'integer',
        'breakpoint_tablet' => 'integer',
        'breakpoint_desktop' => 'integer',
        'hero_height' => 'integer',
        'button_radius' => 'integer',
        'card_radius' => 'integer',
        'input_radius' => 'integer',
        'border_radius' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'theme_type' => 'modern',
        'color_scheme' => 'light',
        'primary_color' => '#dc2626',
        'secondary_color' => '#6b7280',
        'accent_color' => '#f59e0b',
        'background_color' => '#ffffff',
        'text_color' => '#1f2937',
        'link_color' => '#dc2626',
        'border_color' => '#e5e7eb',
        'font_family_primary' => 'Inter',
        'font_family_secondary' => 'Inter',
        'font_size_base' => 16,
        'font_weight_normal' => 400,
        'font_weight_bold' => 600,
        'line_height' => '1.5',
        'letter_spacing' => '0',
        'border_radius' => 8,
        'box_shadow' => '0 1px 3px rgba(0, 0, 0, 0.1)',
        'transition_duration' => '200ms',
        'animation_style' => 'smooth',
        'layout_style' => 'boxed',
        'container_width' => 1200,
        'sidebar_width' => 300,
        'header_height' => 80,
        'footer_height' => 200,
        'spacing_unit' => 16,
        'grid_columns' => 12,
        'breakpoint_mobile' => 640,
        'breakpoint_tablet' => 768,
        'breakpoint_desktop' => 1024,
        'hero_style' => 'gradient',
        'hero_height' => 500,
        'hero_overlay' => true,
        'hero_text_align' => 'center',
        'button_style' => 'solid',
        'button_size' => 'medium',
        'button_radius' => 8,
        'card_style' => 'elevated',
        'card_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',
        'card_radius' => 12,
        'form_style' => 'modern',
        'input_style' => 'outlined',
        'input_radius' => 8,
        'navigation_style' => 'horizontal',
        'navigation_position' => 'top',
        'navigation_background' => 'transparent',
        'footer_style' => 'modern',
        'footer_background' => '#1f2937',
        'is_active' => true,
        'is_default' => false,
        'order' => 0,
    ];

    /**
     * Theme type options
     */
    public static function getThemeTypes(): array
    {
        return [
            'modern' => 'Modern',
            'classic' => 'Classic',
            'minimalist' => 'Minimalist',
            'creative' => 'Creative',
            'corporate' => 'Corporate',
            'ecommerce' => 'E-commerce',
            'blog' => 'Blog',
            'portfolio' => 'Portfolio',
        ];
    }

    /**
     * Color scheme options
     */
    public static function getColorSchemes(): array
    {
        return [
            'light' => 'Light',
            'dark' => 'Dark',
            'auto' => 'Auto (System)',
        ];
    }

    /**
     * Font family options
     */
    public static function getFontFamilies(): array
    {
        return [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Nunito' => 'Nunito',
            'Source Sans Pro' => 'Source Sans Pro',
            'Playfair Display' => 'Playfair Display',
            'Merriweather' => 'Merriweather',
        ];
    }

    /**
     * Animation style options
     */
    public static function getAnimationStyles(): array
    {
        return [
            'none' => 'None',
            'smooth' => 'Smooth',
            'bounce' => 'Bounce',
            'elastic' => 'Elastic',
            'fade' => 'Fade',
            'slide' => 'Slide',
        ];
    }

    /**
     * Layout style options
     */
    public static function getLayoutStyles(): array
    {
        return [
            'boxed' => 'Boxed',
            'full-width' => 'Full Width',
            'fluid' => 'Fluid',
        ];
    }

    /**
     * Hero style options
     */
    public static function getHeroStyles(): array
    {
        return [
            'gradient' => 'Gradient',
            'image' => 'Image',
            'video' => 'Video',
            'solid' => 'Solid Color',
            'pattern' => 'Pattern',
        ];
    }

    /**
     * Button style options
     */
    public static function getButtonStyles(): array
    {
        return [
            'solid' => 'Solid',
            'outlined' => 'Outlined',
            'ghost' => 'Ghost',
            'gradient' => 'Gradient',
        ];
    }

    /**
     * Button size options
     */
    public static function getButtonSizes(): array
    {
        return [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            'extra-large' => 'Extra Large',
        ];
    }

    /**
     * Card style options
     */
    public static function getCardStyles(): array
    {
        return [
            'flat' => 'Flat',
            'elevated' => 'Elevated',
            'outlined' => 'Outlined',
            'filled' => 'Filled',
        ];
    }

    /**
     * Navigation style options
     */
    public static function getNavigationStyles(): array
    {
        return [
            'horizontal' => 'Horizontal',
            'vertical' => 'Vertical',
            'mega' => 'Mega Menu',
            'sidebar' => 'Sidebar',
        ];
    }

    /**
     * Navigation position options
     */
    public static function getNavigationPositions(): array
    {
        return [
            'top' => 'Top',
            'bottom' => 'Bottom',
            'left' => 'Left',
            'right' => 'Right',
            'sticky' => 'Sticky Top',
        ];
    }

    /**
     * Get active design
     */
    public static function getActive(): ?self
    {
        return Cache::remember('web_design_active', 3600, function () {
            return static::where('is_active', true)
                ->orderBy('order')
                ->first();
        });
    }

    /**
     * Set as active (deactivate others)
     */
    public function setActive(): bool
    {
        // Deactivate all other designs
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this design
        $result = $this->update(['is_active' => true]);
        
        // Clear cache
        $this->clearCache();
        
        return $result;
    }

    /**
     * Set as default
     */
    public function setDefault(): bool
    {
        // Remove default from all other designs
        static::where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // Set this as default
        $result = $this->update(['is_default' => true]);
        
        // Clear cache
        $this->clearCache();
        
        return $result;
    }

    /**
     * Generate CSS variables
     */
    public function getCssVariables(): array
    {
        return [
            '--primary-color' => $this->primary_color,
            '--secondary-color' => $this->secondary_color,
            '--accent-color' => $this->accent_color,
            '--background-color' => $this->background_color,
            '--text-color' => $this->text_color,
            '--link-color' => $this->link_color,
            '--border-color' => $this->border_color,
            '--font-family-primary' => $this->font_family_primary,
            '--font-family-secondary' => $this->font_family_secondary,
            '--font-size-base' => $this->font_size_base . 'px',
            '--font-weight-normal' => $this->font_weight_normal,
            '--font-weight-bold' => $this->font_weight_bold,
            '--line-height' => $this->line_height,
            '--letter-spacing' => $this->letter_spacing,
            '--border-radius' => $this->border_radius . 'px',
            '--box-shadow' => $this->box_shadow,
            '--transition-duration' => $this->transition_duration,
            '--container-width' => $this->container_width . 'px',
            '--sidebar-width' => $this->sidebar_width . 'px',
            '--header-height' => $this->header_height . 'px',
            '--footer-height' => $this->footer_height . 'px',
            '--spacing-unit' => $this->spacing_unit . 'px',
            '--hero-height' => $this->hero_height . 'px',
            '--button-radius' => $this->button_radius . 'px',
            '--card-radius' => $this->card_radius . 'px',
            '--input-radius' => $this->input_radius . 'px',
        ];
    }

    /**
     * Clear design cache
     */
    public function clearCache(): void
    {
        Cache::forget('web_design_active');
        Cache::forget('web_design_css_' . $this->id);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when design is updated
        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });

        // Auto-increment order for new records
        static::creating(function ($model) {
            if (is_null($model->order)) {
                $maxOrder = static::max('order');
                $model->order = ($maxOrder ?? 0) + 1;
            }
        });
    }
}
