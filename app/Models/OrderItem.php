<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'product_sku',
        'product_description',
        'product_image',
        'variant_name',
        'variant_attributes',
        'quantity',
        'unit_price',
        'total_price',
        'product_options',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'variant_attributes' => 'array',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'product_options' => 'array',
    ];

    /**
     * Quan hệ với order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Quan hệ với product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với variant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Lấy product name đầy đủ
     */
    public function getFullProductNameAttribute(): string
    {
        $name = $this->product_name;

        if ($this->variant_name) {
            $name .= ' - ' . $this->variant_name;
        }

        return $name;
    }

    /**
     * Lấy product image URL
     */
    public function getProductImageUrlAttribute(): string
    {
        if ($this->product_image) {
            return \Storage::url($this->product_image);
        }

        return asset('images/placeholder-product.jpg');
    }

    /**
     * Lấy variant attributes formatted
     */
    public function getFormattedVariantAttributesAttribute(): string
    {
        if (!$this->variant_attributes) {
            return '';
        }

        $formatted = [];
        foreach ($this->variant_attributes as $attribute => $value) {
            $formatted[] = $attribute . ': ' . $value;
        }

        return implode(', ', $formatted);
    }

    /**
     * Kiểm tra có thể review không
     */
    public function canBeReviewed(): bool
    {
        // Chỉ có thể review khi order đã delivered
        if ($this->order->status !== 'delivered') {
            return false;
        }

        // Kiểm tra đã review chưa
        return !ProductReview::where('order_id', $this->order_id)
                            ->where('product_id', $this->product_id)
                            ->where('user_id', $this->order->user_id)
                            ->exists();
    }

    /**
     * Lấy review nếu có
     */
    public function getReview()
    {
        return ProductReview::where('order_id', $this->order_id)
                          ->where('product_id', $this->product_id)
                          ->where('user_id', $this->order->user_id)
                          ->first();
    }

    /**
     * Create order item from cart item
     */
    public static function createFromCartItem(Cart $cartItem, Order $order): self
    {
        $product = $cartItem->product;
        $variant = $cartItem->variant;

        return static::create([
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'variant_id' => $cartItem->variant_id,
            'product_name' => $product->name,
            'product_sku' => $variant ? $variant->sku : $product->sku,
            'product_description' => $product->short_description,
            'product_image' => $variant && $variant->image ? $variant->image : $product->featured_image,
            'variant_name' => $variant ? $variant->name : null,
            'variant_attributes' => $variant ? $variant->formatted_attributes : null,
            'quantity' => $cartItem->quantity,
            'unit_price' => $cartItem->price,
            'total_price' => $cartItem->total_price,
            'product_options' => $cartItem->product_options,
        ]);
    }

    /**
     * Update stock after order creation
     */
    public function updateStock(): void
    {
        if ($this->variant_id) {
            $variant = ProductVariant::find($this->variant_id);
            if ($variant && $variant->manage_stock) {
                $oldStock = $variant->stock_quantity;
                $variant->decrement('stock_quantity', $this->quantity);
                $newStock = $variant->stock_quantity;

                // Update in_stock status
                if ($newStock <= 0) {
                    $variant->update(['in_stock' => false]);
                }

                // Log inventory change
                InventoryLog::create([
                    'product_id' => $this->product_id,
                    'variant_id' => $this->variant_id,
                    'order_id' => $this->order_id,
                    'type' => 'sale',
                    'quantity_before' => $oldStock,
                    'quantity_change' => -$this->quantity,
                    'quantity_after' => $newStock,
                    'reference' => $this->order->order_number,
                    'notes' => 'Stock reduced due to order',
                ]);
            }
        } else {
            $product = Product::find($this->product_id);
            if ($product && $product->manage_stock) {
                $oldStock = $product->stock_quantity;
                $product->decrement('stock_quantity', $this->quantity);
                $newStock = $product->stock_quantity;

                // Update in_stock status
                if ($newStock <= 0) {
                    $product->update(['in_stock' => false]);
                }

                // Log inventory change
                InventoryLog::create([
                    'product_id' => $this->product_id,
                    'variant_id' => null,
                    'order_id' => $this->order_id,
                    'type' => 'sale',
                    'quantity_before' => $oldStock,
                    'quantity_change' => -$this->quantity,
                    'quantity_after' => $newStock,
                    'reference' => $this->order->order_number,
                    'notes' => 'Stock reduced due to order',
                ]);
            }
        }

        // Update product order count
        Product::where('id', $this->product_id)->increment('order_count');
    }
}
