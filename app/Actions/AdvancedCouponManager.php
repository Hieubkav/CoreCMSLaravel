<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class AdvancedCouponManager
{
    use AsAction;

    /**
     * Advanced coupon management system
     */
    public function handle(string $action, array $params = []): array
    {
        return match ($action) {
            'validate_coupon' => $this->validateCoupon($params),
            'apply_coupon' => $this->applyCoupon($params),
            'auto_apply_coupons' => $this->autoApplyCoupons($params),
            'generate_coupon' => $this->generateCoupon($params),
            'bulk_generate' => $this->bulkGenerateCoupons($params),
            'usage_analytics' => $this->usageAnalytics($params),
            'performance_report' => $this->performanceReport($params),
            'smart_recommendations' => $this->smartRecommendations($params),
            'fraud_detection' => $this->fraudDetection($params),
            'expiry_management' => $this->expiryManagement($params),
            default => throw new \InvalidArgumentException("Unsupported action: {$action}")
        };
    }

    /**
     * Validate coupon với complex rules
     */
    private function validateCoupon(array $params): array
    {
        $code = $params['code'];
        $userId = $params['user_id'] ?? null;
        $cartItems = $params['cart_items'] ?? [];
        $totalAmount = $params['total_amount'] ?? 0;

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'error' => 'Mã giảm giá không tồn tại',
                'error_code' => 'COUPON_NOT_FOUND',
            ];
        }

        // Check basic validity
        $basicValidation = $this->checkBasicValidity($coupon);
        if (!$basicValidation['valid']) {
            return $basicValidation;
        }

        // Check usage limits
        $usageValidation = $this->checkUsageLimits($coupon, $userId);
        if (!$usageValidation['valid']) {
            return $usageValidation;
        }

        // Check conditions
        $conditionValidation = $this->checkConditions($coupon, $cartItems, $totalAmount, $userId);
        if (!$conditionValidation['valid']) {
            return $conditionValidation;
        }

        // Calculate discount
        $discount = $this->calculateDiscount($coupon, $cartItems, $totalAmount);

        return [
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ],
            'discount' => $discount,
            'message' => "Mã giảm giá hợp lệ. Bạn được giảm " . number_format($discount['amount'], 0, ',', '.') . 'đ',
        ];
    }

    /**
     * Apply coupon to cart/order
     */
    private function applyCoupon(array $params): array
    {
        $validation = $this->validateCoupon($params);

        if (!$validation['valid']) {
            return $validation;
        }

        $coupon = Coupon::where('code', $params['code'])->first();
        $userId = $params['user_id'] ?? null;
        $orderId = $params['order_id'] ?? null;

        try {
            DB::beginTransaction();

            // Record usage
            $usage = $coupon->usages()->create([
                'user_id' => $userId,
                'order_id' => $orderId,
                'discount_amount' => $validation['discount']['amount'],
                'used_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Update coupon usage count
            $coupon->increment('used_count');

            DB::commit();

            Log::info("Coupon applied successfully", [
                'coupon_code' => $coupon->code,
                'user_id' => $userId,
                'order_id' => $orderId,
                'discount_amount' => $validation['discount']['amount'],
            ]);

            return array_merge($validation, [
                'applied' => true,
                'usage_id' => $usage->id,
                'applied_at' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Failed to apply coupon", [
                'coupon_code' => $coupon->code,
                'error' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'error' => 'Không thể áp dụng mã giảm giá. Vui lòng thử lại.',
                'error_code' => 'APPLICATION_FAILED',
            ];
        }
    }

    /**
     * Tự động áp dụng coupon phù hợp nhất
     */
    private function autoApplyCoupons(array $params): array
    {
        $userId = $params['user_id'] ?? null;
        $cartItems = $params['cart_items'] ?? [];
        $totalAmount = $params['total_amount'] ?? 0;

        // Get applicable coupons
        $applicableCoupons = $this->getApplicableCoupons($userId, $cartItems, $totalAmount);

        if ($applicableCoupons->isEmpty()) {
            return [
                'auto_applied' => false,
                'message' => 'Không có mã giảm giá nào phù hợp',
                'suggestions' => $this->getSuggestions($userId, $cartItems, $totalAmount),
            ];
        }

        // Find best coupon (highest discount)
        $bestCoupon = $applicableCoupons->sortByDesc(function ($coupon) use ($cartItems, $totalAmount) {
            $discount = $this->calculateDiscount($coupon, $cartItems, $totalAmount);
            return $discount['amount'];
        })->first();

        $discount = $this->calculateDiscount($bestCoupon, $cartItems, $totalAmount);

        return [
            'auto_applied' => true,
            'coupon' => [
                'id' => $bestCoupon->id,
                'code' => $bestCoupon->code,
                'name' => $bestCoupon->name,
                'type' => $bestCoupon->type,
            ],
            'discount' => $discount,
            'alternatives' => $applicableCoupons->take(3)->map(function ($coupon) use ($cartItems, $totalAmount) {
                $discount = $this->calculateDiscount($coupon, $cartItems, $totalAmount);
                return [
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'discount_amount' => $discount['amount'],
                ];
            }),
            'message' => "Tự động áp dụng mã giảm giá tốt nhất: {$bestCoupon->code}",
        ];
    }

    /**
     * Generate new coupon
     */
    private function generateCoupon(array $params): array
    {
        $type = $params['type'] ?? 'percentage'; // percentage, fixed_amount, free_shipping
        $value = $params['value'];
        $name = $params['name'] ?? 'Generated Coupon';
        $prefix = $params['prefix'] ?? 'GEN';
        $length = $params['length'] ?? 8;
        $expiryDays = $params['expiry_days'] ?? 30;
        $usageLimit = $params['usage_limit'] ?? 1;
        $conditions = $params['conditions'] ?? [];

        $code = $this->generateUniqueCode($prefix, $length);

        $coupon = Coupon::create([
            'code' => $code,
            'name' => $name,
            'type' => $type,
            'value' => $value,
            'minimum_amount' => $conditions['minimum_amount'] ?? 0,
            'maximum_discount' => $conditions['maximum_discount'] ?? null,
            'usage_limit' => $usageLimit,
            'usage_limit_per_user' => $conditions['usage_limit_per_user'] ?? 1,
            'valid_from' => now(),
            'valid_until' => now()->addDays($expiryDays),
            'status' => 'active',
            'conditions' => $conditions,
            'auto_generated' => true,
        ]);

        return [
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'valid_until' => $coupon->valid_until->toISOString(),
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Bulk generate coupons
     */
    private function bulkGenerateCoupons(array $params): array
    {
        $count = $params['count'] ?? 10;
        $type = $params['type'] ?? 'percentage';
        $value = $params['value'];
        $prefix = $params['prefix'] ?? 'BULK';
        $expiryDays = $params['expiry_days'] ?? 30;
        $conditions = $params['conditions'] ?? [];

        $coupons = [];
        $errors = [];

        for ($i = 0; $i < $count; $i++) {
            try {
                $code = $this->generateUniqueCode($prefix, 8);

                $coupon = Coupon::create([
                    'code' => $code,
                    'name' => "Bulk Generated Coupon #" . ($i + 1),
                    'type' => $type,
                    'value' => $value,
                    'minimum_amount' => $conditions['minimum_amount'] ?? 0,
                    'maximum_discount' => $conditions['maximum_discount'] ?? null,
                    'usage_limit' => 1,
                    'usage_limit_per_user' => 1,
                    'valid_from' => now(),
                    'valid_until' => now()->addDays($expiryDays),
                    'status' => 'active',
                    'conditions' => $conditions,
                    'auto_generated' => true,
                ]);

                $coupons[] = [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                ];

            } catch (\Exception $e) {
                $errors[] = "Failed to generate coupon #" . ($i + 1) . ": " . $e->getMessage();
            }
        }

        return [
            'success_count' => count($coupons),
            'error_count' => count($errors),
            'coupons' => $coupons,
            'errors' => $errors,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Usage analytics
     */
    private function usageAnalytics(array $params): array
    {
        $couponId = $params['coupon_id'] ?? null;
        $days = $params['days'] ?? 30;
        // $groupBy = $params['group_by'] ?? 'day'; // day, week, month - for future use

        $query = DB::table('coupon_usages')
                   ->join('coupons', 'coupon_usages.coupon_id', '=', 'coupons.id')
                   ->where('coupon_usages.created_at', '>=', now()->subDays($days));

        if ($couponId) {
            $query->where('coupon_usages.coupon_id', $couponId);
        }

        $usageData = $query->selectRaw('
                                DATE(coupon_usages.created_at) as date,
                                COUNT(*) as usage_count,
                                SUM(coupon_usages.discount_amount) as total_discount,
                                AVG(coupon_usages.discount_amount) as avg_discount,
                                COUNT(DISTINCT coupon_usages.user_id) as unique_users
                            ')
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        $topCoupons = DB::table('coupon_usages')
                        ->join('coupons', 'coupon_usages.coupon_id', '=', 'coupons.id')
                        ->where('coupon_usages.created_at', '>=', now()->subDays($days))
                        ->selectRaw('
                            coupons.id,
                            coupons.code,
                            coupons.name,
                            COUNT(*) as usage_count,
                            SUM(coupon_usages.discount_amount) as total_discount
                        ')
                        ->groupBy('coupons.id', 'coupons.code', 'coupons.name')
                        ->orderByDesc('usage_count')
                        ->limit(10)
                        ->get();

        $summary = [
            'total_usages' => $usageData->sum('usage_count'),
            'total_discount_given' => $usageData->sum('total_discount'),
            'average_discount' => $usageData->avg('avg_discount'),
            'unique_users' => $usageData->sum('unique_users'),
            'most_popular_day' => $usageData->sortByDesc('usage_count')->first()?->date,
        ];

        return [
            'period_days' => $days,
            'summary' => $summary,
            'daily_usage' => $usageData->toArray(),
            'top_coupons' => $topCoupons->toArray(),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Performance report
     */
    private function performanceReport(array $params): array
    {
        $days = $params['days'] ?? 30;

        $coupons = Coupon::with(['usages' => function ($query) use ($days) {
                              $query->where('created_at', '>=', now()->subDays($days));
                          }])
                          ->where('created_at', '>=', now()->subDays($days * 2))
                          ->get();

        $performance = $coupons->map(function ($coupon) {
            $usages = $coupon->usages;
            $totalDiscount = $usages->sum('discount_amount');
            $usageCount = $usages->count();
            $uniqueUsers = $usages->unique('user_id')->count();

            $conversionRate = $coupon->usage_limit > 0 ? ($usageCount / $coupon->usage_limit) * 100 : 0;
            $avgDiscountPerUse = $usageCount > 0 ? $totalDiscount / $usageCount : 0;

            return [
                'coupon_id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'usage_count' => $usageCount,
                'usage_limit' => $coupon->usage_limit,
                'conversion_rate' => round($conversionRate, 2),
                'total_discount_given' => $totalDiscount,
                'avg_discount_per_use' => round($avgDiscountPerUse, 2),
                'unique_users' => $uniqueUsers,
                'roi' => $this->calculateCouponROI($coupon, $usages),
                'status' => $coupon->status,
                'expires_at' => $coupon->valid_until?->toISOString(),
            ];
        })->sortByDesc('usage_count');

        return [
            'analysis_period_days' => $days,
            'total_coupons' => $coupons->count(),
            'active_coupons' => $coupons->where('status', 'active')->count(),
            'expired_coupons' => $coupons->where('valid_until', '<', now())->count(),
            'performance_data' => $performance->values()->toArray(),
            'top_performers' => $performance->take(5)->values()->toArray(),
            'generated_at' => now()->toISOString(),
        ];
    }

    // Helper Methods

    private function checkBasicValidity(Coupon $coupon): array
    {
        if ($coupon->status !== 'active') {
            return [
                'valid' => false,
                'error' => 'Mã giảm giá không còn hoạt động',
                'error_code' => 'COUPON_INACTIVE',
            ];
        }

        if ($coupon->valid_from && $coupon->valid_from->isFuture()) {
            return [
                'valid' => false,
                'error' => 'Mã giảm giá chưa có hiệu lực',
                'error_code' => 'COUPON_NOT_STARTED',
            ];
        }

        if ($coupon->valid_until && $coupon->valid_until->isPast()) {
            return [
                'valid' => false,
                'error' => 'Mã giảm giá đã hết hạn',
                'error_code' => 'COUPON_EXPIRED',
            ];
        }

        return ['valid' => true];
    }

    private function checkUsageLimits(Coupon $coupon, ?int $userId): array
    {
        // Check global usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'error' => 'Mã giảm giá đã hết lượt sử dụng',
                'error_code' => 'USAGE_LIMIT_EXCEEDED',
            ];
        }

        // Check per-user usage limit
        if ($userId && $coupon->usage_limit_per_user) {
            $userUsageCount = $coupon->usages()->where('user_id', $userId)->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return [
                    'valid' => false,
                    'error' => 'Bạn đã sử dụng hết lượt cho mã giảm giá này',
                    'error_code' => 'USER_LIMIT_EXCEEDED',
                ];
            }
        }

        return ['valid' => true];
    }

    private function checkConditions(Coupon $coupon, array $cartItems, float $totalAmount, ?int $userId): array
    {
        $conditions = $coupon->conditions ?? [];

        // Minimum amount
        if ($coupon->minimum_amount && $totalAmount < $coupon->minimum_amount) {
            return [
                'valid' => false,
                'error' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->minimum_amount, 0, ',', '.') . 'đ',
                'error_code' => 'MINIMUM_AMOUNT_NOT_MET',
            ];
        }

        // Product restrictions
        if (isset($conditions['allowed_products']) && !empty($conditions['allowed_products'])) {
            $allowedProducts = $conditions['allowed_products'];
            $hasAllowedProduct = collect($cartItems)->contains(function ($item) use ($allowedProducts) {
                return in_array($item['product_id'], $allowedProducts);
            });

            if (!$hasAllowedProduct) {
                return [
                    'valid' => false,
                    'error' => 'Mã giảm giá không áp dụng cho sản phẩm trong giỏ hàng',
                    'error_code' => 'PRODUCT_NOT_ALLOWED',
                ];
            }
        }

        // Category restrictions
        if (isset($conditions['allowed_categories']) && !empty($conditions['allowed_categories'])) {
            $allowedCategories = $conditions['allowed_categories'];
            $hasAllowedCategory = collect($cartItems)->contains(function ($item) use ($allowedCategories) {
                $product = Product::find($item['product_id']);
                return $product && in_array($product->category_id, $allowedCategories);
            });

            if (!$hasAllowedCategory) {
                return [
                    'valid' => false,
                    'error' => 'Mã giảm giá không áp dụng cho danh mục sản phẩm trong giỏ hàng',
                    'error_code' => 'CATEGORY_NOT_ALLOWED',
                ];
            }
        }

        // Customer group restrictions
        if ($userId && isset($conditions['allowed_customer_groups'])) {
            $user = User::find($userId);
            $userGroup = $user->customer_group ?? 'retail';

            if (!in_array($userGroup, $conditions['allowed_customer_groups'])) {
                return [
                    'valid' => false,
                    'error' => 'Mã giảm giá không áp dụng cho nhóm khách hàng của bạn',
                    'error_code' => 'CUSTOMER_GROUP_NOT_ALLOWED',
                ];
            }
        }

        // First order only
        if (isset($conditions['first_order_only']) && $conditions['first_order_only'] && $userId) {
            $orderCount = Order::where('user_id', $userId)->where('status', 'completed')->count();

            if ($orderCount > 0) {
                return [
                    'valid' => false,
                    'error' => 'Mã giảm giá chỉ áp dụng cho đơn hàng đầu tiên',
                    'error_code' => 'NOT_FIRST_ORDER',
                ];
            }
        }

        return ['valid' => true];
    }

    private function calculateDiscount(Coupon $coupon, array $cartItems, float $totalAmount): array
    {
        $discountAmount = 0;
        $applicableAmount = $totalAmount;

        // Calculate applicable amount if product/category restrictions exist
        $conditions = $coupon->conditions ?? [];
        if (isset($conditions['allowed_products']) || isset($conditions['allowed_categories'])) {
            $applicableAmount = $this->calculateApplicableAmount($cartItems, $conditions);
        }

        switch ($coupon->type) {
            case 'percentage':
                $discountAmount = $applicableAmount * ($coupon->value / 100);
                break;

            case 'fixed_amount':
                $discountAmount = min($coupon->value, $applicableAmount);
                break;

            case 'free_shipping':
                $discountAmount = $this->calculateShippingCost($cartItems);
                break;
        }

        // Apply maximum discount limit
        if ($coupon->maximum_discount) {
            $discountAmount = min($discountAmount, $coupon->maximum_discount);
        }

        return [
            'amount' => round($discountAmount, 2),
            'type' => $coupon->type,
            'applicable_amount' => $applicableAmount,
            'percentage' => $applicableAmount > 0 ? round(($discountAmount / $applicableAmount) * 100, 2) : 0,
        ];
    }

    private function calculateApplicableAmount(array $cartItems, array $conditions): float
    {
        $applicableAmount = 0;

        foreach ($cartItems as $item) {
            $isApplicable = true;

            // Check product restrictions
            if (isset($conditions['allowed_products']) && !empty($conditions['allowed_products'])) {
                $isApplicable = in_array($item['product_id'], $conditions['allowed_products']);
            }

            // Check category restrictions
            if ($isApplicable && isset($conditions['allowed_categories']) && !empty($conditions['allowed_categories'])) {
                $product = Product::find($item['product_id']);
                $isApplicable = $product && in_array($product->category_id, $conditions['allowed_categories']);
            }

            if ($isApplicable) {
                $applicableAmount += $item['price'] * $item['quantity'];
            }
        }

        return $applicableAmount;
    }

    private function calculateShippingCost(array $cartItems): float
    {
        // This would calculate actual shipping cost
        // For now, return a default shipping cost
        return 30000; // 30k VND default shipping
    }

    private function getApplicableCoupons(?int $userId, array $cartItems, float $totalAmount): Collection
    {
        $query = Coupon::where('status', 'active')
                      ->where(function ($q) {
                          $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                      })
                      ->where(function ($q) {
                          $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                      })
                      ->where(function ($q) {
                          $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
                      });

        $coupons = $query->get();

        return $coupons->filter(function ($coupon) use ($userId, $cartItems, $totalAmount) {
            $validation = $this->validateCoupon([
                'code' => $coupon->code,
                'user_id' => $userId,
                'cart_items' => $cartItems,
                'total_amount' => $totalAmount,
            ]);

            return $validation['valid'];
        });
    }

    private function getSuggestions(?int $userId, array $cartItems, float $totalAmount): array
    {
        $suggestions = [];

        // Find coupons that would be applicable with higher cart value
        $nearMissCoupons = Coupon::where('status', 'active')
                                ->where('minimum_amount', '>', $totalAmount)
                                ->where('minimum_amount', '<=', $totalAmount * 1.5)
                                ->limit(3)
                                ->get();

        foreach ($nearMissCoupons as $coupon) {
            $needed = $coupon->minimum_amount - $totalAmount;
            $suggestions[] = [
                'type' => 'add_more',
                'message' => "Thêm " . number_format($needed, 0, ',', '.') . "đ để sử dụng mã {$coupon->code}",
                'coupon_code' => $coupon->code,
                'amount_needed' => $needed,
            ];
        }

        return $suggestions;
    }

    private function generateUniqueCode(string $prefix, int $length): string
    {
        do {
            $code = $prefix . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    private function calculateCouponROI(Coupon $coupon, Collection $usages): array
    {
        $totalDiscount = $usages->sum('discount_amount');
        $orderIds = $usages->pluck('order_id')->filter();

        if ($orderIds->isEmpty()) {
            return [
                'roi_percentage' => 0,
                'revenue_generated' => 0,
                'cost' => $totalDiscount,
            ];
        }

        $orders = Order::whereIn('id', $orderIds)->get();
        $totalRevenue = $orders->sum('total_amount');
        $revenueWithoutDiscount = $totalRevenue + $totalDiscount;

        $roi = $totalDiscount > 0 ? (($totalRevenue - $totalDiscount) / $totalDiscount) * 100 : 0;

        return [
            'roi_percentage' => round($roi, 2),
            'revenue_generated' => $totalRevenue,
            'cost' => $totalDiscount,
            'orders_influenced' => $orders->count(),
        ];
    }

    // Additional methods for smart recommendations, fraud detection, expiry management would go here...

    private function smartRecommendations(array $params): array
    {
        // Implementation for AI-powered coupon recommendations
        return ['message' => 'Smart recommendations feature coming soon'];
    }

    private function fraudDetection(array $params): array
    {
        // Implementation for fraud detection
        return ['message' => 'Fraud detection feature coming soon'];
    }

    private function expiryManagement(array $params): array
    {
        // Implementation for expiry management
        return ['message' => 'Expiry management feature coming soon'];
    }
}
