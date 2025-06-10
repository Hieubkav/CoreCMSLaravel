<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'value',
        'price_adjustment',
        'stock_adjustment',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'attribute_id' => 'integer',
        'price_adjustment' => 'decimal:2',
        'stock_adjustment' => 'integer',
    ];

    /**
     * Quan hệ với product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với attribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    /**
     * Lấy formatted value
     */
    public function getFormattedValueAttribute(): string
    {
        return $this->attribute ? $this->attribute->formatValue($this->value) : $this->value;
    }

    /**
     * Lấy adjusted price
     */
    public function getAdjustedPriceAttribute()
    {
        return $this->product->price + $this->price_adjustment;
    }

    /**
     * Lấy adjusted stock
     */
    public function getAdjustedStockAttribute(): int
    {
        return $this->product->stock_quantity + $this->stock_adjustment;
    }

    /**
     * Validate value theo attribute type
     */
    public function validateValue(): bool
    {
        if (!$this->attribute) {
            return false;
        }

        return $this->attribute->validateValue($this->value);
    }

    /**
     * Scope theo product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope theo attribute
     */
    public function scopeForAttribute($query, $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }
}
