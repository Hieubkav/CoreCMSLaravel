<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\CatPost;
use App\Models\Post;
use App\Models\Slider;
use App\Models\ThemeSetting;
use App\Models\WidgetSetting;
use App\Models\WebDesign;
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

        // Share data for layout views
        View::composer([
            'layouts.app',
            'components.public.*'
        ], function ($view) {
            $this->shareLayoutData($view);
        });
    }

    /**
     * Share global data with all views
     */
    private function shareGlobalData($view)
    {
        // Cache settings for 1 hour
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::where('status', 'active')->first();
        });

        // Cache active theme for 1 hour
        $activeTheme = Cache::remember('active_theme_data', 3600, function () {
            return ThemeSetting::getActiveTheme();
        });

        // Cache web design settings for 1 hour
        $webDesign = Cache::remember('web_design_settings', 3600, function () {
            return WebDesign::getInstance();
        });

        $view->with([
            'globalSettings' => $settings,
            'settings' => $settings, // Keep for backward compatibility
            'activeTheme' => $activeTheme,
            'webDesign' => $webDesign,
        ]);
    }

    /**
     * Share data for layout views
     */
    private function shareLayoutData($view)
    {
        // Cache navigation data for 2 hours
        $navigationData = Cache::remember('navigation_data', 7200, function () {
            return [
                // Post Categories for navigation
                'postCategories' => CatPost::where('status', 'active')
                    ->whereHas('posts', function($query) {
                        $query->where('status', 'active');
                    })
                    ->withCount(['posts' => function($query) {
                        $query->where('status', 'active');
                    }])
                    ->orderBy('order')
                    ->take(6)
                    ->get(),

                // Recent Posts for footer
                'recentPosts' => Post::where('status', 'active')
                    ->select(['id', 'title', 'slug', 'created_at', 'thumbnail'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                // Latest Posts for homepage
                'latestPosts' => Post::where('status', 'active')
                    ->with(['category:id,name,slug'])
                    ->select(['id', 'title', 'slug', 'content', 'thumbnail', 'category_id', 'order', 'created_at'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get(),

                // Sliders for hero banner
                'sliders' => Slider::where('status', 'active')
                    ->orderBy('order')
                    ->select(['id', 'title', 'description', 'image_link', 'link', 'alt_text', 'order'])
                    ->get(),

                // Quick Stats
                'quickStats' => [
                    'total_posts' => Post::where('status', 'active')->count(),
                    'total_categories' => CatPost::where('status', 'active')->count(),
                ],

                // Widgets for different positions
                'sidebarWidgets' => WidgetSetting::getForPosition('sidebar_right'),
                'headerWidgets' => WidgetSetting::getForPosition('header'),
                'footerWidgets' => WidgetSetting::getForPosition('footer'),
            ];
        });

        $view->with($navigationData);
    }

    /**
     * Clear cache when needed
     */
    public static function clearCache()
    {
        Cache::forget('global_settings');
        Cache::forget('navigation_data');
        Cache::forget('active_theme_data');
        Cache::forget('web_design_settings');
        Cache::forget('widgets_position_sidebar_right');
        Cache::forget('widgets_position_header');
        Cache::forget('widgets_position_footer');
    }

    /**
     * Refresh specific cache
     */
    public static function refreshCache($type = 'all')
    {
        switch ($type) {
            case 'settings':
                Cache::forget('global_settings');
                break;
            case 'navigation':
                Cache::forget('navigation_data');
                break;
            case 'posts':
                Cache::forget('navigation_data'); // Posts are part of navigation data
                break;
            case 'sliders':
                Cache::forget('navigation_data'); // Sliders are part of navigation data
                break;
            case 'theme':
                Cache::forget('active_theme_data');
                break;
            case 'webdesign':
                Cache::forget('web_design_settings');
                break;
            case 'widgets':
                Cache::forget('widgets_position_sidebar_right');
                Cache::forget('widgets_position_header');
                Cache::forget('widgets_position_footer');
                break;
            case 'all':
            default:
                self::clearCache();
                break;
        }
    }
}