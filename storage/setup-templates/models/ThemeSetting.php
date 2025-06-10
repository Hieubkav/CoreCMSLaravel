<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'theme_version',
        'author',
        'compatibility',
        'primary_color',
        'secondary_color',
        'accent_color',
        'success_color',
        'warning_color',
        'error_color',
        'info_color',
        'bg_primary',
        'bg_secondary',
        'bg_accent',
        'font_family_primary',
        'font_family_secondary',
        'font_family_heading',
        'font_size_base',
        'line_height_base',
        'layout_type',
        'container_max_width',
        'sidebar_position',
        'sidebar_width',
        'header_style',
        'header_sticky',
        'header_bg_color',
        'header_height',
        'footer_style',
        'footer_bg_color',
        'footer_text_color',
        'footer_show_social',
        'footer_show_newsletter',
        'nav_style',
        'nav_show_icons',
        'nav_hover_effect',
        'nav_mobile_hamburger',
        'button_style',
        'button_size',
        'button_shadows',
        'button_hover_effect',
        'card_style',
        'border_radius',
        'use_shadows',
        'shadow_intensity',
        'animations_enabled',
        'animation_speed',
        'enabled_animations',
        'breakpoints',
        'mobile_first',
        'mobile_nav_style',
        'custom_css',
        'custom_js',
        'custom_fonts',
        'dark_mode_enabled',
        'rtl_support',
        'print_styles',
        'enabled_features',
        'css_minification',
        'js_minification',
        'lazy_loading',
        'critical_css',
        'meta_tags',
        'favicon_path',
        'apple_touch_icon_path',
        'social_meta',
        'is_active',
        'is_default',
        'status',
        'order',
    ];

    protected $casts = [
        'compatibility' => 'array',
        'font_size_base' => 'decimal:1',
        'line_height_base' => 'decimal:2',
        'container_max_width' => 'integer',
        'sidebar_width' => 'integer',
        'header_sticky' => 'boolean',
        'header_height' => 'integer',
        'footer_show_social' => 'boolean',
        'footer_show_newsletter' => 'boolean',
        'nav_show_icons' => 'boolean',
        'nav_mobile_hamburger' => 'boolean',
        'button_shadows' => 'boolean',
        'border_radius' => 'integer',
        'use_shadows' => 'boolean',
        'animations_enabled' => 'boolean',
        'enabled_animations' => 'array',
        'breakpoints' => 'array',
        'mobile_first' => 'boolean',
        'custom_fonts' => 'array',
        'dark_mode_enabled' => 'boolean',
        'rtl_support' => 'boolean',
        'print_styles' => 'boolean',
        'enabled_features' => 'array',
        'css_minification' => 'boolean',
        'js_minification' => 'boolean',
        'lazy_loading' => 'boolean',
        'critical_css' => 'boolean',
        'meta_tags' => 'array',
        'social_meta' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear theme cache when theme is updated
        static::saved(function ($theme) {
            Cache::forget('active_theme');
            Cache::forget('theme_css_' . $theme->id);
            Cache::forget('theme_js_' . $theme->id);
        });

        static::deleted(function ($theme) {
            Cache::forget('active_theme');
        });

        // Ensure only one active theme
        static::saving(function ($theme) {
            if ($theme->is_active) {
                static::where('id', '!=', $theme->id)->update(['is_active' => false]);
            }
        });
    }

    /**
     * Scope cho themes active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Lấy active theme
     */
    public static function getActiveTheme(): ?self
    {
        return Cache::remember('active_theme', 3600, function () {
            return static::where('is_active', true)
                         ->where('status', 'active')
                         ->first() ?: static::getDefaultTheme();
        });
    }

    /**
     * Lấy default theme
     */
    public static function getDefaultTheme(): ?self
    {
        return static::where('is_default', true)
                    ->where('status', 'active')
                    ->first();
    }

    /**
     * Activate theme
     */
    public function activate(): bool
    {
        // Deactivate other themes
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this theme
        $this->is_active = true;
        $saved = $this->save();

        if ($saved) {
            // Clear caches
            Cache::forget('active_theme');
            $this->clearGeneratedAssets();
            
            // Generate new assets
            $this->generateCssFile();
            $this->generateJsFile();
        }

        return $saved;
    }

    /**
     * Generate CSS file from theme settings
     */
    public function generateCssFile(): string
    {
        $css = Cache::remember("theme_css_{$this->id}", 3600, function () {
            return $this->buildCssContent();
        });

        $cssPath = public_path("css/theme-{$this->slug}.css");
        File::put($cssPath, $css);

        return $cssPath;
    }

    /**
     * Generate JS file from theme settings
     */
    public function generateJsFile(): string
    {
        $js = Cache::remember("theme_js_{$this->id}", 3600, function () {
            return $this->buildJsContent();
        });

        $jsPath = public_path("js/theme-{$this->slug}.js");
        File::put($jsPath, $js);

        return $jsPath;
    }

    /**
     * Build CSS content from theme settings
     */
    private function buildCssContent(): string
    {
        $css = ":root {\n";
        
        // CSS Custom Properties
        $css .= "  --primary-color: {$this->primary_color};\n";
        $css .= "  --secondary-color: {$this->secondary_color};\n";
        $css .= "  --accent-color: {$this->accent_color};\n";
        $css .= "  --success-color: {$this->success_color};\n";
        $css .= "  --warning-color: {$this->warning_color};\n";
        $css .= "  --error-color: {$this->error_color};\n";
        $css .= "  --info-color: {$this->info_color};\n";
        
        // Typography
        $css .= "  --font-family-primary: {$this->font_family_primary};\n";
        $css .= "  --font-family-secondary: {$this->font_family_secondary};\n";
        $css .= "  --font-family-heading: {$this->font_family_heading};\n";
        $css .= "  --font-size-base: {$this->font_size_base}px;\n";
        $css .= "  --line-height-base: {$this->line_height_base};\n";
        
        // Layout
        $css .= "  --container-max-width: {$this->container_max_width}px;\n";
        $css .= "  --sidebar-width: {$this->sidebar_width}px;\n";
        $css .= "  --header-height: {$this->header_height}px;\n";
        $css .= "  --border-radius: {$this->border_radius}px;\n";
        
        $css .= "}\n\n";

        // Body styles
        $css .= "body {\n";
        $css .= "  font-family: var(--font-family-primary);\n";
        $css .= "  font-size: var(--font-size-base);\n";
        $css .= "  line-height: var(--line-height-base);\n";
        $css .= "}\n\n";

        // Container styles
        if ($this->layout_type === 'boxed') {
            $css .= ".container {\n";
            $css .= "  max-width: var(--container-max-width);\n";
            $css .= "  margin: 0 auto;\n";
            $css .= "}\n\n";
        }

        // Header styles
        $css .= "header {\n";
        $css .= "  height: var(--header-height);\n";
        if ($this->header_sticky) {
            $css .= "  position: sticky;\n";
            $css .= "  top: 0;\n";
            $css .= "  z-index: 1000;\n";
        }
        $css .= "}\n\n";

        // Button styles
        $css .= ".btn {\n";
        $css .= "  border-radius: var(--border-radius);\n";
        if ($this->button_shadows) {
            $css .= "  box-shadow: 0 2px 4px rgba(0,0,0,0.1);\n";
        }
        $css .= "}\n\n";

        // Add custom CSS
        if ($this->custom_css) {
            $css .= "/* Custom CSS */\n";
            $css .= $this->custom_css . "\n";
        }

        return $css;
    }

    /**
     * Build JS content from theme settings
     */
    private function buildJsContent(): string
    {
        $js = "// Theme: {$this->name}\n";
        $js .= "// Generated: " . now()->toDateTimeString() . "\n\n";

        // Theme configuration object
        $js .= "window.themeConfig = {\n";
        $js .= "  name: '{$this->name}',\n";
        $js .= "  slug: '{$this->slug}',\n";
        $js .= "  animationsEnabled: " . ($this->animations_enabled ? 'true' : 'false') . ",\n";
        $js .= "  animationSpeed: '{$this->animation_speed}',\n";
        $js .= "  lazyLoading: " . ($this->lazy_loading ? 'true' : 'false') . ",\n";
        $js .= "  darkModeEnabled: " . ($this->dark_mode_enabled ? 'true' : 'false') . ",\n";
        $js .= "};\n\n";

        // Animation initialization
        if ($this->animations_enabled) {
            $js .= $this->generateAnimationJs();
        }

        // Lazy loading initialization
        if ($this->lazy_loading) {
            $js .= $this->generateLazyLoadingJs();
        }

        // Add custom JS
        if ($this->custom_js) {
            $js .= "/* Custom JavaScript */\n";
            $js .= $this->custom_js . "\n";
        }

        return $js;
    }

    /**
     * Generate animation JavaScript
     */
    private function generateAnimationJs(): string
    {
        return "
// Animation initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes
    const animatedElements = document.querySelectorAll('[data-animate]');
    animatedElements.forEach(element => {
        element.classList.add('animate-on-scroll');
    });
});
";
    }

    /**
     * Generate lazy loading JavaScript
     */
    private function generateLazyLoadingJs(): string
    {
        return "
// Lazy loading initialization
if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
}
";
    }

    /**
     * Clear generated assets
     */
    private function clearGeneratedAssets(): void
    {
        $cssPath = public_path("css/theme-{$this->slug}.css");
        $jsPath = public_path("js/theme-{$this->slug}.js");

        if (File::exists($cssPath)) {
            File::delete($cssPath);
        }

        if (File::exists($jsPath)) {
            File::delete($jsPath);
        }
    }

    /**
     * Get CSS file URL
     */
    public function getCssUrlAttribute(): string
    {
        return asset("css/theme-{$this->slug}.css");
    }

    /**
     * Get JS file URL
     */
    public function getJsUrlAttribute(): string
    {
        return asset("js/theme-{$this->slug}.js");
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
