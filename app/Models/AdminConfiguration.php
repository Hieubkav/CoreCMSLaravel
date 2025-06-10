<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        // Admin Panel Colors
        'admin_primary_color',
        'admin_secondary_color',

        // Analytics & Tracking
        'visitor_analytics_enabled',

        // Performance Settings
        'query_cache',
        'eager_loading',
        'asset_optimization',
        'cache_duration',
        'pagination_size',

        // Image Processing
        'webp_quality',
        'max_width',
        'max_height',

        // SEO Configuration
        'seo_auto_generate',
        'default_description',

        // Meta fields
        'status',
        'order'
    ];

    protected $casts = [
        'visitor_analytics_enabled' => 'boolean',
        'query_cache' => 'boolean',
        'eager_loading' => 'boolean',
        'asset_optimization' => 'boolean',
        'seo_auto_generate' => 'boolean',
        'cache_duration' => 'integer',
        'pagination_size' => 'integer',
        'webp_quality' => 'integer',
        'max_width' => 'integer',
        'max_height' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Get current admin configuration
     */
    public static function current()
    {
        return static::where('status', 'active')->first() ?? new static();
    }

    /**
     * Create or update admin configuration
     */
    public static function updateOrCreateConfig(array $data)
    {
        // Deactivate all existing configs
        static::query()->update(['status' => 'inactive']);

        // Create new active config
        return static::create(array_merge($data, [
            'status' => 'active',
            'order' => 0
        ]));
    }
}
