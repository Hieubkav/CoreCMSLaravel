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
        // Register Post Observer if Post model exists
        if (class_exists(\App\Models\Post::class) && class_exists(\App\Observers\PostObserver::class)) {
            \App\Models\Post::observe(\App\Observers\PostObserver::class);
        }

        // Register Service Observer if Service model exists
        if (class_exists(\App\Models\Service::class) && class_exists(\App\Observers\ServiceObserver::class)) {
            \App\Models\Service::observe(\App\Observers\ServiceObserver::class);
        }

        // Register Staff Observer if Staff model exists
        if (class_exists(\App\Models\Staff::class) && class_exists(\App\Observers\StaffObserver::class)) {
            \App\Models\Staff::observe(\App\Observers\StaffObserver::class);
        }

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
