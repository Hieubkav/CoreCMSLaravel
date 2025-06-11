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
        Schema::create('admin_configurations', function (Blueprint $table) {
            $table->id();

            // Dashboard Settings
            $table->string('dashboard_title')->default('Admin Dashboard');
            $table->string('dashboard_theme')->default('light'); // light, dark
            $table->string('sidebar_style')->default('expanded'); // expanded, collapsed, overlay
            $table->boolean('enable_dark_mode_toggle')->default(true);

            // Navigation & Menu
            $table->boolean('show_dashboard_widgets')->default(true);
            $table->boolean('show_quick_actions')->default(true);
            $table->boolean('enable_breadcrumbs')->default(true);
            $table->string('menu_style')->default('sidebar'); // sidebar, topbar, hybrid

            // Data Management
            $table->integer('records_per_page')->default(25); // 10, 25, 50, 100
            $table->boolean('enable_bulk_actions')->default(true);
            $table->boolean('enable_export_functions')->default(true);
            $table->boolean('enable_import_functions')->default(false);
            $table->boolean('enable_advanced_filters')->default(true);

            // User Management
            $table->boolean('enable_user_registration')->default(false);
            $table->boolean('require_email_verification')->default(true);
            $table->boolean('enable_two_factor_auth')->default(false);
            $table->string('default_user_role')->default('user');

            // Content Management
            $table->boolean('enable_rich_text_editor')->default(true);
            $table->boolean('enable_media_library')->default(true);
            $table->boolean('enable_file_manager')->default(true);
            $table->string('default_image_quality')->default('80'); // 60, 80, 90, 100
            $table->string('max_upload_size')->default('10MB'); // 5MB, 10MB, 20MB, 50MB

            // SEO & Analytics
            $table->boolean('enable_seo_tools')->default(true);
            $table->boolean('enable_analytics_dashboard')->default(true);
            $table->boolean('enable_visitor_tracking')->default(true);
            $table->boolean('visitor_analytics_enabled')->default(false);
            $table->boolean('seo_auto_generate')->default(true);
            $table->string('default_description')->default('Powered by Core Laravel Framework');

            // Security Settings
            $table->boolean('enable_activity_log')->default(true);
            $table->boolean('enable_login_attempts_limit')->default(true);
            $table->integer('max_login_attempts')->default(5);
            $table->boolean('enable_ip_whitelist')->default(false);
            $table->json('allowed_ips')->nullable();

            // Backup & Maintenance
            $table->boolean('enable_auto_backup')->default(false);
            $table->string('backup_frequency')->default('weekly'); // daily, weekly, monthly
            $table->boolean('enable_maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();

            // Notifications
            $table->boolean('enable_email_notifications')->default(true);
            $table->boolean('enable_browser_notifications')->default(false);
            $table->boolean('enable_slack_notifications')->default(false);
            $table->string('notification_email')->nullable();

            // Performance
            $table->boolean('enable_query_optimization')->default(true);
            $table->boolean('enable_cache_management')->default(true);
            $table->boolean('enable_database_optimization')->default(false);
            $table->string('cache_driver')->default('file'); // file, redis, memcached
            $table->boolean('query_cache')->default(true);
            $table->boolean('eager_loading')->default(true);
            $table->boolean('asset_optimization')->default(false);
            $table->integer('cache_duration')->default(3600);
            $table->integer('pagination_size')->default(25);

            // Customization
            $table->string('admin_logo')->nullable();
            $table->string('admin_favicon')->nullable();
            $table->string('admin_primary_color')->default('#dc2626');
            $table->string('admin_secondary_color')->default('#64748b');
            $table->text('custom_admin_css')->nullable();
            $table->text('custom_admin_js')->nullable();

            // Image Processing
            $table->integer('webp_quality')->default(80);
            $table->integer('max_width')->default(1920);
            $table->integer('max_height')->default(1080);

            // Metadata
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('active'); // active, inactive
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('admin_configurations');
    }
};