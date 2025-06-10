<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;

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

    protected $casts = [
        'parent_id' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Quan hệ với parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Quan hệ với children menu items
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->ordered();
    }

    /**
     * Scope cho menu items active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('title');
    }

    /**
     * Scope cho top level menu items
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Lấy danh sách menu types
     */
    public static function getMenuTypes(): array
    {
        return [
            'custom_link' => 'Link tùy chỉnh',
            'post_category' => 'Danh mục bài viết',
            'post_detail' => 'Chi tiết bài viết',
            'all_posts' => 'Tất cả bài viết',
            'product_category' => 'Danh mục sản phẩm',
            'product_detail' => 'Chi tiết sản phẩm',
            'all_products' => 'Tất cả sản phẩm',
            'home' => 'Trang chủ',
        ];
    }

    /**
     * Lấy URL thực tế của menu item
     */
    public function getResolvedUrlAttribute(): string
    {
        try {
            // If route_name is specified, try to generate route
            if ($this->route_name && Route::has($this->route_name)) {
                return route($this->route_name);
            }

            // If URL is specified, use it directly
            if ($this->url) {
                return $this->url;
            }

            // Fallback to #
            return '#';
        } catch (\Exception $e) {
            // Fallback to URL or # if route generation fails
            return $this->url ?: '#';
        }
    }

    /**
     * Kiểm tra có children không
     */
    public function hasChildren(): bool
    {
        return $this->children()->active()->exists();
    }

    /**
     * Lấy icon với fallback
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?: 'fas fa-link';
    }

    /**
     * Lấy menu tree hierarchical
     */
    public static function getMenuTree(): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
                    ->topLevel()
                    ->ordered()
                    ->with(['children' => function ($query) {
                        $query->active()->ordered();
                    }])
                    ->get();
    }

    /**
     * Tạo breadcrumb từ menu item
     */
    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $current = $this;

        while ($current) {
            array_unshift($breadcrumb, [
                'label' => $current->title,
                'url' => $current->resolved_url
            ]);
            $current = $current->parent;
        }

        return $breadcrumb;
    }

    /**
     * Kiểm tra menu item có active không (dựa vào URL hiện tại)
     */
    public function isActive(): bool
    {
        $currentUrl = request()->url();
        $menuUrl = $this->resolved_url;

        // Exact match
        if ($currentUrl === $menuUrl) {
            return true;
        }

        // Check if current URL starts with menu URL (for parent menus)
        if ($menuUrl !== '#' && str_starts_with($currentUrl, $menuUrl)) {
            return true;
        }

        // Check children
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lấy depth level của menu item
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $current = $this->parent;

        while ($current) {
            $depth++;
            $current = $current->parent;
        }

        return $depth;
    }

    /**
     * Validate không được tạo circular reference
     */
    public function canSetParent(int $parentId): bool
    {
        if ($parentId === $this->id) {
            return false; // Không thể set chính nó làm parent
        }

        // Kiểm tra xem parentId có phải là descendant của current item không
        $descendants = $this->getAllDescendants();
        return !$descendants->contains('id', $parentId);
    }

    /**
     * Lấy tất cả descendants
     */
    public function getAllDescendants(): \Illuminate\Database\Eloquent\Collection
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }

        return $descendants;
    }
}
