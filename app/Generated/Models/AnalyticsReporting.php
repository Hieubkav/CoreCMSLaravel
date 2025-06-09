<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsReporting extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'metric_name',
        'metric_value',
        'metric_type',
        'dimension',
        'date',
        'hour',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'referer',
        'page_url',
        'event_category',
        'event_action',
        'event_label',
        'custom_data',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'language',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metric_value' => 'float',
        'date' => 'date',
        'hour' => 'integer',
        'user_id' => 'integer',
        'custom_data' => 'array',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'metric_type' => 'counter',
        'device_type' => 'desktop',
        'language' => 'vi',
        'country' => 'VN',
    ];

    /**
     * Metric types
     */
    public static function getMetricTypes(): array
    {
        return [
            'counter' => 'Bộ đếm',
            'gauge' => 'Đo lường',
            'timer' => 'Thời gian',
            'percentage' => 'Phần trăm',
            'currency' => 'Tiền tệ',
            'event' => 'Sự kiện',
        ];
    }

    /**
     * Event categories
     */
    public static function getEventCategories(): array
    {
        return [
            'page_view' => 'Lượt xem trang',
            'user_interaction' => 'Tương tác người dùng',
            'form_submission' => 'Gửi biểu mẫu',
            'download' => 'Tải xuống',
            'video' => 'Video',
            'social' => 'Mạng xã hội',
            'ecommerce' => 'Thương mại điện tử',
            'search' => 'Tìm kiếm',
            'navigation' => 'Điều hướng',
            'error' => 'Lỗi',
            'performance' => 'Hiệu suất',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Track metric
     */
    public static function track(string $metricName, float $value = 1, array $data = []): self
    {
        $request = request();
        
        return static::create(array_merge([
            'metric_name' => $metricName,
            'metric_value' => $value,
            'metric_type' => $data['metric_type'] ?? 'counter',
            'dimension' => $data['dimension'] ?? null,
            'date' => now()->toDateString(),
            'hour' => now()->hour,
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'page_url' => $request->fullUrl(),
            'device_type' => static::detectDeviceType($request->userAgent()),
            'browser' => static::detectBrowser($request->userAgent()),
            'os' => static::detectOS($request->userAgent()),
            'language' => app()->getLocale(),
        ], $data));
    }

    /**
     * Track event
     */
    public static function trackEvent(string $category, string $action, string $label = null, float $value = null, array $customData = []): self
    {
        return static::track('event', $value ?? 1, [
            'metric_type' => 'event',
            'event_category' => $category,
            'event_action' => $action,
            'event_label' => $label,
            'custom_data' => $customData,
        ]);
    }

    /**
     * Track page view
     */
    public static function trackPageView(string $url = null, string $title = null): self
    {
        $request = request();
        
        return static::trackEvent('page_view', 'view', $title, 1, [
            'page_url' => $url ?? $request->fullUrl(),
            'page_title' => $title,
        ]);
    }

    /**
     * Get analytics dashboard data
     */
    public static function getDashboardData(int $days = 30): array
    {
        $cacheKey = "analytics_dashboard_{$days}";
        
        return Cache::remember($cacheKey, 1800, function () use ($days) {
            $startDate = now()->subDays($days);
            
            return [
                'overview' => static::getOverviewMetrics($startDate),
                'page_views' => static::getPageViewsData($startDate),
                'user_behavior' => static::getUserBehaviorData($startDate),
                'traffic_sources' => static::getTrafficSourcesData($startDate),
                'device_breakdown' => static::getDeviceBreakdownData($startDate),
                'top_pages' => static::getTopPagesData($startDate),
                'real_time' => static::getRealTimeData(),
            ];
        });
    }

    /**
     * Get overview metrics
     */
    private static function getOverviewMetrics($startDate): array
    {
        return [
            'total_page_views' => static::where('event_category', 'page_view')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'unique_visitors' => static::where('created_at', '>=', $startDate)
                ->distinct('ip_address')
                ->count('ip_address'),
            'total_sessions' => static::where('created_at', '>=', $startDate)
                ->distinct('session_id')
                ->count('session_id'),
            'bounce_rate' => static::calculateBounceRate($startDate),
            'avg_session_duration' => static::calculateAvgSessionDuration($startDate),
        ];
    }

    /**
     * Get page views data
     */
    private static function getPageViewsData($startDate): array
    {
        return static::where('event_category', 'page_view')
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as views'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('views', 'date')
            ->toArray();
    }

    /**
     * Get user behavior data
     */
    private static function getUserBehaviorData($startDate): array
    {
        return [
            'top_events' => static::where('metric_type', 'event')
                ->where('created_at', '>=', $startDate)
                ->select('event_category', 'event_action', DB::raw('COUNT(*) as count'))
                ->groupBy('event_category', 'event_action')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->toArray(),
            'user_flow' => static::getUserFlowData($startDate),
        ];
    }

    /**
     * Get traffic sources data
     */
    private static function getTrafficSourcesData($startDate): array
    {
        return static::where('created_at', '>=', $startDate)
            ->whereNotNull('referer')
            ->select(DB::raw('
                CASE 
                    WHEN referer LIKE "%google%" THEN "Google"
                    WHEN referer LIKE "%facebook%" THEN "Facebook"
                    WHEN referer LIKE "%youtube%" THEN "YouTube"
                    WHEN referer LIKE "%twitter%" THEN "Twitter"
                    WHEN referer = "" OR referer IS NULL THEN "Direct"
                    ELSE "Other"
                END as source
            '), DB::raw('COUNT(*) as visits'))
            ->groupBy('source')
            ->orderBy('visits', 'desc')
            ->pluck('visits', 'source')
            ->toArray();
    }

    /**
     * Get device breakdown data
     */
    private static function getDeviceBreakdownData($startDate): array
    {
        return static::where('created_at', '>=', $startDate)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();
    }

    /**
     * Get top pages data
     */
    private static function getTopPagesData($startDate): array
    {
        return static::where('event_category', 'page_view')
            ->where('created_at', '>=', $startDate)
            ->select('page_url', DB::raw('COUNT(*) as views'))
            ->groupBy('page_url')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->pluck('views', 'page_url')
            ->toArray();
    }

    /**
     * Get real-time data
     */
    private static function getRealTimeData(): array
    {
        $lastHour = now()->subHour();
        
        return [
            'active_users' => static::where('created_at', '>=', $lastHour)
                ->distinct('session_id')
                ->count('session_id'),
            'page_views_last_hour' => static::where('event_category', 'page_view')
                ->where('created_at', '>=', $lastHour)
                ->count(),
            'top_pages_now' => static::where('event_category', 'page_view')
                ->where('created_at', '>=', $lastHour)
                ->select('page_url', DB::raw('COUNT(*) as views'))
                ->groupBy('page_url')
                ->orderBy('views', 'desc')
                ->limit(5)
                ->pluck('views', 'page_url')
                ->toArray(),
        ];
    }

    /**
     * Calculate bounce rate
     */
    private static function calculateBounceRate($startDate): float
    {
        $totalSessions = static::where('created_at', '>=', $startDate)
            ->distinct('session_id')
            ->count('session_id');
        
        $singlePageSessions = static::where('created_at', '>=', $startDate)
            ->select('session_id', DB::raw('COUNT(*) as page_count'))
            ->groupBy('session_id')
            ->having('page_count', '=', 1)
            ->count();
        
        return $totalSessions > 0 ? ($singlePageSessions / $totalSessions) * 100 : 0;
    }

    /**
     * Calculate average session duration
     */
    private static function calculateAvgSessionDuration($startDate): float
    {
        $sessions = static::where('created_at', '>=', $startDate)
            ->select('session_id', DB::raw('MIN(created_at) as start_time'), DB::raw('MAX(created_at) as end_time'))
            ->groupBy('session_id')
            ->get();
        
        $totalDuration = 0;
        $validSessions = 0;
        
        foreach ($sessions as $session) {
            $duration = strtotime($session->end_time) - strtotime($session->start_time);
            if ($duration > 0) {
                $totalDuration += $duration;
                $validSessions++;
            }
        }
        
        return $validSessions > 0 ? $totalDuration / $validSessions : 0;
    }

    /**
     * Get user flow data
     */
    private static function getUserFlowData($startDate): array
    {
        // Simplified user flow - entry and exit pages
        return [
            'entry_pages' => static::where('created_at', '>=', $startDate)
                ->whereNull('referer')
                ->select('page_url', DB::raw('COUNT(*) as entries'))
                ->groupBy('page_url')
                ->orderBy('entries', 'desc')
                ->limit(5)
                ->pluck('entries', 'page_url')
                ->toArray(),
            'exit_pages' => static::where('created_at', '>=', $startDate)
                ->select('page_url', DB::raw('COUNT(*) as exits'))
                ->groupBy('page_url')
                ->orderBy('exits', 'desc')
                ->limit(5)
                ->pluck('exits', 'page_url')
                ->toArray(),
        ];
    }

    /**
     * Detect device type from user agent
     */
    private static function detectDeviceType(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false) {
            return 'mobile';
        }
        
        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    private static function detectBrowser(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'safari') !== false) return 'Safari';
        if (strpos($userAgent, 'edge') !== false) return 'Edge';
        if (strpos($userAgent, 'opera') !== false) return 'Opera';
        
        return 'Other';
    }

    /**
     * Detect OS from user agent
     */
    private static function detectOS(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'windows') !== false) return 'Windows';
        if (strpos($userAgent, 'mac') !== false) return 'macOS';
        if (strpos($userAgent, 'linux') !== false) return 'Linux';
        if (strpos($userAgent, 'android') !== false) return 'Android';
        if (strpos($userAgent, 'ios') !== false) return 'iOS';
        
        return 'Other';
    }

    /**
     * Clear analytics cache
     */
    public static function clearAnalyticsCache(): void
    {
        $patterns = [
            'analytics_dashboard_*',
        ];
        
        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when analytics data is updated
        static::saved(function ($model) {
            static::clearAnalyticsCache();
        });

        static::deleted(function ($model) {
            static::clearAnalyticsCache();
        });
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
