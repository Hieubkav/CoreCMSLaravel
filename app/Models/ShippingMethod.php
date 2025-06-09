<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cost_type',
        'cost',
        'free_shipping_threshold',
        'cost_per_kg',
        'base_cost',
        'min_delivery_days',
        'max_delivery_days',
        'delivery_time_text',
        'allowed_countries',
        'excluded_countries',
        'min_order_amount',
        'max_order_amount',
        'max_weight',
        'requires_address',
        'is_pickup',
        'pickup_address',
        'status',
        'order',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'cost_per_kg' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'min_delivery_days' => 'integer',
        'max_delivery_days' => 'integer',
        'allowed_countries' => 'array',
        'excluded_countries' => 'array',
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'requires_address' => 'boolean',
        'is_pickup' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope cho shipping methods active
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
     * Lấy danh sách cost types
     */
    public static function getCostTypes(): array
    {
        return [
            'fixed' => 'Giá cố định',
            'weight_based' => 'Theo trọng lượng',
            'free' => 'Miễn phí',
            'calculated' => 'Tính toán động',
        ];
    }

    /**
     * Lấy cost type label
     */
    public function getCostTypeLabelAttribute(): string
    {
        return static::getCostTypes()[$this->cost_type] ?? $this->cost_type;
    }

    /**
     * Lấy delivery time formatted
     */
    public function getDeliveryTimeAttribute(): string
    {
        if ($this->delivery_time_text) {
            return $this->delivery_time_text;
        }

        if ($this->min_delivery_days && $this->max_delivery_days) {
            if ($this->min_delivery_days === $this->max_delivery_days) {
                return $this->min_delivery_days . ' ngày';
            }
            return $this->min_delivery_days . '-' . $this->max_delivery_days . ' ngày';
        }

        if ($this->min_delivery_days) {
            return 'Từ ' . $this->min_delivery_days . ' ngày';
        }

        if ($this->max_delivery_days) {
            return 'Tối đa ' . $this->max_delivery_days . ' ngày';
        }

        return 'Liên hệ để biết thời gian giao hàng';
    }

    /**
     * Kiểm tra có áp dụng được cho order không
     */
    public function isAvailableForOrder(array $orderData): bool
    {
        // Check order amount
        $orderTotal = $orderData['total'] ?? 0;
        
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            return false;
        }

        if ($this->max_order_amount && $orderTotal > $this->max_order_amount) {
            return false;
        }

        // Check weight
        $totalWeight = $orderData['total_weight'] ?? 0;
        
        if ($this->max_weight && $totalWeight > $this->max_weight) {
            return false;
        }

        // Check country
        $country = $orderData['shipping_country'] ?? $orderData['billing_country'] ?? '';
        
        if ($this->allowed_countries && !in_array($country, $this->allowed_countries)) {
            return false;
        }

        if ($this->excluded_countries && in_array($country, $this->excluded_countries)) {
            return false;
        }

        return true;
    }

    /**
     * Tính shipping cost
     */
    public function calculateCost(array $orderData): float
    {
        $orderTotal = $orderData['total'] ?? 0;
        $totalWeight = $orderData['total_weight'] ?? 0;

        switch ($this->cost_type) {
            case 'free':
                return 0;

            case 'fixed':
                // Check free shipping threshold
                if ($this->free_shipping_threshold && $orderTotal >= $this->free_shipping_threshold) {
                    return 0;
                }
                return $this->cost;

            case 'weight_based':
                $cost = $this->base_cost ?: 0;
                $cost += $totalWeight * ($this->cost_per_kg ?: 0);
                
                // Check free shipping threshold
                if ($this->free_shipping_threshold && $orderTotal >= $this->free_shipping_threshold) {
                    return 0;
                }
                
                return $cost;

            case 'calculated':
                // This would integrate with external shipping APIs
                return $this->calculateDynamicCost($orderData);

            default:
                return $this->cost;
        }
    }

    /**
     * Tính dynamic shipping cost (placeholder for API integration)
     */
    private function calculateDynamicCost(array $orderData): float
    {
        // This would integrate with shipping providers like:
        // - Giao Hàng Nhanh (GHN)
        // - Giao Hàng Tiết Kiệm (GHTK)
        // - ViettelPost
        // - J&T Express
        
        // For now, return base cost
        return $this->cost;
    }

    /**
     * Get available shipping methods for order
     */
    public static function getAvailableForOrder(array $orderData): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
                    ->ordered()
                    ->get()
                    ->filter(function ($method) use ($orderData) {
                        return $method->isAvailableForOrder($orderData);
                    });
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
