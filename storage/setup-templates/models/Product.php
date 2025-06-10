<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'sku',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'low_stock_threshold',
        'weight',
        'length',
        'width',
        'height',
        'featured_image',
        'gallery_images',
        'type',
        'status',
        'is_featured',
        'is_digital',
        'seo_title',
        'seo_description',
        'og_image',
        'meta_data',
        'view_count',
        'order_count',
        'average_rating',
        'review_count',
        'order',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'meta_data' => 'array',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'average_rating' => 'decimal:2',
        'review_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với category
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Quan hệ với variants
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Quan hệ với attributes
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Quan hệ với reviews
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Quan hệ với cart items
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Quan hệ với wishlist items
     */
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Quan hệ với order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Quan hệ với inventory logs
     */
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * Scope cho products active
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
     * Scope cho featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope cho products có stock
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    /**
     * Scope cho products có sale
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
    }

    /**
     * Lấy URL featured image
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }

        return asset('images/placeholder-product.jpg');
    }

    /**
     * Lấy gallery images URLs
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return [];
        }

        return array_map(function ($image) {
            return Storage::url($image);
        }, $this->gallery_images);
    }

    /**
     * Lấy giá hiển thị (sale price hoặc regular price)
     */
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    /**
     * Kiểm tra có đang sale không
     */
    public function isOnSale(): bool
    {
        return $this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->isOnSale()) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Kiểm tra có variants không
     */
    public function hasVariants(): bool
    {
        return $this->type === 'variable' && $this->variants()->exists();
    }

    /**
     * Lấy variant rẻ nhất
     */
    public function getCheapestVariant()
    {
        if (!$this->hasVariants()) {
            return null;
        }

        return $this->variants()
                   ->where('status', 'active')
                   ->orderBy('price')
                   ->first();
    }

    /**
     * Lấy range giá cho variable product
     */
    public function getPriceRangeAttribute(): array
    {
        if (!$this->hasVariants()) {
            return [
                'min' => $this->display_price,
                'max' => $this->display_price
            ];
        }

        $variants = $this->variants()->where('status', 'active')->get();
        $prices = $variants->map(function ($variant) {
            return $variant->sale_price ?: $variant->price;
        });

        return [
            'min' => $prices->min(),
            'max' => $prices->max()
        ];
    }

    /**
     * Kiểm tra stock status
     */
    public function isInStock(): bool
    {
        if (!$this->manage_stock) {
            return $this->in_stock;
        }

        if ($this->hasVariants()) {
            return $this->variants()
                       ->where('status', 'active')
                       ->where('in_stock', true)
                       ->where('stock_quantity', '>', 0)
                       ->exists();
        }

        return $this->stock_quantity > 0;
    }

    /**
     * Kiểm tra low stock
     */
    public function isLowStock(): bool
    {
        if (!$this->manage_stock || !$this->isInStock()) {
            return false;
        }

        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    /**
     * Lấy stock status text
     */
    public function getStockStatusAttribute(): string
    {
        if (!$this->isInStock()) {
            return 'Hết hàng';
        }

        if ($this->isLowStock()) {
            return 'Sắp hết hàng';
        }

        return 'Còn hàng';
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Lấy related products
     */
    public function getRelatedProducts(int $limit = 4)
    {
        return static::where('category_id', $this->category_id)
                    ->where('id', '!=', $this->id)
                    ->where('status', 'active')
                    ->inStock()
                    ->ordered()
                    ->limit($limit)
                    ->get();
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
