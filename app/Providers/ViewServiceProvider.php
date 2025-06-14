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

        // Share Service data if model exists
        $services = null;
        if (class_exists('App\Models\Service')) {
            $services = Cache::remember('homepage_services', 1800, function () {
                try {
                    $query = \App\Models\Service::where('status', 'active');

                    // Check if is_featured column exists
                    if (\Illuminate\Support\Facades\Schema::hasColumn('services', 'is_featured')) {
                        $query->where('is_featured', true);
                    }

                    return $query->orderBy('order')->limit(6)->get();
                } catch (\Exception $e) {
                    return collect(); // Return empty collection if table doesn't exist
                }
            });
        }

        // Share Staff data if model exists
        $staff = null;
        if (class_exists('App\Models\Staff')) {
            $staff = Cache::remember('homepage_staff', 1800, function () {
                try {
                    $query = \App\Models\Staff::where('status', 'active');

                    // Check if is_featured column exists
                    if (\Illuminate\Support\Facades\Schema::hasColumn('staff', 'is_featured')) {
                        $query->where('is_featured', true);
                    }

                    return $query->orderBy('order')->limit(4)->get();
                } catch (\Exception $e) {
                    return collect(); // Return empty collection if table doesn't exist
                }
            });
        }

        // Share Blog data if model exists
        $posts = null;
        $postCategories = null;
        if (class_exists('App\Models\Post')) {
            $posts = Cache::remember('homepage_posts', 1800, function () {
                try {
                    $query = \App\Models\Post::where('status', 'published');

                    // Check if is_featured column exists
                    if (\Illuminate\Support\Facades\Schema::hasColumn('posts', 'is_featured')) {
                        $query->where('is_featured', true);
                    }

                    return $query->with('category')
                        ->orderBy('published_at', 'desc')
                        ->limit(6)
                        ->get();
                } catch (\Exception $e) {
                    return collect(); // Return empty collection if table doesn't exist
                }
            });

            $postCategories = Cache::remember('post_categories', 1800, function () {
                try {
                    return \App\Models\PostCategory::where('status', 'active')
                        ->orderBy('order')
                        ->get();
                } catch (\Exception $e) {
                    return collect(); // Return empty collection if table doesn't exist
                }
            });
        }

        $view->with([
            'globalSettings' => $settings,
            'settings' => $settings, // Keep for backward compatibility
            'services' => $services,
            'staff' => $staff,
            'posts' => $posts,
            'postCategories' => $postCategories,
        ]);
    }

    /**
     * Clear cache cho model cụ thể
     */
    public static function refreshCache(string $cacheKey = 'all'): void
    {
        if ($cacheKey === 'all') {
            Cache::forget('global_settings');
            Cache::forget('homepage_services');
            Cache::forget('homepage_staff');
            Cache::forget('homepage_posts');
            Cache::forget('post_categories');
        } else {
            Cache::forget($cacheKey);
        }
    }

    /**
     * Clear tất cả cache
     */
    public static function clearCache(): void
    {
        Cache::forget('global_settings');
        Cache::forget('homepage_services');
        Cache::forget('homepage_staff');
        Cache::forget('homepage_posts');
        Cache::forget('post_categories');
    }
}
