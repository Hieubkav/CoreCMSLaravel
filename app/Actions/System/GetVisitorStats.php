<?php

namespace App\Actions\System;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GetVisitorStats
{
    use AsAction;

    /**
     * Lấy thống kê visitor theo type
     */
    public function handle(string $type): int|array
    {
        return match ($type) {
            'today_unique' => $this->getTodayUniqueVisitors(),
            'today_total' => $this->getTodayTotalVisits(),
            'total_unique' => $this->getTotalUniqueVisitors(),
            'total_visits' => $this->getTotalVisits(),
            'popular_posts' => $this->getPopularPosts(),
            'popular_courses' => $this->getPopularCourses(),
            default => 0
        };
    }

    /**
     * Lấy số unique visitors hôm nay
     */
    private function getTodayUniqueVisitors(): int
    {
        return Cache::remember('visitor_stats_today_unique', 300, function () {
            try {
                return DB::table('visitor_logs')
                    ->whereDate('created_at', today())
                    ->distinct('ip_address')
                    ->count('ip_address');
            } catch (\Exception) {
                return 0;
            }
        });
    }

    /**
     * Lấy tổng số visits hôm nay
     */
    private function getTodayTotalVisits(): int
    {
        return Cache::remember('visitor_stats_today_total', 300, function () {
            try {
                return DB::table('visitor_logs')
                    ->whereDate('created_at', today())
                    ->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    /**
     * Lấy tổng số unique visitors
     */
    private function getTotalUniqueVisitors(): int
    {
        return Cache::remember('visitor_stats_total_unique', 3600, function () {
            try {
                return DB::table('visitor_logs')
                    ->distinct('ip_address')
                    ->count('ip_address');
            } catch (\Exception) {
                return 0;
            }
        });
    }

    /**
     * Lấy tổng số visits
     */
    private function getTotalVisits(): int
    {
        return Cache::remember('visitor_stats_total_visits', 3600, function () {
            try {
                return DB::table('visitor_logs')->count();
            } catch (\Exception) {
                return 0;
            }
        });
    }

    /**
     * Lấy bài viết phổ biến
     */
    private function getPopularPosts(): array
    {
        return Cache::remember('visitor_stats_popular_posts', 3600, function () {
            try {
                return DB::table('posts')
                    ->where('status', 'published')
                    ->orderBy('view_count', 'desc')
                    ->limit(5)
                    ->select(['id', 'title', 'slug', 'view_count', 'thumbnail_link'])
                    ->get()
                    ->toArray();
            } catch (\Exception) {
                return [];
            }
        });
    }

    /**
     * Lấy khóa học phổ biến
     */
    private function getPopularCourses(): array
    {
        return Cache::remember('visitor_stats_popular_courses', 3600, function () {
            try {
                return DB::table('courses')
                    ->where('status', 'published')
                    ->orderBy('view_count', 'desc')
                    ->limit(5)
                    ->select(['id', 'title', 'slug', 'view_count', 'thumbnail_link'])
                    ->get()
                    ->toArray();
            } catch (\Exception) {
                return [];
            }
        });
    }

    /**
     * Lấy thống kê tổng quan
     */
    public static function overview(): array
    {
        return [
            'today_unique' => static::run('today_unique'),
            'today_total' => static::run('today_total'),
            'total_unique' => static::run('total_unique'),
            'total_visits' => static::run('total_visits'),
        ];
    }

    /**
     * Lấy thống kê chi tiết
     */
    public static function detailed(): array
    {
        return [
            'overview' => static::overview(),
            'popular_posts' => static::run('popular_posts'),
            'popular_courses' => static::run('popular_courses'),
        ];
    }

    /**
     * Clear cache thống kê
     */
    public static function clearCache(): void
    {
        $cacheKeys = [
            'visitor_stats_today_unique',
            'visitor_stats_today_total',
            'visitor_stats_total_unique',
            'visitor_stats_total_visits',
            'visitor_stats_popular_posts',
            'visitor_stats_popular_courses',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Refresh cache thống kê
     */
    public static function refreshCache(): array
    {
        static::clearCache();
        return static::detailed();
    }
}
