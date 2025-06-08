<?php

namespace App\Observers;

use App\Providers\ViewServiceProvider;

/**
 * Observer để clear cache ViewServiceProvider khi dữ liệu thay đổi
 * Theo nguyên tắc KISS - đơn giản và hiệu quả
 */
class CacheObserver
{
    /**
     * Clear cache khi tạo mới
     */
    public function created($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache khi cập nhật
     */
    public function updated($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache khi xóa
     */
    public function deleted($model)
    {
        $this->clearRelevantCache($model);
    }

    /**
     * Clear cache dựa trên model type
     */
    private function clearRelevantCache($model)
    {
        $modelClass = get_class($model);
        
        switch ($modelClass) {
            case 'App\Models\Course':
                ViewServiceProvider::refreshCache('courses');
                break;
                
            case 'App\Models\CatCourse':
                ViewServiceProvider::refreshCache('courses');
                break;
                
            case 'App\Models\Post':
                ViewServiceProvider::refreshCache('posts');
                break;
                
            case 'App\Models\CatPost':
                ViewServiceProvider::refreshCache('posts');
                break;
                
            case 'App\Models\Slider':
                ViewServiceProvider::refreshCache('sliders');
                break;
                
            case 'App\Models\Setting':
                ViewServiceProvider::refreshCache('settings');
                break;
                
            case 'App\Models\WebDesign':
                ViewServiceProvider::refreshCache('webdesign');
                break;
                
            case 'App\Models\Testimonial':
                ViewServiceProvider::refreshCache('testimonials');
                break;
                
            case 'App\Models\MenuItem':
                ViewServiceProvider::refreshCache('navigation');
                break;
                
            case 'App\Models\CourseGroup':
            case 'App\Models\Album':
            case 'App\Models\Partner':
            case 'App\Models\Association':
            case 'App\Models\Student':
            case 'App\Models\Instructor':
            case 'App\Models\Faq':
                ViewServiceProvider::refreshCache('storefront');
                break;
                
            default:
                // Clear all cache cho các model khác
                ViewServiceProvider::clearCache();
                break;
        }
    }
}
