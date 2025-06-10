<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share global data with all views
        View::composer('*', function ($view) {
            $this->shareGlobalData($view);
        });
    }

    /**
     * Share global data với tất cả views
     */
    private function shareGlobalData($view): void
    {
        // Cache settings for 1 hour (with safe fallback)
        $settings = Cache::remember('global_settings', 3600, function () {
            try {
                return Setting::first();
            } catch (\Exception $e) {
                // Return null if table doesn't exist (during setup)
                return null;
            }
        });

        $view->with([
            'globalSettings' => $settings,
            'settings' => $settings, // Keep for backward compatibility
        ]);
    }

    /**
     * Clear cache cho model cụ thể
     */
    public static function refreshCache(string $cacheKey = 'all'): void
    {
        if ($cacheKey === 'all') {
            Cache::forget('global_settings');
        } else {
            Cache::forget('global_settings');
        }
    }

    /**
     * Clear tất cả cache
     */
    public static function clearCache(): void
    {
        Cache::forget('global_settings');
    }
}
