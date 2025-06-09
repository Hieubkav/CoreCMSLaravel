<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'weight',
        'length',
        'width',
        'height',
        'image',
        'gallery_images',
        'attribute_values',
        'status',
        'order',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'gallery_images' => 'array',
        'attribute_values' => 'array',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với cart items
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'variant_id');
    }

    /**
     * Quan hệ với wishlist items
     */
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class, 'variant_id');
    }

    /**
     * Quan hệ với order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    /**
     * Quan hệ với inventory logs
     */
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'variant_id');
    }

    /**
     * Scope cho variants active
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
     * Scope cho variants có stock
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true)->where('stock_quantity', '>', 0);
    }

    /**
     * Scope cho variants có sale
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
    }

    /**
     * Lấy URL image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        // Fallback to product featured image
        return $this->product->featured_image_url;
    }

    /**
     * Lấy gallery images URLs
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return $this->product->gallery_image_urls;
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
     * Kiểm tra stock status
     */
    public function isInStock(): bool
    {
        if (!$this->manage_stock) {
            return $this->in_stock;
        }

        return $this->stock_quantity > 0;
    }

    /**
     * Lấy stock status text
     */
    public function getStockStatusAttribute(): string
    {
        if (!$this->isInStock()) {
            return 'Hết hàng';
        }

        if ($this->stock_quantity <= 5) {
            return 'Sắp hết hàng';
        }

        return 'Còn hàng';
    }

    /**
     * Lấy attribute values formatted
     */
    public function getFormattedAttributesAttribute(): array
    {
        if (!$this->attribute_values) {
            return [];
        }

        $formatted = [];
        foreach ($this->attribute_values as $attributeId => $value) {
            $attribute = ProductAttribute::find($attributeId);
            if ($attribute) {
                $formatted[$attribute->name] = $attribute->formatValue($value);
            }
        }

        return $formatted;
    }

    /**
     * Lấy attribute values cho display
     */
    public function getAttributeDisplayAttribute(): string
    {
        $attributes = $this->formatted_attributes;
        
        if (empty($attributes)) {
            return '';
        }

        return implode(' - ', array_values($attributes));
    }

    /**
     * Tìm variant theo attribute combination
     */
    public static function findByAttributes($productId, array $attributes)
    {
        return static::where('product_id', $productId)
                    ->where('status', 'active')
                    ->get()
                    ->first(function ($variant) use ($attributes) {
                        if (!$variant->attribute_values) {
                            return false;
                        }

                        foreach ($attributes as $attributeId => $value) {
                            if (!isset($variant->attribute_values[$attributeId]) || 
                                $variant->attribute_values[$attributeId] != $value) {
                                return false;
                            }
                        }

                        return true;
                    });
    }

    /**
     * Lấy available attributes cho product
     */
    public static function getAvailableAttributes($productId): array
    {
        $variants = static::where('product_id', $productId)
                         ->where('status', 'active')
                         ->get();

        $availableAttributes = [];

        foreach ($variants as $variant) {
            if (!$variant->attribute_values) {
                continue;
            }

            foreach ($variant->attribute_values as $attributeId => $value) {
                if (!isset($availableAttributes[$attributeId])) {
                    $availableAttributes[$attributeId] = [];
                }

                if (!in_array($value, $availableAttributes[$attributeId])) {
                    $availableAttributes[$attributeId][] = $value;
                }
            }
        }

        return $availableAttributes;
    }
}
