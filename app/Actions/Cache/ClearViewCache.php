<?php

namespace App\Actions\Cache;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Providers\ViewServiceProvider;

class ClearViewCache
{
    use AsAction;

    /**
     * Clear cache cho model cụ thể
     */
    public static function forModel($model): void
    {
        $modelClass = is_string($model) ? $model : get_class($model);
        (new self())->handle($modelClass);
    }

    /**
     * Clear cache cho model cụ thể
     */
    public function handle(string $modelClass): void
    {
        switch ($modelClass) {
            case 'App\Models\Setting':
            case 'App\Generated\Models\SystemConfiguration':
            case 'App\Generated\Models\WebsiteSettings':
                ViewServiceProvider::refreshCache('settings');
                break;

            case 'App\Models\MenuItem':
            case 'App\Generated\Models\MenuItem':
                ViewServiceProvider::refreshCache('navigation');
                break;

            case 'App\Models\Post':
            case 'App\Generated\Models\Post':
            case 'App\Generated\Models\PostCategory':
            case 'App\Models\Partner':
            case 'App\Models\Slider':
            case 'App\Generated\Models\Staff':
            case 'App\Generated\Models\Testimonial':
            case 'App\Generated\Models\Faq':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                break;

            default:
                ViewServiceProvider::refreshCache('all');
                break;
        }
    }

    /**
     * Clear tất cả cache
     */
    public static function all(): void
    {
        ViewServiceProvider::refreshCache('all');
    }
}
