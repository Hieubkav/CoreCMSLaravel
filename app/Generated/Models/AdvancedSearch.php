<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdvancedSearch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'query',
        'user_id',
        'ip_address',
        'user_agent',
        'results_count',
        'search_type',
        'filters',
        'sort_by',
        'sort_direction',
        'page',
        'per_page',
        'execution_time',
        'has_results',
        'clicked_result_id',
        'clicked_result_type',
        'session_id',
        'referer',
        'language',
        'device_type',
        'location',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'filters' => 'array',
        'has_results' => 'boolean',
        'execution_time' => 'float',
        'page' => 'integer',
        'per_page' => 'integer',
        'results_count' => 'integer',
        'user_id' => 'integer',
        'clicked_result_id' => 'integer',
        'location' => 'array',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'search_type' => 'general',
        'sort_by' => 'relevance',
        'sort_direction' => 'desc',
        'page' => 1,
        'per_page' => 20,
        'has_results' => false,
        'language' => 'vi',
        'device_type' => 'desktop',
    ];

    /**
     * Search types
     */
    public static function getSearchTypes(): array
    {
        return [
            'general' => 'Tìm kiếm chung',
            'posts' => 'Bài viết',
            'products' => 'Sản phẩm',
            'courses' => 'Khóa học',
            'users' => 'Người dùng',
            'categories' => 'Danh mục',
            'tags' => 'Thẻ',
            'files' => 'Tệp tin',
            'advanced' => 'Tìm kiếm nâng cao',
        ];
    }

    /**
     * Sort options
     */
    public static function getSortOptions(): array
    {
        return [
            'relevance' => 'Độ liên quan',
            'date' => 'Ngày tạo',
            'title' => 'Tiêu đề',
            'views' => 'Lượt xem',
            'popularity' => 'Độ phổ biến',
            'rating' => 'Đánh giá',
            'price' => 'Giá',
            'alphabetical' => 'Thứ tự ABC',
        ];
    }

    /**
     * Device types
     */
    public static function getDeviceTypes(): array
    {
        return [
            'desktop' => 'Máy tính',
            'mobile' => 'Điện thoại',
            'tablet' => 'Máy tính bảng',
            'bot' => 'Bot/Crawler',
        ];
    }

    /**
     * Log search query
     */
    public static function logSearch(array $data): self
    {
        // Detect device type
        $userAgent = $data['user_agent'] ?? request()->userAgent();
        $deviceType = static::detectDeviceType($userAgent);
        
        // Get location from IP (simplified)
        $location = static::getLocationFromIP($data['ip_address'] ?? request()->ip());
        
        return static::create(array_merge($data, [
            'device_type' => $deviceType,
            'location' => $location,
            'session_id' => session()->getId(),
            'referer' => request()->header('referer'),
            'language' => app()->getLocale(),
        ]));
    }

    /**
     * Get popular searches
     */
    public static function getPopularSearches(int $limit = 10, int $days = 30): array
    {
        $cacheKey = "popular_searches_{$limit}_{$days}";
        
        return Cache::remember($cacheKey, 3600, function () use ($limit, $days) {
            return static::where('created_at', '>=', now()->subDays($days))
                ->where('has_results', true)
                ->select('query', DB::raw('COUNT(*) as search_count'))
                ->groupBy('query')
                ->orderBy('search_count', 'desc')
                ->limit($limit)
                ->pluck('search_count', 'query')
                ->toArray();
        });
    }

    /**
     * Get search suggestions
     */
    public static function getSearchSuggestions(string $query, int $limit = 5): array
    {
        $cacheKey = "search_suggestions_" . md5($query) . "_{$limit}";
        
        return Cache::remember($cacheKey, 1800, function () use ($query, $limit) {
            return static::where('query', 'LIKE', $query . '%')
                ->where('has_results', true)
                ->select('query', DB::raw('COUNT(*) as frequency'))
                ->groupBy('query')
                ->orderBy('frequency', 'desc')
                ->limit($limit)
                ->pluck('query')
                ->toArray();
        });
    }

    /**
     * Get search analytics
     */
    public static function getSearchAnalytics(int $days = 30): array
    {
        $cacheKey = "search_analytics_{$days}";
        
        return Cache::remember($cacheKey, 3600, function () use ($days) {
            $startDate = now()->subDays($days);
            
            return [
                'total_searches' => static::where('created_at', '>=', $startDate)->count(),
                'unique_queries' => static::where('created_at', '>=', $startDate)
                    ->distinct('query')
                    ->count('query'),
                'successful_searches' => static::where('created_at', '>=', $startDate)
                    ->where('has_results', true)
                    ->count(),
                'failed_searches' => static::where('created_at', '>=', $startDate)
                    ->where('has_results', false)
                    ->count(),
                'average_execution_time' => static::where('created_at', '>=', $startDate)
                    ->avg('execution_time'),
                'top_search_types' => static::where('created_at', '>=', $startDate)
                    ->select('search_type', DB::raw('COUNT(*) as count'))
                    ->groupBy('search_type')
                    ->orderBy('count', 'desc')
                    ->limit(5)
                    ->pluck('count', 'search_type')
                    ->toArray(),
                'device_breakdown' => static::where('created_at', '>=', $startDate)
                    ->select('device_type', DB::raw('COUNT(*) as count'))
                    ->groupBy('device_type')
                    ->pluck('count', 'device_type')
                    ->toArray(),
                'daily_searches' => static::where('created_at', '>=', $startDate)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('count', 'date')
                    ->toArray(),
            ];
        });
    }

    /**
     * Get failed searches (no results)
     */
    public static function getFailedSearches(int $limit = 20, int $days = 7): array
    {
        return static::where('created_at', '>=', now()->subDays($days))
            ->where('has_results', false)
            ->select('query', DB::raw('COUNT(*) as frequency'))
            ->groupBy('query')
            ->orderBy('frequency', 'desc')
            ->limit($limit)
            ->pluck('frequency', 'query')
            ->toArray();
    }

    /**
     * Get search trends
     */
    public static function getSearchTrends(int $days = 30): array
    {
        $cacheKey = "search_trends_{$days}";
        
        return Cache::remember($cacheKey, 3600, function () use ($days) {
            $trends = [];
            
            for ($i = $days; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $count = static::whereDate('created_at', $date)->count();
                $trends[$date] = $count;
            }
            
            return $trends;
        });
    }

    /**
     * Log search result click
     */
    public function logClick(int $resultId, string $resultType): bool
    {
        return $this->update([
            'clicked_result_id' => $resultId,
            'clicked_result_type' => $resultType,
        ]);
    }

    /**
     * Get click-through rate
     */
    public static function getClickThroughRate(int $days = 30): float
    {
        $totalSearches = static::where('created_at', '>=', now()->subDays($days))
            ->where('has_results', true)
            ->count();
        
        $clickedSearches = static::where('created_at', '>=', now()->subDays($days))
            ->where('has_results', true)
            ->whereNotNull('clicked_result_id')
            ->count();
        
        return $totalSearches > 0 ? ($clickedSearches / $totalSearches) * 100 : 0;
    }

    /**
     * Detect device type from user agent
     */
    private static function detectDeviceType(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);
        
        if (strpos($userAgent, 'bot') !== false || strpos($userAgent, 'crawler') !== false) {
            return 'bot';
        }
        
        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false) {
            return 'mobile';
        }
        
        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    /**
     * Get location from IP (simplified implementation)
     */
    private static function getLocationFromIP(string $ip): array
    {
        // In production, you would use a service like MaxMind GeoIP
        // For now, return basic info
        return [
            'ip' => $ip,
            'country' => 'VN',
            'city' => 'Ho Chi Minh City',
            'timezone' => 'Asia/Ho_Chi_Minh',
        ];
    }

    /**
     * Clear search cache
     */
    public static function clearSearchCache(): void
    {
        $patterns = [
            'popular_searches_*',
            'search_suggestions_*',
            'search_analytics_*',
            'search_trends_*',
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

        // Clear cache when search data is updated
        static::saved(function ($model) {
            static::clearSearchCache();
        });

        static::deleted(function ($model) {
            static::clearSearchCache();
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
