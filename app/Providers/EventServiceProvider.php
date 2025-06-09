<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Slider;
use App\Models\Setting;
use App\Models\SystemConfiguration;
use App\Models\MenuItem;
use App\Models\Product;
use App\Observers\PostObserver;
use App\Observers\SliderObserver;
use App\Observers\SettingObserver;
use App\Observers\SystemConfigurationObserver;
use App\Observers\MenuItemObserver;
use App\Observers\ProductObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register observers for existing models only
        Post::observe(PostObserver::class);
        Slider::observe(SliderObserver::class);
        Setting::observe(SettingObserver::class);

        // Register observers for new modules (only if tables exist)
        if (Schema::hasTable('system_configurations')) {
            SystemConfiguration::observe(SystemConfigurationObserver::class);
        }

        if (Schema::hasTable('menu_items')) {
            MenuItem::observe(MenuItemObserver::class);
        }

        if (Schema::hasTable('products')) {
            Product::observe(ProductObserver::class);
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
