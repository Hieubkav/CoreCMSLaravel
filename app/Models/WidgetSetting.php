<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class WidgetSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'widget_type',
        'widget_class',
        'template_path',
        'widget_config',
        'content',
        'data_source',
        'display_options',
        'position',
        'custom_position',
        'order_position',
        'show_on_mobile',
        'show_on_tablet',
        'show_on_desktop',
        'responsive_config',
        'show_on_pages',
        'hide_on_pages',
        'show_for_roles',
        'show_for_users',
        'show_for_guests',
        'display_conditions',
        'show_from',
        'show_until',
        'schedule',
        'css_class',
        'css_id',
        'inline_styles',
        'custom_css',
        'custom_js',
        'show_title',
        'title_tag',
        'wrapper_tag',
        'show_border',
        'show_shadow',
        'cache_enabled',
        'cache_duration',
        'cache_tags',
        'user_specific_cache',
        'lazy_load',
        'async_load',
        'load_priority',
        'view_count',
        'click_count',
        'interaction_stats',
        'last_updated_at',
        'parent_widget_id',
        'child_widgets',
        'related_widgets',
        'ab_testing_enabled',
        'ab_variants',
        'ab_traffic_split',
        'author_id',
        'last_editor_id',
        'edit_history',
        'edit_permissions',
        'view_permissions',
        'is_locked',
        'api_endpoints',
        'webhook_urls',
        'third_party_config',
        'status',
        'is_system_widget',
        'version',
        'order',
    ];

    protected $casts = [
        'widget_config' => 'array',
        'data_source' => 'array',
        'display_options' => 'array',
        'order_position' => 'integer',
        'show_on_mobile' => 'boolean',
        'show_on_tablet' => 'boolean',
        'show_on_desktop' => 'boolean',
        'responsive_config' => 'array',
        'show_on_pages' => 'array',
        'hide_on_pages' => 'array',
        'show_for_roles' => 'array',
        'show_for_users' => 'array',
        'show_for_guests' => 'boolean',
        'display_conditions' => 'array',
        'show_from' => 'datetime',
        'show_until' => 'datetime',
        'schedule' => 'array',
        'inline_styles' => 'array',
        'show_title' => 'boolean',
        'show_border' => 'boolean',
        'show_shadow' => 'boolean',
        'cache_enabled' => 'boolean',
        'cache_duration' => 'integer',
        'cache_tags' => 'array',
        'user_specific_cache' => 'boolean',
        'lazy_load' => 'boolean',
        'async_load' => 'boolean',
        'load_priority' => 'integer',
        'view_count' => 'integer',
        'click_count' => 'integer',
        'interaction_stats' => 'array',
        'last_updated_at' => 'datetime',
        'parent_widget_id' => 'integer',
        'child_widgets' => 'array',
        'related_widgets' => 'array',
        'ab_testing_enabled' => 'boolean',
        'ab_variants' => 'array',
        'ab_traffic_split' => 'decimal:2',
        'author_id' => 'integer',
        'last_editor_id' => 'integer',
        'edit_history' => 'array',
        'edit_permissions' => 'array',
        'view_permissions' => 'array',
        'is_locked' => 'boolean',
        'api_endpoints' => 'array',
        'webhook_urls' => 'array',
        'third_party_config' => 'array',
        'is_system_widget' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Quan hệ với last editor
     */
    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_editor_id');
    }

    /**
     * Quan hệ với parent widget
     */
    public function parentWidget()
    {
        return $this->belongsTo(WidgetSetting::class, 'parent_widget_id');
    }

    /**
     * Quan hệ với child widgets
     */
    public function childWidgets()
    {
        return $this->hasMany(WidgetSetting::class, 'parent_widget_id');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($widget) {
            if (!$widget->author_id) {
                $widget->author_id = auth()->id();
            }
        });

        static::updating(function ($widget) {
            $widget->last_editor_id = auth()->id();
            $widget->last_updated_at = now();
        });

        static::saved(function ($widget) {
            if ($widget->cache_enabled) {
                Cache::forget("widget_content_{$widget->id}");
                Cache::forget("widgets_position_{$widget->position}");
            }
        });
    }

    /**
     * Scope cho widgets active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope theo position
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope sắp xếp theo order position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_position')->orderBy('name');
    }

    /**
     * Scope cho current device
     */
    public function scopeForCurrentDevice($query)
    {
        $device = $this->detectDevice();
        
        switch ($device) {
            case 'mobile':
                return $query->where('show_on_mobile', true);
            case 'tablet':
                return $query->where('show_on_tablet', true);
            default:
                return $query->where('show_on_desktop', true);
        }
    }

    /**
     * Lấy danh sách widget types
     */
    public static function getWidgetTypes(): array
    {
        return [
            'text' => 'Văn bản',
            'html' => 'HTML',
            'image' => 'Hình ảnh',
            'gallery' => 'Thư viện ảnh',
            'video' => 'Video',
            'audio' => 'Audio',
            'recent_posts' => 'Bài viết mới',
            'popular_posts' => 'Bài viết phổ biến',
            'categories' => 'Danh mục',
            'tags' => 'Thẻ',
            'archives' => 'Lưu trữ',
            'recent_products' => 'Sản phẩm mới',
            'featured_products' => 'Sản phẩm nổi bật',
            'product_categories' => 'Danh mục sản phẩm',
            'search' => 'Tìm kiếm',
            'newsletter' => 'Đăng ký nhận tin',
            'social_media' => 'Mạng xã hội',
            'contact_info' => 'Thông tin liên hệ',
            'testimonials' => 'Đánh giá',
            'faq' => 'Câu hỏi thường gặp',
            'calendar' => 'Lịch',
            'weather' => 'Thời tiết',
            'map' => 'Bản đồ',
            'custom' => 'Tùy chỉnh',
            'shortcode' => 'Shortcode',
        ];
    }

    /**
     * Lấy widget type label
     */
    public function getWidgetTypeLabelAttribute(): string
    {
        return static::getWidgetTypes()[$this->widget_type] ?? $this->widget_type;
    }

    /**
     * Kiểm tra widget có thể hiển thị không
     */
    public function canDisplay(?int $userId = null, ?string $currentPage = null): bool
    {
        // Check status
        if ($this->status !== 'active') {
            return false;
        }

        // Check date range
        if ($this->show_from && $this->show_from > now()) {
            return false;
        }

        if ($this->show_until && $this->show_until < now()) {
            return false;
        }

        // Check schedule
        if ($this->schedule && !$this->isInSchedule()) {
            return false;
        }

        // Check page restrictions
        if (!$this->isAllowedOnCurrentPage($currentPage)) {
            return false;
        }

        // Check user permissions
        if (!$this->isAllowedForUser($userId)) {
            return false;
        }

        // Check display conditions
        if ($this->display_conditions && !$this->checkDisplayConditions()) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có trong schedule không
     */
    private function isInSchedule(): bool
    {
        if (!$this->schedule) {
            return true;
        }

        $now = now();
        $currentDay = $now->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        $currentHour = $now->hour;

        $allowedDays = $this->schedule['days'] ?? [];
        $allowedHours = $this->schedule['hours'] ?? [];

        if (!empty($allowedDays) && !in_array($currentDay, $allowedDays)) {
            return false;
        }

        if (!empty($allowedHours) && !in_array($currentHour, $allowedHours)) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có được phép hiển thị trên trang hiện tại không
     */
    private function isAllowedOnCurrentPage(?string $currentPage): bool
    {
        // If no restrictions, allow all pages
        if (!$this->show_on_pages && !$this->hide_on_pages) {
            return true;
        }

        // Check hide_on_pages first
        if ($this->hide_on_pages && in_array($currentPage, $this->hide_on_pages)) {
            return false;
        }

        // Check show_on_pages
        if ($this->show_on_pages && !in_array($currentPage, $this->show_on_pages)) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có được phép hiển thị cho user không
     */
    private function isAllowedForUser(?int $userId): bool
    {
        // Check guest access
        if (!$userId && !$this->show_for_guests) {
            return false;
        }

        // Check specific users
        if ($this->show_for_users && $userId && !in_array($userId, $this->show_for_users)) {
            return false;
        }

        // Check user roles
        if ($this->show_for_roles && $userId) {
            $user = User::find($userId);
            if ($user && !$user->hasAnyRole($this->show_for_roles)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Kiểm tra display conditions
     */
    private function checkDisplayConditions(): bool
    {
        if (!$this->display_conditions) {
            return true;
        }

        foreach ($this->display_conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '=';
            $value = $condition['value'] ?? '';

            // Get actual value based on field
            $actualValue = $this->getConditionValue($field);

            if (!$this->evaluateCondition($actualValue, $operator, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get condition value
     */
    private function getConditionValue(string $field)
    {
        switch ($field) {
            case 'user_logged_in':
                return auth()->check();
            case 'user_role':
                return auth()->user()?->getRoleNames()->first();
            case 'page_type':
                return request()->route()?->getName();
            case 'device_type':
                return $this->detectDevice();
            default:
                return null;
        }
    }

    /**
     * Evaluate condition
     */
    private function evaluateCondition($actualValue, string $operator, $expectedValue): bool
    {
        switch ($operator) {
            case '=':
                return $actualValue == $expectedValue;
            case '!=':
                return $actualValue != $expectedValue;
            case 'in':
                return in_array($actualValue, (array) $expectedValue);
            case 'not_in':
                return !in_array($actualValue, (array) $expectedValue);
            case 'contains':
                return str_contains($actualValue, $expectedValue);
            default:
                return true;
        }
    }

    /**
     * Detect device type
     */
    private function detectDevice(): string
    {
        $userAgent = request()->header('User-Agent', '');
        
        if (preg_match('/Mobile|Android|iPhone/', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/iPad|Tablet/', $userAgent)) {
            return 'tablet';
        }
        
        return 'desktop';
    }

    /**
     * Render widget content
     */
    public function render(array $data = []): string
    {
        if (!$this->canDisplay(auth()->id(), request()->route()?->getName())) {
            return '';
        }

        $cacheKey = $this->getCacheKey($data);
        
        if ($this->cache_enabled && !$this->user_specific_cache) {
            return Cache::remember($cacheKey, $this->cache_duration, function () use ($data) {
                return $this->buildContent($data);
            });
        }

        return $this->buildContent($data);
    }

    /**
     * Get cache key
     */
    private function getCacheKey(array $data): string
    {
        $key = "widget_content_{$this->id}";
        
        if ($this->user_specific_cache) {
            $key .= '_user_' . (auth()->id() ?: 'guest');
        }
        
        if (!empty($data)) {
            $key .= '_' . md5(serialize($data));
        }
        
        return $key;
    }

    /**
     * Build widget content
     */
    private function buildContent(array $data = []): string
    {
        try {
            // Merge widget config with provided data
            $widgetData = array_merge($this->widget_config ?: [], $data);

            // Get content based on widget type
            $content = $this->getWidgetContent($widgetData);

            // Wrap content if needed
            return $this->wrapContent($content);

        } catch (\Exception $e) {
            \Log::error("Error rendering widget {$this->id}: " . $e->getMessage());
            return "<!-- Widget render error -->";
        }
    }

    /**
     * Get widget content based on type
     */
    private function getWidgetContent(array $data): string
    {
        switch ($this->widget_type) {
            case 'text':
                return $this->content ?: '';

            case 'html':
                return $this->content ?: '';

            case 'recent_posts':
                return $this->renderRecentPosts($data);

            case 'recent_products':
                return $this->renderRecentProducts($data);

            case 'search':
                return $this->renderSearchWidget($data);

            case 'custom':
                return $this->renderCustomWidget($data);

            default:
                if ($this->template_path && View::exists($this->template_path)) {
                    return View::make($this->template_path, $data)->render();
                }
                return $this->content ?: '';
        }
    }

    /**
     * Render recent posts widget
     */
    private function renderRecentPosts(array $data): string
    {
        $limit = $data['limit'] ?? 5;
        $posts = \App\Models\Post::published()->limit($limit)->get();
        
        return View::make('widgets.recent-posts', compact('posts'))->render();
    }

    /**
     * Render recent products widget
     */
    private function renderRecentProducts(array $data): string
    {
        $limit = $data['limit'] ?? 5;
        $products = \App\Models\Product::active()->latest()->limit($limit)->get();
        
        return View::make('widgets.recent-products', compact('products'))->render();
    }

    /**
     * Render search widget
     */
    private function renderSearchWidget(array $data): string
    {
        return View::make('widgets.search', $data)->render();
    }

    /**
     * Render custom widget
     */
    private function renderCustomWidget(array $data): string
    {
        if ($this->widget_class && class_exists($this->widget_class)) {
            $widgetInstance = new $this->widget_class();
            return $widgetInstance->render($data);
        }

        return $this->content ?: '';
    }

    /**
     * Wrap content with HTML wrapper
     */
    private function wrapContent(string $content): string
    {
        if (empty($content)) {
            return '';
        }

        $wrapperTag = $this->wrapper_tag ?: 'div';
        $cssClass = $this->css_class ? " class=\"{$this->css_class}\"" : '';
        $cssId = $this->css_id ? " id=\"{$this->css_id}\"" : '';
        
        $styles = [];
        if ($this->inline_styles) {
            foreach ($this->inline_styles as $property => $value) {
                $styles[] = "{$property}: {$value}";
            }
        }
        $styleAttr = !empty($styles) ? " style=\"" . implode('; ', $styles) . "\"" : '';

        $html = "<{$wrapperTag}{$cssClass}{$cssId}{$styleAttr}>";
        
        if ($this->show_title && $this->name) {
            $titleTag = $this->title_tag ?: 'h3';
            $html .= "<{$titleTag} class=\"widget-title\">{$this->name}</{$titleTag}>";
        }
        
        $html .= $content;
        $html .= "</{$wrapperTag}>";

        return $html;
    }

    /**
     * Get widgets for position
     */
    public static function getForPosition(string $position): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("widgets_position_{$position}", 3600, function () use ($position) {
            return static::active()
                         ->byPosition($position)
                         ->forCurrentDevice()
                         ->ordered()
                         ->get();
        });
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Increment click count
     */
    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
