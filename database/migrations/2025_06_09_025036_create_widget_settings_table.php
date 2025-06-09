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
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Widget identification and type
            $table->enum('widget_type', [
                'text', 'html', 'image', 'gallery', 'video', 'audio',
                'recent_posts', 'popular_posts', 'categories', 'tags', 'archives',
                'recent_products', 'featured_products', 'product_categories',
                'search', 'newsletter', 'social_media', 'contact_info',
                'testimonials', 'faq', 'calendar', 'weather', 'map',
                'custom', 'shortcode'
            ])->default('custom');
            $table->string('widget_class')->nullable(); // PHP class for dynamic widgets
            $table->string('template_path')->nullable(); // Blade template path

            // Widget content and configuration
            $table->json('widget_config'); // Widget-specific configuration
            $table->longText('content')->nullable(); // Static content for text/HTML widgets
            $table->json('data_source')->nullable(); // Dynamic data configuration
            $table->json('display_options')->nullable(); // Display settings

            // Positioning and layout
            $table->enum('position', [
                'sidebar_left', 'sidebar_right', 'header', 'footer',
                'before_content', 'after_content', 'custom'
            ])->default('sidebar_right');
            $table->string('custom_position')->nullable(); // Custom position identifier
            $table->integer('order_position')->default(0); // Order within position

            // Responsive behavior
            $table->boolean('show_on_mobile')->default(true);
            $table->boolean('show_on_tablet')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->json('responsive_config')->nullable(); // Responsive-specific settings

            // Visibility rules
            $table->json('show_on_pages')->nullable(); // Page types/IDs where widget shows
            $table->json('hide_on_pages')->nullable(); // Page types/IDs where widget hides
            $table->json('show_for_roles')->nullable(); // User roles that can see widget
            $table->json('show_for_users')->nullable(); // Specific users that can see widget
            $table->boolean('show_for_guests')->default(true); // Show for non-logged users

            // Conditional display
            $table->json('display_conditions')->nullable(); // Complex display conditions
            $table->datetime('show_from')->nullable(); // Start showing from date
            $table->datetime('show_until')->nullable(); // Stop showing after date
            $table->json('schedule')->nullable(); // Weekly schedule (days/hours)

            // Styling and appearance
            $table->string('css_class')->nullable(); // Custom CSS classes
            $table->string('css_id')->nullable(); // Custom CSS ID
            $table->json('inline_styles')->nullable(); // Inline CSS styles
            $table->longText('custom_css')->nullable(); // Widget-specific CSS
            $table->longText('custom_js')->nullable(); // Widget-specific JavaScript

            // Widget wrapper settings
            $table->boolean('show_title')->default(true);
            $table->string('title_tag')->default('h3'); // HTML tag for title
            $table->string('wrapper_tag')->default('div'); // HTML wrapper tag
            $table->boolean('show_border')->default(false);
            $table->boolean('show_shadow')->default(false);

            // Caching settings
            $table->boolean('cache_enabled')->default(true);
            $table->integer('cache_duration')->default(3600); // Cache TTL in seconds
            $table->json('cache_tags')->nullable(); // Cache invalidation tags
            $table->boolean('user_specific_cache')->default(false); // Cache per user

            // Performance settings
            $table->boolean('lazy_load')->default(false);
            $table->boolean('async_load')->default(false);
            $table->integer('load_priority')->default(10); // Loading priority (1-100)

            // Widget data and statistics
            $table->integer('view_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->json('interaction_stats')->nullable(); // Detailed interaction statistics
            $table->datetime('last_updated_at')->nullable(); // Content last update

            // Widget relationships
            $table->unsignedBigInteger('parent_widget_id')->nullable(); // For nested widgets
            $table->json('child_widgets')->nullable(); // Child widget IDs
            $table->json('related_widgets')->nullable(); // Related widget IDs

            // Widget variations and A/B testing
            $table->boolean('ab_testing_enabled')->default(false);
            $table->json('ab_variants')->nullable(); // A/B test variants
            $table->decimal('ab_traffic_split', 3, 2)->default(0.5); // Traffic split ratio

            // Content management
            $table->unsignedBigInteger('author_id')->nullable(); // User who created widget
            $table->unsignedBigInteger('last_editor_id')->nullable(); // Last user who edited
            $table->json('edit_history')->nullable(); // Edit history log

            // Widget permissions
            $table->json('edit_permissions')->nullable(); // Who can edit this widget
            $table->json('view_permissions')->nullable(); // Who can view this widget
            $table->boolean('is_locked')->default(false); // Prevent editing

            // Integration settings
            $table->json('api_endpoints')->nullable(); // External API endpoints
            $table->json('webhook_urls')->nullable(); // Webhook URLs for updates
            $table->json('third_party_config')->nullable(); // Third-party service config

            // Widget lifecycle
            $table->enum('status', ['active', 'inactive', 'draft', 'archived'])->default('active');
            $table->boolean('is_system_widget')->default(false); // System vs user widget
            $table->string('version', 10)->default('1.0');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('last_editor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_widget_id')->references('id')->on('widget_settings')->onDelete('cascade');

            // Indexes
            $table->index(['widget_type', 'status']);
            $table->index(['position', 'order_position']);
            $table->index(['status', 'order']);
            $table->index('slug');
            $table->index('custom_position');
            $table->index(['show_from', 'show_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_settings');
    }
};
