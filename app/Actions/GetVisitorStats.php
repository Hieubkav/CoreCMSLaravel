<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

/**
 * Get Visitor Stats Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Lấy thống kê visitor đơn giản
 * Thay thế VisitorStatsService phức tạp
 */
class GetVisitorStats
{
    use AsAction;

    private const CACHE_TTL = 300; // 5 phút

    /**
     * Lấy tất cả thống kê realtime
     */
    public function handle(): array
    {
        return Cache::remember('visitor_realtime_stats', self::CACHE_TTL, function () {
            return [
                'unique_visitors_today' => $this->getUniqueVisitorsToday(),
                'total_page_views_today' => $this->getTotalPageViewsToday(),
                'unique_visitors_total' => $this->getUniqueVisitorsTotal(),
                'total_page_views_total' => $this->getTotalPageViewsTotal(),
                'top_courses' => $this->getTopCourses(),
            ];
        });
    }

    /**
     * Lấy unique visitors hôm nay
     */
    private function getUniqueVisitorsToday(): int
    {
        try {
            return DB::table('visitor_logs')
                ->whereDate('created_at', today())
                ->distinct('ip_address')
                ->count('ip_address');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng page views hôm nay
     */
    private function getTotalPageViewsToday(): int
    {
        try {
            return DB::table('visitor_logs')
                ->whereDate('created_at', today())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng unique visitors
     */
    private function getUniqueVisitorsTotal(): int
    {
        try {
            return DB::table('visitor_logs')
                ->distinct('ip_address')
                ->count('ip_address');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy tổng page views
     */
    private function getTotalPageViewsTotal(): int
    {
        try {
            return DB::table('visitor_logs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Lấy top 3 khóa học được xem nhiều nhất
     */
    private function getTopCourses(): array
    {
        try {
            return Course::where('status', 'active')
                ->orderBy('view_count', 'desc')
                ->limit(3)
                ->select(['id', 'title', 'slug', 'view_count', 'thumbnail_link'])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Lấy chỉ unique visitors hôm nay
     */
    public static function todayVisitors(): int
    {
        return (new static)->getUniqueVisitorsToday();
    }

    /**
     * Lấy chỉ page views hôm nay
     */
    public static function todayPageViews(): int
    {
        return (new static)->getTotalPageViewsToday();
    }

    /**
     * Lấy chỉ top courses
     */
    public static function topCourses(): array
    {
        return (new static)->getTopCourses();
    }

    /**
     * Clear cache thống kê
     */
    public static function clearCache(): void
    {
        Cache::forget('visitor_realtime_stats');
    }
}
