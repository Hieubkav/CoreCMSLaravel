<?php

namespace App\Actions\System;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ResetVisitorStats
{
    use AsAction;

    /**
     * Reset tất cả visitor statistics - KISS: Truncate table
     */
    public function handle(): array
    {
        try {
            // 1. Đếm records trước khi xóa
            $deletedCount = DB::table('visitors')->count();

            // 2. Clear tất cả cache liên quan
            $this->clearAllCache();

            // 3. TRUNCATE table - đơn giản và nhanh
            DB::statement('TRUNCATE TABLE visitors');

            Log::info('Visitor stats reset successfully', [
                'deleted_records' => $deletedCount,
                'reset_by' => 'truncate_table'
            ]);

            return [
                'success' => true,
                'message' => "Đã reset thành công! Truncate bảng visitors ({$deletedCount} records).",
                'deleted_count' => $deletedCount,
                'cache_cleared' => true
            ];

        } catch (\Exception $e) {
            Log::error('Failed to reset visitor stats: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Lỗi khi reset stats: ' . $e->getMessage(),
                'deleted_count' => 0,
                'cache_cleared' => false
            ];
        }
    }

    /**
     * Clear tất cả cache liên quan đến visitor stats
     */
    private function clearAllCache(): void
    {
        $cacheKeys = [
            'visitor_stats_today_unique',
            'visitor_stats_today_total',
            'visitor_stats_total_unique', 
            'visitor_stats_total_visits',
            'visitor_stats_popular_posts',
            'visitor_stats_popular_courses',
            'analytics_widget_stats'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Static method để gọi từ bên ngoài
     */
    public static function reset(): array
    {
        return static::run();
    }
}
