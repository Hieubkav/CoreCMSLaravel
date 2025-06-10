<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'product_options',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'product_options' => 'array',
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
     * Scope cho user cart
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope cho session cart
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope cho current user hoặc session
     */
    public function scopeForCurrentUser($query)
    {
        if (auth()->check()) {
            return $query->where('user_id', auth()->id());
        }

        return $query->where('session_id', session()->getId());
    }

    /**
     * Lấy total price cho cart item
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Lấy current price (có thể khác với price lúc add to cart)
     */
    public function getCurrentPriceAttribute()
    {
        if ($this->variant) {
            return $this->variant->display_price;
        }

        return $this->product->display_price;
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
     * Kiểm tra có đủ stock không
     */
    public function hasEnoughStock(): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->variant) {
            return $this->variant->isInStock() && 
                   $this->variant->stock_quantity >= $this->quantity;
        }

        return $this->product->isInStock() && 
               $this->product->stock_quantity >= $this->quantity;
    }

    /**
     * Lấy max quantity có thể add
     */
    public function getMaxQuantityAttribute(): int
    {
        if ($this->variant) {
            return $this->variant->stock_quantity;
        }

        return $this->product->stock_quantity;
    }

    /**
     * Update quantity
     */
    public function updateQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->delete();
        }

        if ($quantity > $this->max_quantity) {
            $quantity = $this->max_quantity;
        }

        $this->quantity = $quantity;
        return $this->save();
    }

    /**
     * Add to cart (static method)
     */
    public static function addItem(
        int $productId, 
        int $quantity = 1, 
        ?int $variantId = null, 
        ?array $options = null
    ): self {
        $product = Product::findOrFail($productId);
        $variant = $variantId ? ProductVariant::findOrFail($variantId) : null;

        // Determine price
        $price = $variant ? $variant->display_price : $product->display_price;

        // Check for existing cart item
        $cartQuery = static::where('product_id', $productId);

        if (auth()->check()) {
            $cartQuery->where('user_id', auth()->id());
        } else {
            $cartQuery->where('session_id', session()->getId());
        }

        if ($variantId) {
            $cartQuery->where('variant_id', $variantId);
        }

        $existingItem = $cartQuery->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->save();
            return $existingItem;
        }

        // Create new cart item
        return static::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : session()->getId(),
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
            'price' => $price,
            'product_options' => $options,
        ]);
    }

    /**
     * Get cart total
     */
    public static function getCartTotal(): array
    {
        $items = static::forCurrentUser()->with(['product', 'variant'])->get();

        $subtotal = $items->sum('total_price');
        $itemCount = $items->sum('quantity');

        return [
            'items' => $items,
            'item_count' => $itemCount,
            'subtotal' => $subtotal,
            'total' => $subtotal, // Will be calculated with taxes, shipping, etc.
        ];
    }

    /**
     * Clear cart
     */
    public static function clearCart(): void
    {
        static::forCurrentUser()->delete();
    }

    /**
     * Merge guest cart with user cart
     */
    public static function mergeGuestCart(string $sessionId, int $userId): void
    {
        $guestItems = static::where('session_id', $sessionId)->get();

        foreach ($guestItems as $guestItem) {
            $existingItem = static::where('user_id', $userId)
                                 ->where('product_id', $guestItem->product_id)
                                 ->where('variant_id', $guestItem->variant_id)
                                 ->first();

            if ($existingItem) {
                $existingItem->quantity += $guestItem->quantity;
                $existingItem->save();
            } else {
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }

        // Delete any remaining guest items
        static::where('session_id', $sessionId)->delete();
    }

    /**
     * Clean old guest carts
     */
    public static function cleanOldGuestCarts(int $days = 7): void
    {
        static::whereNotNull('session_id')
             ->where('created_at', '<', now()->subDays($days))
             ->delete();
    }
}
