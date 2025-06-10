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
        Schema::create('frontend_configurations', function (Blueprint $table) {
            $table->id();

            // Theme Configuration
            $table->string('theme_mode')->default('light'); // light, dark, auto
            $table->string('design_style')->default('minimalist'); // minimalist, modern, classic
            $table->string('icon_system')->default('fontawesome'); // fontawesome, heroicons, custom

            // Color Scheme
            $table->string('primary_color')->default('#dc2626'); // Red-600
            $table->string('secondary_color')->default('#f97316'); // Orange-500
            $table->string('accent_color')->default('#059669'); // Emerald-600
            $table->string('background_color')->default('#ffffff');
            $table->string('text_color')->default('#1f2937');

            // Typography
            $table->string('font_family')->default('Inter'); // Inter, Roboto, Open Sans
            $table->string('font_size')->default('base'); // sm, base, lg
            $table->string('font_weight')->default('normal'); // light, normal, medium, semibold

            // Layout Settings
            $table->string('container_width')->default('max-w-7xl'); // max-w-6xl, max-w-7xl, max-w-full
            $table->boolean('enable_breadcrumbs')->default(true);
            $table->boolean('enable_back_to_top')->default(true);
            $table->boolean('enable_loading_spinner')->default(true);

            // Navigation
            $table->boolean('sticky_navbar')->default(true);
            $table->boolean('show_search_bar')->default(true);
            $table->boolean('show_language_switcher')->default(false);
            $table->string('menu_style')->default('horizontal'); // horizontal, vertical, mega

            // Footer
            $table->boolean('show_footer_social')->default(false);
            $table->boolean('show_footer_newsletter')->default(false);
            $table->text('footer_copyright')->nullable();

            // Performance & SEO
            $table->boolean('enable_lazy_loading')->default(true);
            $table->boolean('enable_image_optimization')->default(true);
            $table->boolean('enable_minification')->default(true);
            $table->boolean('enable_caching')->default(true);

            // Error Pages
            $table->json('error_pages')->nullable(); // ['404', '500', '503']

            // Custom CSS/JS
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->text('custom_head_tags')->nullable();

            // Metadata
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontend_configurations');
    }
};