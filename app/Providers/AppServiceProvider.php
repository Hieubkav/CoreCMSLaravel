<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
// use App\Models\Post;
// use App\Observers\PostObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper functions
        if (!function_exists('simpleLazyImage')) {
            require_once app_path('Helpers/PerformanceHelper.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observers for existing models only
        // Post::observe(PostObserver::class);

        // Register Generated Model Observers
        // \App\Generated\Models\WebDesign::observe(\App\Generated\Observers\WebDesignObserver::class);

        // Cache Observer for ViewServiceProvider
        // \App\Models\Post::observe(\App\Observers\CacheObserver::class);
        // \App\Models\CatPost::observe(\App\Observers\CacheObserver::class);
        // \App\Models\Slider::observe(\App\Observers\CacheObserver::class);
        // \App\Models\Setting::observe(\App\Observers\CacheObserver::class);

        // Cache Observer for Generated Models
        // \App\Generated\Models\WebDesign::observe(\App\Observers\CacheObserver::class);

        // Register Blade directive for lazy loading
        Blade::directive('simpleLazyImage', function ($expression) {
            return "<?php echo simpleLazyImage({$expression}); ?>";
        });
    }
}
