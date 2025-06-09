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
        Schema::create('page_builders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Page identification
            $table->enum('page_type', [
                'homepage', 'about', 'contact', 'services', 'products',
                'blog', 'portfolio', 'landing', 'custom'
            ])->default('custom');
            $table->string('template_name')->nullable(); // Base template to extend
            $table->string('route_name')->nullable(); // Laravel route name
            $table->string('url_path')->nullable(); // Custom URL path

            // Page metadata
            $table->string('page_title');
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();

            // Page structure - JSON array of blocks/sections
            $table->json('page_blocks'); // Main content blocks
            $table->json('sidebar_blocks')->nullable(); // Sidebar content blocks
            $table->json('header_blocks')->nullable(); // Custom header blocks
            $table->json('footer_blocks')->nullable(); // Custom footer blocks

            // Layout settings
            $table->string('layout_template')->default('layouts.shop'); // Blade layout
            $table->boolean('use_sidebar')->default(false);
            $table->string('sidebar_position')->default('right'); // left, right
            $table->integer('content_width')->default(12); // Bootstrap columns (1-12)
            $table->integer('sidebar_width')->default(3); // Bootstrap columns

            // Page styling
            $table->string('page_class')->nullable(); // Custom CSS classes for body
            $table->json('custom_css_vars')->nullable(); // CSS custom properties
            $table->longText('custom_css')->nullable(); // Page-specific CSS
            $table->longText('custom_js')->nullable(); // Page-specific JavaScript

            // Block types available for this page
            $table->json('allowed_block_types')->nullable(); // Restrict available blocks
            $table->json('block_settings')->nullable(); // Default settings for blocks

            // Responsive settings
            $table->json('mobile_blocks')->nullable(); // Mobile-specific block order
            $table->json('tablet_blocks')->nullable(); // Tablet-specific block order
            $table->boolean('mobile_optimized')->default(true);

            // Content management
            $table->enum('content_type', ['static', 'dynamic', 'mixed'])->default('static');
            $table->json('dynamic_sources')->nullable(); // Data sources for dynamic content
            $table->json('content_filters')->nullable(); // Filters for dynamic content

            // Caching and performance
            $table->boolean('cache_enabled')->default(true);
            $table->integer('cache_duration')->default(3600); // Cache TTL in seconds
            $table->json('cache_tags')->nullable(); // Cache invalidation tags

            // Access control
            $table->enum('visibility', ['public', 'private', 'password', 'members'])->default('public');
            $table->string('password')->nullable(); // For password-protected pages
            $table->json('allowed_roles')->nullable(); // User roles that can access
            $table->json('allowed_users')->nullable(); // Specific users that can access

            // Publishing settings
            $table->boolean('is_published')->default(false);
            $table->datetime('published_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->unsignedBigInteger('author_id')->nullable(); // User who created the page
            $table->unsignedBigInteger('last_editor_id')->nullable(); // Last user who edited

            // Versioning
            $table->string('version', 10)->default('1.0');
            $table->unsignedBigInteger('parent_page_id')->nullable(); // For page variations/translations
            $table->string('language', 5)->default('vi'); // Language code

            // Analytics and tracking
            $table->boolean('track_analytics')->default(true);
            $table->json('tracking_codes')->nullable(); // Google Analytics, Facebook Pixel, etc.
            $table->integer('view_count')->default(0);
            $table->json('conversion_goals')->nullable(); // Conversion tracking

            // A/B Testing
            $table->boolean('ab_testing_enabled')->default(false);
            $table->json('ab_variants')->nullable(); // A/B test variants
            $table->decimal('ab_traffic_split', 3, 2)->default(0.5); // Traffic split ratio

            // Comments and feedback
            $table->boolean('comments_enabled')->default(false);
            $table->enum('comment_moderation', ['none', 'manual', 'auto'])->default('manual');
            $table->boolean('feedback_enabled')->default(false);

            $table->enum('status', ['draft', 'published', 'archived', 'scheduled'])->default('draft');
            $table->integer('order')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('last_editor_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_page_id')->references('id')->on('page_builders')->onDelete('cascade');

            // Indexes
            $table->index(['page_type', 'status']);
            $table->index(['is_published', 'published_at']);
            $table->index(['status', 'order']);
            $table->index('slug');
            $table->index('route_name');
            $table->index('url_path');
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_builders');
    }
};
