<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaxSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'rate',
        'is_inclusive',
        'applicable_countries',
        'applicable_states',
        'applicable_cities',
        'postal_codes',
        'applicable_product_categories',
        'excluded_product_categories',
        'applicable_products',
        'excluded_products',
        'customer_type',
        'applicable_user_groups',
        'starts_at',
        'expires_at',
        'priority',
        'status',
        'order',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_inclusive' => 'boolean',
        'applicable_countries' => 'array',
        'applicable_states' => 'array',
        'applicable_cities' => 'array',
        'postal_codes' => 'array',
        'applicable_product_categories' => 'array',
        'excluded_product_categories' => 'array',
        'applicable_products' => 'array',
        'excluded_products' => 'array',
        'applicable_user_groups' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'priority' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Scope cho tax settings active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope sắp xếp theo priority và order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('order')->orderBy('name');
    }

    /**
     * Scope cho tax settings valid (trong thời gian hiệu lực)
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
     * Lấy danh sách tax types
     */
    public static function getTaxTypes(): array
    {
        return [
            'percentage' => 'Phần trăm',
            'fixed' => 'Số tiền cố định',
            'compound' => 'Thuế kép',
        ];
    }

    /**
     * Lấy danh sách customer types
     */
    public static function getCustomerTypes(): array
    {
        return [
            'all' => 'Tất cả khách hàng',
            'individual' => 'Cá nhân',
            'business' => 'Doanh nghiệp',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getTaxTypes()[$this->type] ?? $this->type;
    }

    /**
     * Lấy customer type label
     */
    public function getCustomerTypeLabelAttribute(): string
    {
        return static::getCustomerTypes()[$this->customer_type] ?? $this->customer_type;
    }

    /**
     * Kiểm tra tax setting có valid không
     */
    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có áp dụng cho địa chỉ không
     */
    public function isApplicableForAddress(array $address): bool
    {
        // Check country
        if ($this->applicable_countries && !in_array($address['country'] ?? '', $this->applicable_countries)) {
            return false;
        }

        // Check state
        if ($this->applicable_states && !in_array($address['state'] ?? '', $this->applicable_states)) {
            return false;
        }

        // Check city
        if ($this->applicable_cities && !in_array($address['city'] ?? '', $this->applicable_cities)) {
            return false;
        }

        // Check postal code
        if ($this->postal_codes && !$this->isPostalCodeApplicable($address['postcode'] ?? '')) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra postal code có áp dụng không
     */
    private function isPostalCodeApplicable(string $postcode): bool
    {
        if (!$this->postal_codes) {
            return true;
        }

        foreach ($this->postal_codes as $pattern) {
            // Support wildcards and ranges
            if (fnmatch($pattern, $postcode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kiểm tra có áp dụng cho sản phẩm không
     */
    public function isApplicableForProduct(int $productId, int $categoryId): bool
    {
        // Check excluded products first
        if ($this->excluded_products && in_array($productId, $this->excluded_products)) {
            return false;
        }

        // Check excluded categories
        if ($this->excluded_product_categories && in_array($categoryId, $this->excluded_product_categories)) {
            return false;
        }

        // Check applicable products
        if ($this->applicable_products && !in_array($productId, $this->applicable_products)) {
            return false;
        }

        // Check applicable categories
        if ($this->applicable_product_categories && !in_array($categoryId, $this->applicable_product_categories)) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra có áp dụng cho customer không
     */
    public function isApplicableForCustomer(?int $userId = null, string $customerType = 'individual'): bool
    {
        // Check customer type
        if ($this->customer_type !== 'all' && $this->customer_type !== $customerType) {
            return false;
        }

        // Check user groups (if user is logged in)
        if ($userId && $this->applicable_user_groups) {
            // This would need to be implemented based on your user group system
            // For now, return true
            return true;
        }

        return true;
    }

    /**
     * Tính tax amount
     */
    public function calculateTax(float $amount): float
    {
        switch ($this->type) {
            case 'percentage':
                return ($amount * $this->rate) / 100;
                
            case 'fixed':
                return $this->rate;
                
            case 'compound':
                // Compound tax is calculated on amount + other taxes
                // This would need additional logic to handle multiple taxes
                return ($amount * $this->rate) / 100;
                
            default:
                return 0;
        }
    }

    /**
     * Get applicable tax settings for order
     */
    public static function getApplicableForOrder(array $orderData): \Illuminate\Database\Eloquent\Collection
    {
        $address = [
            'country' => $orderData['billing_country'] ?? '',
            'state' => $orderData['billing_state'] ?? '',
            'city' => $orderData['billing_city'] ?? '',
            'postcode' => $orderData['billing_postcode'] ?? '',
        ];

        return static::valid()
                    ->ordered()
                    ->get()
                    ->filter(function ($taxSetting) use ($orderData, $address) {
                        return $taxSetting->isApplicableForAddress($address) &&
                               $taxSetting->isApplicableForCustomer(
                                   $orderData['user_id'] ?? null,
                                   $orderData['customer_type'] ?? 'individual'
                               );
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
