<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'url',
        'route_name',
        'icon',
        'description',
        'target',
        'css_class',
        'menu_location',
        'is_active',
        'is_featured',
        'order',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'target' => '_self',
        'is_active' => true,
        'is_featured' => false,
        'order' => 0,
        'menu_location' => 'main',
    ];

    /**
     * Menu location options
     */
    public static function getMenuLocations(): array
    {
        return [
            'main' => 'Menu chính',
            'header' => 'Header',
            'footer' => 'Footer',
            'sidebar' => 'Sidebar',
            'mobile' => 'Mobile menu',
            'breadcrumb' => 'Breadcrumb',
        ];
    }

    /**
     * Target options
     */
    public static function getTargetOptions(): array
    {
        return [
            '_self' => 'Cùng tab (_self)',
            '_blank' => 'Tab mới (_blank)',
            '_parent' => 'Frame cha (_parent)',
            '_top' => 'Toàn bộ cửa sổ (_top)',
        ];
    }

    /**
     * Icon options (Font Awesome)
     */
    public static function getIconOptions(): array
    {
        return [
            'fas fa-home' => 'Trang chủ',
            'fas fa-info-circle' => 'Giới thiệu',
            'fas fa-newspaper' => 'Tin tức',
            'fas fa-shopping-cart' => 'Sản phẩm',
            'fas fa-users' => 'Đội ngũ',
            'fas fa-envelope' => 'Liên hệ',
            'fas fa-phone' => 'Hotline',
            'fas fa-map-marker-alt' => 'Địa chỉ',
            'fas fa-cog' => 'Dịch vụ',
            'fas fa-star' => 'Nổi bật',
            'fas fa-heart' => 'Yêu thích',
            'fas fa-download' => 'Tải về',
            'fas fa-external-link-alt' => 'Liên kết ngoài',
            'fas fa-file-alt' => 'Tài liệu',
            'fas fa-play' => 'Video',
            'fas fa-image' => 'Hình ảnh',
        ];
    }

    /**
     * Parent menu item relationship
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Child menu items relationship
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('order')
            ->orderBy('title');
    }

    /**
     * Active children relationship
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Get all descendants (recursive)
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->with('descendants');
    }

    /**
     * Get breadcrumb path
     */
    public function getBreadcrumbPath(): array
    {
        $path = [];
        $current = $this;
        
        while ($current) {
            array_unshift($path, $current);
            $current = $current->parent;
        }
        
        return $path;
    }

    /**
     * Check if menu item has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if menu item has active children
     */
    public function hasActiveChildren(): bool
    {
        return $this->activeChildren()->exists();
    }

    /**
     * Get depth level
     */
    public function getDepthLevel(): int
    {
        $level = 0;
        $current = $this->parent;
        
        while ($current) {
            $level++;
            $current = $current->parent;
        }
        
        return $level;
    }

    /**
     * Get full URL
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                return $this->url ?: '#';
            }
        }
        
        if ($this->url) {
            // Check if URL is external
            if (filter_var($this->url, FILTER_VALIDATE_URL)) {
                return $this->url;
            }
            
            // Internal URL
            return url($this->url);
        }
        
        return '#';
    }

    /**
     * Check if URL is external
     */
    public function isExternalUrl(): bool
    {
        return $this->url && filter_var($this->url, FILTER_VALIDATE_URL);
    }

    /**
     * Get icon HTML
     */
    public function getIconHtmlAttribute(): string
    {
        if (!$this->icon) {
            return '';
        }
        
        return "<i class=\"{$this->icon}\"></i>";
    }

    /**
     * Scope for root menu items (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for active menu items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured menu items
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for specific menu location
     */
    public function scopeLocation($query, string $location)
    {
        return $query->where('menu_location', $location);
    }

    /**
     * Scope for ordered menu items
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    /**
     * Get menu tree for specific location
     */
    public static function getMenuTree(string $location = 'main', bool $activeOnly = true): array
    {
        $query = static::root()
            ->location($location)
            ->with(['children' => function ($query) use ($activeOnly) {
                $query->ordered();
                if ($activeOnly) {
                    $query->active();
                }
            }])
            ->ordered();
            
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->get()->toArray();
    }

    /**
     * Generate slug from title
     */
    public function generateSlug(): string
    {
        if ($this->slug) {
            return $this->slug;
        }
        
        return \Illuminate\Support\Str::slug($this->title);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug if not provided
        static::creating(function ($model) {
            if (!$model->slug && $model->title) {
                $model->slug = $model->generateSlug();
            }
            
            // Auto-increment order for new records
            if (is_null($model->order)) {
                $maxOrder = static::where('parent_id', $model->parent_id)
                    ->where('menu_location', $model->menu_location)
                    ->max('order');
                $model->order = ($maxOrder ?? 0) + 1;
            }
        });

        // Update slug when title changes
        static::updating(function ($model) {
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                $model->slug = $model->generateSlug();
            }
        });
    }
}
