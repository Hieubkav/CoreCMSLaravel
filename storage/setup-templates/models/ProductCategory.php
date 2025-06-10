<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'meta_data',
        'status',
        'order',
        'seo_title',
        'seo_description',
        'og_image',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'parent_id' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với parent category
     */
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    /**
     * Quan hệ với children categories
     */
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->ordered();
    }

    /**
     * Quan hệ với products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Scope cho categories active
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
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Scope cho top level categories
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Lấy URL image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return asset('images/placeholder-category.jpg');
    }

    /**
     * Lấy icon với fallback
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?: 'fas fa-folder';
    }

    /**
     * Kiểm tra có children không
     */
    public function hasChildren(): bool
    {
        return $this->children()->active()->exists();
    }

    /**
     * Lấy category tree hierarchical
     */
    public static function getCategoryTree(): \Illuminate\Database\Eloquent\Collection
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
     * Lấy breadcrumb từ category
     */
    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $current = $this;

        while ($current) {
            array_unshift($breadcrumb, [
                'name' => $current->name,
                'slug' => $current->slug,
                'url' => route('products.category', $current->slug)
            ]);
            $current = $current->parent;
        }

        return $breadcrumb;
    }

    /**
     * Lấy depth level của category
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

    /**
     * Lấy tất cả product IDs trong category và subcategories
     */
    public function getAllProductIds(): array
    {
        $categoryIds = [$this->id];
        $descendants = $this->getAllDescendants();
        
        foreach ($descendants as $descendant) {
            $categoryIds[] = $descendant->id;
        }

        return Product::whereIn('category_id', $categoryIds)
                     ->where('status', 'active')
                     ->pluck('id')
                     ->toArray();
    }

    /**
     * Đếm số products trong category (bao gồm subcategories)
     */
    public function getProductCountAttribute(): int
    {
        return count($this->getAllProductIds());
    }

    /**
     * Lấy featured products
     */
    public function getFeaturedProducts(int $limit = 8)
    {
        $productIds = $this->getAllProductIds();
        
        return Product::whereIn('id', $productIds)
                     ->where('is_featured', true)
                     ->where('status', 'active')
                     ->ordered()
                     ->limit($limit)
                     ->get();
    }

    /**
     * Validate không được tạo circular reference
     */
    public function canSetParent(int $parentId): bool
    {
        if ($parentId === $this->id) {
            return false; // Không thể set chính nó làm parent
        }

        // Kiểm tra xem parentId có phải là descendant của current category không
        $descendants = $this->getAllDescendants();
        return !$descendants->contains('id', $parentId);
    }

    /**
     * Lấy SEO title
     */
    public function getSeoTitleAttribute($value): string
    {
        return $value ?: $this->name;
    }

    /**
     * Lấy SEO description
     */
    public function getSeoDescriptionAttribute($value): string
    {
        return $value ?: \Str::limit(strip_tags($this->description), 160);
    }

    /**
     * Lấy OG image URL
     */
    public function getOgImageUrlAttribute(): string
    {
        if ($this->og_image) {
            return Storage::url($this->og_image);
        }

        return $this->image_url;
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
