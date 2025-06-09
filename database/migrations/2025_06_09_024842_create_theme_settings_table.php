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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Theme identification
            $table->string('theme_version', 10)->default('1.0');
            $table->string('author')->nullable();
            $table->json('compatibility')->nullable(); // Laravel versions, PHP versions

            // Color scheme
            $table->string('primary_color', 7)->default('#dc2626'); // Red theme
            $table->string('secondary_color', 7)->default('#1f2937'); // Dark gray
            $table->string('accent_color', 7)->default('#f59e0b'); // Amber
            $table->string('success_color', 7)->default('#10b981'); // Green
            $table->string('warning_color', 7)->default('#f59e0b'); // Amber
            $table->string('error_color', 7)->default('#ef4444'); // Red
            $table->string('info_color', 7)->default('#3b82f6'); // Blue

            // Background colors
            $table->string('bg_primary', 20)->default('bg-white');
            $table->string('bg_secondary', 20)->default('bg-gray-50');
            $table->string('bg_accent', 20)->default('bg-red-50');

            // Typography
            $table->string('font_family_primary')->default('Inter, system-ui, sans-serif');
            $table->string('font_family_secondary')->default('Inter, system-ui, sans-serif');
            $table->string('font_family_heading')->default('Inter, system-ui, sans-serif');
            $table->decimal('font_size_base', 3, 1)->default(16.0); // Base font size in px
            $table->decimal('line_height_base', 3, 2)->default(1.5); // Line height multiplier

            // Layout settings
            $table->string('layout_type')->default('full-width'); // full-width, boxed, fluid
            $table->integer('container_max_width')->default(1200); // Max container width in px
            $table->string('sidebar_position')->default('right'); // left, right, none
            $table->integer('sidebar_width')->default(300); // Sidebar width in px

            // Header settings
            $table->string('header_style')->default('default'); // default, minimal, centered, split
            $table->boolean('header_sticky')->default(true);
            $table->string('header_bg_color', 20)->default('bg-white');
            $table->integer('header_height')->default(80); // Header height in px

            // Footer settings
            $table->string('footer_style')->default('default'); // default, minimal, columns
            $table->string('footer_bg_color', 20)->default('bg-gray-900');
            $table->string('footer_text_color', 20)->default('text-white');
            $table->boolean('footer_show_social')->default(true);
            $table->boolean('footer_show_newsletter')->default(true);

            // Navigation settings
            $table->string('nav_style')->default('horizontal'); // horizontal, vertical, mega
            $table->boolean('nav_show_icons')->default(false);
            $table->string('nav_hover_effect')->default('underline'); // underline, background, scale
            $table->boolean('nav_mobile_hamburger')->default(true);

            // Button styles
            $table->string('button_style')->default('rounded'); // rounded, square, pill
            $table->string('button_size')->default('medium'); // small, medium, large
            $table->boolean('button_shadows')->default(true);
            $table->string('button_hover_effect')->default('scale'); // scale, shadow, color

            // Card/Component styles
            $table->string('card_style')->default('shadow'); // shadow, border, minimal
            $table->integer('border_radius')->default(8); // Border radius in px
            $table->boolean('use_shadows')->default(true);
            $table->string('shadow_intensity')->default('medium'); // light, medium, heavy

            // Animation settings
            $table->boolean('animations_enabled')->default(true);
            $table->string('animation_speed')->default('normal'); // slow, normal, fast
            $table->json('enabled_animations')->nullable(); // Array of enabled animation types

            // Responsive settings
            $table->json('breakpoints')->nullable(); // Custom breakpoints
            $table->boolean('mobile_first')->default(true);
            $table->string('mobile_nav_style')->default('overlay'); // overlay, push, slide

            // Custom CSS/JS
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->json('custom_fonts')->nullable(); // Google Fonts, custom fonts

            // Theme features
            $table->boolean('dark_mode_enabled')->default(false);
            $table->boolean('rtl_support')->default(false);
            $table->boolean('print_styles')->default(true);
            $table->json('enabled_features')->nullable(); // Array of enabled theme features

            // Performance settings
            $table->boolean('css_minification')->default(true);
            $table->boolean('js_minification')->default(true);
            $table->boolean('lazy_loading')->default(true);
            $table->boolean('critical_css')->default(false);

            // SEO and meta
            $table->json('meta_tags')->nullable(); // Default meta tags
            $table->string('favicon_path')->nullable();
            $table->string('apple_touch_icon_path')->nullable();
            $table->json('social_meta')->nullable(); // OG tags, Twitter cards

            $table->boolean('is_active')->default(false);
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'inactive', 'development'])->default('active');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['status', 'is_active']);
            $table->index('slug');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
