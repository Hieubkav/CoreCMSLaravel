<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Models\Course;
use App\Models\CatPost;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Partner;
use App\Models\MenuItem;
use App\Models\Student;

class ViewDataHelper
{
    /**
     * Get cached settings data
     */
    public static function getSettings()
    {
        return Cache::remember('global_settings', 3600, function () {
            return Setting::where('status', 'active')->get()->keyBy('key');
        });
    }

    /**
     * Get cached storefront data - Cập nhật cho website khóa học Core
     */
    public static function getStorefrontData()
    {
        return Cache::remember('storefront_data', 1800, function () {
            return [
                'sliders' => Slider::where('status', 'active')
                    ->orderBy('order')
                    ->get(),

                'courseCategories' => CatPost::where('status', 'active')
                    ->orderBy('order')
                    ->take(12)
                    ->get(),

                'featuredCourses' => Course::where('status', 'active')
                    ->where('is_featured', true)
                    ->with(['courseCategory', 'images'])
                    ->orderBy('order')
                    ->take(8)
                    ->get(),

                'latestPosts' => Post::where('status', 'active')
                    ->with(['category', 'images'])
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get(),

                'partners' => Partner::where('status', 'active')
                    ->orderBy('order')
                    ->get(),

                'popularCourses' => Course::where('status', 'active')
                    ->where('is_featured', true)
                    ->with(['courseCategory', 'images'])
                    ->orderBy('order')
                    ->take(8)
                    ->get(),

                'newCourses' => Course::where('status', 'active')
                    ->with(['courseCategory', 'images'])
                    ->orderBy('created_at', 'desc')
                    ->take(8)
                    ->get(),

                'totalStudents' => Student::where('status', 'active')->count(),
                'totalCourses' => Course::where('status', 'active')->count(),
            ];
        });
    }

    /**
     * Get cached navigation data - Cập nhật cho website khóa học Core
     */
    public static function getNavigationData()
    {
        return Cache::remember('navigation_data', 7200, function () {
            return [
                'mainCategories' => CatPost::where('status', 'active')
                    ->orderBy('order')
                    ->get(),

                'footerCategories' => CatPost::where('status', 'active')
                    ->orderBy('order')
                    ->take(6)
                    ->get(),

                'recentPosts' => Post::where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                'recentCourses' => Course::where('status', 'active')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get(),

                'menuItems' => MenuItem::where('status', 'active')
                    ->whereNull('parent_id')
                    ->with(['children' => function ($query) {
                        $query->where('status', 'active')->orderBy('order');
                    }])
                    ->orderBy('order')
                    ->get(),
            ];
        });
    }

    /**
     * Get specific data by key
     */
    public static function get($key, $default = null)
    {
        $storefrontData = self::getStorefrontData();
        $navigationData = self::getNavigationData();
        $settings = self::getSettings();

        $allData = array_merge($storefrontData, $navigationData, ['settings' => $settings]);

        return $allData[$key] ?? $default;
    }
}
