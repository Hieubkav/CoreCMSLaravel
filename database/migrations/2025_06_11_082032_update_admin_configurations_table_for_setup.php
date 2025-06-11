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
        Schema::table('admin_configurations', function (Blueprint $table) {
            // Thêm các cột mới cần thiết cho setup form
            if (!Schema::hasColumn('admin_configurations', 'admin_primary_color')) {
                $table->string('admin_primary_color')->default('#1f2937')->after('id');
            }
            if (!Schema::hasColumn('admin_configurations', 'admin_secondary_color')) {
                $table->string('admin_secondary_color')->default('#374151')->after('admin_primary_color');
            }
            if (!Schema::hasColumn('admin_configurations', 'visitor_analytics_enabled')) {
                $table->boolean('visitor_analytics_enabled')->default(false)->after('admin_secondary_color');
            }
            if (!Schema::hasColumn('admin_configurations', 'query_cache')) {
                $table->boolean('query_cache')->default(true)->after('visitor_analytics_enabled');
            }
            if (!Schema::hasColumn('admin_configurations', 'eager_loading')) {
                $table->boolean('eager_loading')->default(true)->after('query_cache');
            }
            if (!Schema::hasColumn('admin_configurations', 'asset_optimization')) {
                $table->boolean('asset_optimization')->default(true)->after('eager_loading');
            }
            if (!Schema::hasColumn('admin_configurations', 'cache_duration')) {
                $table->integer('cache_duration')->default(300)->after('asset_optimization');
            }
            if (!Schema::hasColumn('admin_configurations', 'pagination_size')) {
                $table->integer('pagination_size')->default(25)->after('cache_duration');
            }
            if (!Schema::hasColumn('admin_configurations', 'webp_quality')) {
                $table->integer('webp_quality')->default(95)->after('pagination_size');
            }
            if (!Schema::hasColumn('admin_configurations', 'max_width')) {
                $table->integer('max_width')->default(1920)->after('webp_quality');
            }
            if (!Schema::hasColumn('admin_configurations', 'max_height')) {
                $table->integer('max_height')->default(1080)->after('max_width');
            }
            if (!Schema::hasColumn('admin_configurations', 'status')) {
                $table->string('status')->default('active')->after('updated_by');
            }
            if (!Schema::hasColumn('admin_configurations', 'order')) {
                $table->integer('order')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'admin_primary_color',
                'admin_secondary_color',
                'visitor_analytics_enabled',
                'query_cache',
                'eager_loading',
                'asset_optimization',
                'cache_duration',
                'pagination_size',
                'webp_quality',
                'max_width',
                'max_height',
                'status',
                'order'
            ]);
        });
    }
};
