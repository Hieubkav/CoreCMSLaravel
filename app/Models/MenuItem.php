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
        'label',
        'menu_type',
        'link',
        'icon',
        'status',
        'order',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'order' => 'integer',
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
        return $query->where('status', 'active');
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('label');
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
    public function getUrlAttribute(): string
    {
        try {
            switch ($this->menu_type) {
                case 'home':
                    // Use storeFront route as the main homepage route
                    return route('storeFront');

                case 'all_posts':
                    // Check if posts.index route exists
                    if (Route::has('posts.index')) {
                        return route('posts.index');
                    }
                    return $this->link ?: '#';

                case 'all_products':
                    // Check if products.index route exists
                    if (Route::has('products.index')) {
                        return route('products.index');
                    }
                    return $this->link ?: '#';

                case 'post_category':
                case 'post_detail':
                case 'product_category':
                case 'product_detail':
                    // Sẽ cần implement logic để lấy URL từ ID
                    return $this->link ?: '#';

                case 'custom_link':
                default:
                    return $this->link ?: '#';
            }
        } catch (\Exception $e) {
            // Fallback to custom link or # if route generation fails
            return $this->link ?: '#';
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
                'label' => $current->label,
                'url' => $current->url
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
        $menuUrl = $this->url;

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
