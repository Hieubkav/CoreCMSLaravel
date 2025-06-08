<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Providers\ViewServiceProvider;

/**
 * Clear View Cache Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Clear cache dựa trên model type
 * Thay thế ClearsViewCache trait phức tạp
 */
class ClearViewCache
{
    use AsAction;

    /**
     * Clear cache cho model cụ thể
     */
    public function handle(string $modelClass): void
    {
        switch ($modelClass) {
            case 'App\Models\Setting':
                ViewServiceProvider::refreshCache('settings');
                break;

            case 'App\Models\MenuItem':
            case 'App\Models\Association':
                ViewServiceProvider::refreshCache('navigation');
                break;

            case 'App\Models\Post':
            case 'App\Models\Partner':
            case 'App\Models\Slider':
            case 'App\Models\Course':
            case 'App\Models\CatCourse':
            case 'App\Models\CourseGroup':
            case 'App\Models\Instructor':
            case 'App\Models\Student':
            case 'App\Models\Testimonial':
            case 'App\Models\Faq':
                ViewServiceProvider::refreshCache('storefront');
                ViewServiceProvider::refreshCache('navigation');
                break;

            default:
                ViewServiceProvider::refreshCache('all');
                break;
        }
    }

    /**
     * Clear cache cho model instance
     */
    public static function forModel($model): void
    {
        static::run(get_class($model));
    }

    /**
     * Clear tất cả cache
     */
    public static function all(): void
    {
        ViewServiceProvider::refreshCache('all');
    }
}
