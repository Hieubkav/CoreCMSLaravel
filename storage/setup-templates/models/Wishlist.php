<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'notes',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
    ];

    /**
     * Quan hệ với user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Scope cho user wishlist
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope cho current user
     */
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Lấy product name
     */
    public function getProductNameAttribute(): string
    {
        $name = $this->product->name;

        if ($this->variant) {
            $name .= ' - ' . $this->variant->attribute_display;
        }

        return $name;
    }

    /**
     * Lấy product image
     */
    public function getProductImageAttribute(): string
    {
        if ($this->variant && $this->variant->image) {
            return $this->variant->image_url;
        }

        return $this->product->featured_image_url;
    }

    /**
     * Lấy product price
     */
    public function getProductPriceAttribute()
    {
        if ($this->variant) {
            return $this->variant->display_price;
        }

        return $this->product->display_price;
    }

    /**
     * Kiểm tra product có còn available không
     */
    public function isAvailable(): bool
    {
        if (!$this->product || $this->product->status !== 'active') {
            return false;
        }

        if ($this->variant && $this->variant->status !== 'active') {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có in stock không
     */
    public function isInStock(): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->variant) {
            return $this->variant->isInStock();
        }

        return $this->product->isInStock();
    }

    /**
     * Add to wishlist (static method)
     */
    public static function addItem(int $productId, ?int $variantId = null, ?string $notes = null): ?self
    {
        if (!auth()->check()) {
            return null;
        }

        // Check if already exists
        $existing = static::where('user_id', auth()->id())
                         ->where('product_id', $productId)
                         ->where('variant_id', $variantId)
                         ->first();

        if ($existing) {
            // Update notes if provided
            if ($notes) {
                $existing->notes = $notes;
                $existing->save();
            }
            return $existing;
        }

        return static::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'variant_id' => $variantId,
            'notes' => $notes,
        ]);
    }

    /**
     * Remove from wishlist
     */
    public static function removeItem(int $productId, ?int $variantId = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return static::where('user_id', auth()->id())
                    ->where('product_id', $productId)
                    ->where('variant_id', $variantId)
                    ->delete() > 0;
    }

    /**
     * Check if item is in wishlist
     */
    public static function isInWishlist(int $productId, ?int $variantId = null): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return static::where('user_id', auth()->id())
                    ->where('product_id', $productId)
                    ->where('variant_id', $variantId)
                    ->exists();
    }

    /**
     * Get wishlist count for user
     */
    public static function getWishlistCount(?int $userId = null): int
    {
        $userId = $userId ?: auth()->id();

        if (!$userId) {
            return 0;
        }

        return static::where('user_id', $userId)->count();
    }

    /**
     * Move to cart
     */
    public function moveToCart(int $quantity = 1): bool
    {
        try {
            Cart::addItem(
                $this->product_id,
                $quantity,
                $this->variant_id
            );

            $this->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user's wishlist with products
     */
    public static function getUserWishlist(?int $userId = null)
    {
        $userId = $userId ?: auth()->id();

        if (!$userId) {
            return collect();
        }

        return static::where('user_id', $userId)
                    ->with(['product', 'variant'])
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}
