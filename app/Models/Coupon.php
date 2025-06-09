<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'applicable_products',
        'applicable_categories',
        'excluded_products',
        'excluded_categories',
        'applicable_users',
        'first_order_only',
        'status',
        'order',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'excluded_products' => 'array',
        'excluded_categories' => 'array',
        'applicable_users' => 'array',
        'first_order_only' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope cho coupons active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope cho coupons valid (trong thời gian hiệu lực)
     */
    public function scopeValid($query)
    {
        $now = now();
        
        return $query->where('status', 'active')
                    ->where(function ($q) use ($now) {
                        $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
                    });
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Lấy danh sách coupon types
     */
    public static function getCouponTypes(): array
    {
        return [
            'fixed' => 'Giảm giá cố định',
            'percentage' => 'Giảm giá theo phần trăm',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getCouponTypes()[$this->type] ?? $this->type;
    }

    /**
     * Kiểm tra coupon có valid không
     */
    public function isValid(): bool
    {
        // Check status
        if ($this->status !== 'active') {
            return false;
        }

        // Check date range
        $now = now();
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra coupon có áp dụng được cho user không
     */
    public function isValidForUser(?int $userId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if (!$userId) {
            $userId = auth()->id();
        }

        if (!$userId) {
            return false;
        }

        // Check user-specific restrictions
        if ($this->applicable_users && !in_array($userId, $this->applicable_users)) {
            return false;
        }

        // Check usage limit per user
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->getUserUsageCount($userId);
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        // Check first order only
        if ($this->first_order_only) {
            $hasOrders = Order::where('user_id', $userId)
                            ->where('status', '!=', 'cancelled')
                            ->exists();
            if ($hasOrders) {
                return false;
            }
        }

        return true;
    }

    /**
     * Kiểm tra coupon có áp dụng được cho cart không
     */
    public function isValidForCart(array $cartItems, float $subtotal): array
    {
        $result = [
            'valid' => false,
            'message' => '',
            'discount_amount' => 0,
        ];

        if (!$this->isValidForUser()) {
            $result['message'] = 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
            return $result;
        }

        // Check minimum amount
        if ($this->minimum_amount && $subtotal < $this->minimum_amount) {
            $result['message'] = 'Đơn hàng tối thiểu ' . number_format($this->minimum_amount) . 'đ để sử dụng mã này';
            return $result;
        }

        // Check applicable products/categories
        $applicableAmount = $this->getApplicableAmount($cartItems);
        
        if ($applicableAmount <= 0) {
            $result['message'] = 'Mã giảm giá không áp dụng cho sản phẩm trong giỏ hàng';
            return $result;
        }

        // Calculate discount
        $discountAmount = $this->calculateDiscount($applicableAmount);

        $result['valid'] = true;
        $result['discount_amount'] = $discountAmount;
        $result['message'] = 'Mã giảm giá hợp lệ';

        return $result;
    }

    /**
     * Tính số tiền áp dụng được coupon
     */
    private function getApplicableAmount(array $cartItems): float
    {
        $applicableAmount = 0;

        foreach ($cartItems as $item) {
            $isApplicable = true;

            // Check excluded products
            if ($this->excluded_products && in_array($item['product_id'], $this->excluded_products)) {
                $isApplicable = false;
            }

            // Check excluded categories
            if ($isApplicable && $this->excluded_categories) {
                $product = Product::find($item['product_id']);
                if ($product && in_array($product->category_id, $this->excluded_categories)) {
                    $isApplicable = false;
                }
            }

            // Check applicable products
            if ($isApplicable && $this->applicable_products && !in_array($item['product_id'], $this->applicable_products)) {
                $isApplicable = false;
            }

            // Check applicable categories
            if ($isApplicable && $this->applicable_categories) {
                $product = Product::find($item['product_id']);
                if (!$product || !in_array($product->category_id, $this->applicable_categories)) {
                    $isApplicable = false;
                }
            }

            if ($isApplicable) {
                $applicableAmount += $item['total_price'];
            }
        }

        return $applicableAmount;
    }

    /**
     * Tính discount amount
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'fixed') {
            $discount = min($this->value, $amount);
        } else {
            $discount = ($amount * $this->value) / 100;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount) {
            $discount = min($discount, $this->maximum_discount);
        }

        return round($discount, 2);
    }

    /**
     * Lấy số lần user đã sử dụng coupon
     */
    public function getUserUsageCount(int $userId): int
    {
        // This would need to be implemented based on how you track coupon usage
        // For now, returning 0 as placeholder
        return 0;
    }

    /**
     * Apply coupon (increment usage count)
     */
    public function applyCoupon(): void
    {
        $this->increment('used_count');

        // Update status if usage limit reached
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Find coupon by code
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', strtoupper($code))->first();
    }

    /**
     * Generate unique coupon code
     */
    public static function generateCode(int $length = 8): string
    {
        do {
            $code = strtoupper(\Str::random($length));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
