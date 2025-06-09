<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DynamicPricingEngine
{
    use AsAction;

    /**
     * Dynamic pricing engine
     */
    public function handle(string $action, array $params = []): array
    {
        return match ($action) {
            'calculate_price' => $this->calculateDynamicPrice($params),
            'bulk_price_update' => $this->bulkPriceUpdate($params),
            'time_based_pricing' => $this->timeBasedPricing($params),
            'quantity_discounts' => $this->quantityDiscounts($params),
            'customer_group_pricing' => $this->customerGroupPricing($params),
            'competitor_pricing' => $this->competitorPricing($params),
            'demand_based_pricing' => $this->demandBasedPricing($params),
            'seasonal_pricing' => $this->seasonalPricing($params),
            'inventory_based_pricing' => $this->inventoryBasedPricing($params),
            'bundle_pricing' => $this->bundlePricing($params),
            default => throw new \InvalidArgumentException("Unsupported action: {$action}")
        };
    }

    /**
     * Tính giá động cho sản phẩm
     */
    private function calculateDynamicPrice(array $params): array
    {
        $productId = $params['product_id'];
        $userId = $params['user_id'] ?? null;
        $quantity = $params['quantity'] ?? 1;
        $context = $params['context'] ?? 'web'; // web, mobile, api

        $product = Product::findOrFail($productId);
        $user = $userId ? User::find($userId) : null;

        $basePrice = $product->price;
        $finalPrice = $basePrice;
        $appliedRules = [];
        $discounts = [];

        // 1. Customer Group Pricing
        if ($user) {
            $groupDiscount = $this->getCustomerGroupDiscount($user, $product);
            if ($groupDiscount > 0) {
                $discountAmount = $basePrice * ($groupDiscount / 100);
                $finalPrice -= $discountAmount;
                $appliedRules[] = 'customer_group';
                $discounts[] = [
                    'type' => 'customer_group',
                    'description' => "Giảm giá nhóm khách hàng {$groupDiscount}%",
                    'amount' => $discountAmount,
                    'percentage' => $groupDiscount,
                ];
            }
        }

        // 2. Quantity Discounts
        $quantityDiscount = $this->getQuantityDiscount($product, $quantity);
        if ($quantityDiscount > 0) {
            $discountAmount = $finalPrice * ($quantityDiscount / 100);
            $finalPrice -= $discountAmount;
            $appliedRules[] = 'quantity_discount';
            $discounts[] = [
                'type' => 'quantity_discount',
                'description' => "Giảm giá số lượng {$quantityDiscount}% (mua {$quantity} sản phẩm)",
                'amount' => $discountAmount,
                'percentage' => $quantityDiscount,
            ];
        }

        // 3. Time-based Pricing
        $timeDiscount = $this->getTimeBasedDiscount($product);
        if ($timeDiscount > 0) {
            $discountAmount = $finalPrice * ($timeDiscount / 100);
            $finalPrice -= $discountAmount;
            $appliedRules[] = 'time_based';
            $discounts[] = [
                'type' => 'time_based',
                'description' => "Giảm giá theo thời gian {$timeDiscount}%",
                'amount' => $discountAmount,
                'percentage' => $timeDiscount,
            ];
        }

        // 4. Inventory-based Pricing
        $inventoryDiscount = $this->getInventoryBasedDiscount($product);
        if ($inventoryDiscount > 0) {
            $discountAmount = $finalPrice * ($inventoryDiscount / 100);
            $finalPrice -= $discountAmount;
            $appliedRules[] = 'inventory_based';
            $discounts[] = [
                'type' => 'inventory_based',
                'description' => "Giảm giá thanh lý tồn kho {$inventoryDiscount}%",
                'amount' => $discountAmount,
                'percentage' => $inventoryDiscount,
            ];
        }

        // 5. Demand-based Pricing
        $demandAdjustment = $this->getDemandBasedAdjustment($product);
        if ($demandAdjustment != 0) {
            $adjustmentAmount = $finalPrice * ($demandAdjustment / 100);
            $finalPrice += $adjustmentAmount;
            $appliedRules[] = 'demand_based';

            if ($demandAdjustment > 0) {
                $discounts[] = [
                    'type' => 'demand_surcharge',
                    'description' => "Phụ phí nhu cầu cao {$demandAdjustment}%",
                    'amount' => $adjustmentAmount,
                    'percentage' => $demandAdjustment,
                ];
            } else {
                $discounts[] = [
                    'type' => 'demand_discount',
                    'description' => "Giảm giá nhu cầu thấp " . abs($demandAdjustment) . "%",
                    'amount' => abs($adjustmentAmount),
                    'percentage' => abs($demandAdjustment),
                ];
            }
        }

        // 6. Loyalty Discount
        if ($user) {
            $loyaltyDiscount = $this->getLoyaltyDiscount($user, $product);
            if ($loyaltyDiscount > 0) {
                $discountAmount = $finalPrice * ($loyaltyDiscount / 100);
                $finalPrice -= $discountAmount;
                $appliedRules[] = 'loyalty';
                $discounts[] = [
                    'type' => 'loyalty',
                    'description' => "Giảm giá khách hàng thân thiết {$loyaltyDiscount}%",
                    'amount' => $discountAmount,
                    'percentage' => $loyaltyDiscount,
                ];
            }
        }

        // Ensure minimum price
        $minPrice = $product->cost_price ?? ($basePrice * 0.5);
        $finalPrice = max($finalPrice, $minPrice);

        $totalDiscount = $basePrice - $finalPrice;
        $discountPercentage = $basePrice > 0 ? ($totalDiscount / $basePrice) * 100 : 0;

        return [
            'product_id' => $productId,
            'base_price' => $basePrice,
            'final_price' => round($finalPrice, 2),
            'total_discount' => round($totalDiscount, 2),
            'discount_percentage' => round($discountPercentage, 2),
            'quantity' => $quantity,
            'total_amount' => round($finalPrice * $quantity, 2),
            'applied_rules' => $appliedRules,
            'discounts' => $discounts,
            'context' => $context,
            'calculated_at' => now()->toISOString(),
            'valid_until' => now()->addMinutes(30)->toISOString(), // Price valid for 30 minutes
        ];
    }

    /**
     * Bulk price update với dynamic rules
     */
    private function bulkPriceUpdate(array $params): array
    {
        $productIds = $params['product_ids'];
        $rules = $params['rules']; // Array of pricing rules
        $dryRun = $params['dry_run'] ?? true;

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);
                $newPrice = $this->applyPricingRules($product, $rules);

                $result = [
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'current_price' => $product->price,
                    'new_price' => $newPrice,
                    'change_amount' => $newPrice - $product->price,
                    'change_percentage' => $product->price > 0 ? (($newPrice - $product->price) / $product->price) * 100 : 0,
                ];

                if (!$dryRun) {
                    $product->update(['price' => $newPrice]);

                    // Log price change
                    Log::info("Dynamic price updated", [
                        'product_id' => $productId,
                        'old_price' => $product->price,
                        'new_price' => $newPrice,
                        'rules' => $rules,
                    ]);
                }

                $results[] = $result;
                $successCount++;

            } catch (\Exception $e) {
                $results[] = [
                    'product_id' => $productId,
                    'error' => $e->getMessage(),
                ];
                $errorCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'dry_run' => $dryRun,
            'results' => $results,
            'total_products' => count($productIds),
            'processed_at' => now()->toISOString(),
        ];
    }

    /**
     * Time-based pricing (flash sales, happy hours)
     */
    private function timeBasedPricing(array $params): array
    {
        $productIds = $params['product_ids'];
        $schedule = $params['schedule']; // Array of time-based rules
        $timezone = $params['timezone'] ?? 'Asia/Ho_Chi_Minh';

        $now = Carbon::now($timezone);
        $activeRules = [];
        $upcomingRules = [];

        foreach ($schedule as $rule) {
            $startTime = Carbon::parse($rule['start_time'], $timezone);
            $endTime = Carbon::parse($rule['end_time'], $timezone);

            if ($now->between($startTime, $endTime)) {
                $activeRules[] = array_merge($rule, [
                    'status' => 'active',
                    'time_remaining' => $endTime->diffInMinutes($now),
                ]);
            } elseif ($now->lt($startTime)) {
                $upcomingRules[] = array_merge($rule, [
                    'status' => 'upcoming',
                    'starts_in' => $startTime->diffInMinutes($now),
                ]);
            }
        }

        $affectedProducts = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            $currentPrice = $product->price;
            $adjustedPrice = $currentPrice;
            $appliedRules = [];

            foreach ($activeRules as $rule) {
                if ($rule['type'] === 'percentage') {
                    $adjustedPrice = $currentPrice * (1 - $rule['value'] / 100);
                } elseif ($rule['type'] === 'fixed_amount') {
                    $adjustedPrice = $currentPrice - $rule['value'];
                } elseif ($rule['type'] === 'fixed_price') {
                    $adjustedPrice = $rule['value'];
                }

                $appliedRules[] = $rule['name'];
            }

            $affectedProducts[] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'original_price' => $currentPrice,
                'adjusted_price' => max(0, round($adjustedPrice, 2)),
                'discount_amount' => $currentPrice - $adjustedPrice,
                'applied_rules' => $appliedRules,
            ];
        }

        return [
            'current_time' => $now->toISOString(),
            'timezone' => $timezone,
            'active_rules' => $activeRules,
            'upcoming_rules' => $upcomingRules,
            'affected_products' => $affectedProducts,
            'total_products' => count($affectedProducts),
        ];
    }

    /**
     * Quantity-based discounts
     */
    private function quantityDiscounts(array $params): array
    {
        $productId = $params['product_id'];
        $quantities = $params['quantities']; // Array of quantity tiers

        $product = Product::findOrFail($productId);
        $basePrice = $product->price;
        $tiers = [];

        foreach ($quantities as $qty) {
            $discount = $this->getQuantityDiscount($product, $qty);
            $discountedPrice = $basePrice * (1 - $discount / 100);

            $tiers[] = [
                'quantity' => $qty,
                'unit_price' => round($discountedPrice, 2),
                'total_price' => round($discountedPrice * $qty, 2),
                'discount_percentage' => $discount,
                'savings_per_unit' => round($basePrice - $discountedPrice, 2),
                'total_savings' => round(($basePrice - $discountedPrice) * $qty, 2),
            ];
        }

        return [
            'product_id' => $productId,
            'product_name' => $product->name,
            'base_price' => $basePrice,
            'quantity_tiers' => $tiers,
            'calculated_at' => now()->toISOString(),
        ];
    }

    /**
     * Customer group pricing
     */
    private function customerGroupPricing(array $params): array
    {
        $productId = $params['product_id'];
        $userGroups = $params['user_groups'] ?? ['retail', 'wholesale', 'vip', 'staff'];

        $product = Product::findOrFail($productId);
        $basePrice = $product->price;
        $groupPricing = [];

        foreach ($userGroups as $group) {
            $discount = $this->getGroupDiscount($group, $product);
            $groupPrice = $basePrice * (1 - $discount / 100);

            $groupPricing[] = [
                'group' => $group,
                'discount_percentage' => $discount,
                'price' => round($groupPrice, 2),
                'savings' => round($basePrice - $groupPrice, 2),
            ];
        }

        return [
            'product_id' => $productId,
            'product_name' => $product->name,
            'base_price' => $basePrice,
            'group_pricing' => $groupPricing,
            'calculated_at' => now()->toISOString(),
        ];
    }

    /**
     * Competitor pricing analysis
     */
    private function competitorPricing(array $params): array
    {
        $productId = $params['product_id'];
        $competitors = $params['competitors'] ?? [];

        $product = Product::findOrFail($productId);
        $ourPrice = $product->price;

        // This would integrate with competitor price monitoring APIs
        $competitorPrices = $this->fetchCompetitorPrices($product, $competitors);

        $analysis = [
            'our_price' => $ourPrice,
            'competitor_prices' => $competitorPrices,
            'market_position' => $this->analyzeMarketPosition($ourPrice, $competitorPrices),
            'recommended_action' => $this->getCompetitorPricingRecommendation($ourPrice, $competitorPrices),
        ];

        return [
            'product_id' => $productId,
            'product_name' => $product->name,
            'analysis' => $analysis,
            'analyzed_at' => now()->toISOString(),
        ];
    }

    /**
     * Demand-based pricing
     */
    private function demandBasedPricing(array $params): array
    {
        $productIds = $params['product_ids'];
        $period = $params['period'] ?? 30; // days

        $results = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            $demandMetrics = $this->calculateDemandMetrics($product, $period);
            $priceAdjustment = $this->calculateDemandBasedAdjustment($demandMetrics);

            $currentPrice = $product->price;
            $suggestedPrice = $currentPrice * (1 + $priceAdjustment / 100);

            $results[] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'current_price' => $currentPrice,
                'suggested_price' => round($suggestedPrice, 2),
                'adjustment_percentage' => round($priceAdjustment, 2),
                'demand_metrics' => $demandMetrics,
                'confidence_level' => $this->calculateConfidenceLevel($demandMetrics),
            ];
        }

        return [
            'analysis_period_days' => $period,
            'products_analyzed' => count($results),
            'results' => $results,
            'analyzed_at' => now()->toISOString(),
        ];
    }

    /**
     * Seasonal pricing
     */
    private function seasonalPricing(array $params): array
    {
        $productIds = $params['product_ids'];
        $season = $params['season'] ?? $this->getCurrentSeason();

        $seasonalRules = $this->getSeasonalRules($season);
        $results = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;

            $seasonalAdjustment = $this->getSeasonalAdjustment($product, $season);
            $adjustedPrice = $product->price * (1 + $seasonalAdjustment / 100);

            $results[] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'category' => $product->category?->name,
                'current_price' => $product->price,
                'seasonal_price' => round($adjustedPrice, 2),
                'adjustment_percentage' => round($seasonalAdjustment, 2),
                'season' => $season,
                'effective_period' => $seasonalRules['period'] ?? null,
            ];
        }

        return [
            'season' => $season,
            'seasonal_rules' => $seasonalRules,
            'products_analyzed' => count($results),
            'results' => $results,
            'calculated_at' => now()->toISOString(),
        ];
    }

    /**
     * Inventory-based pricing
     */
    private function inventoryBasedPricing(array $params): array
    {
        $categoryId = $params['category_id'] ?? null;
        $stockThreshold = $params['stock_threshold'] ?? 10;

        $query = Product::where('manage_stock', true)->where('status', 'active');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();
        $results = [];

        foreach ($products as $product) {
            $stockLevel = $product->stock_quantity;
            $stockRatio = $stockLevel / max(1, $product->low_stock_threshold ?: 10);

            $priceAdjustment = $this->calculateInventoryPriceAdjustment($stockRatio, $stockLevel);
            $adjustedPrice = $product->price * (1 + $priceAdjustment / 100);

            $recommendation = 'maintain';
            if ($priceAdjustment > 0) {
                $recommendation = 'increase_price';
            } elseif ($priceAdjustment < -5) {
                $recommendation = 'clearance_sale';
            } elseif ($priceAdjustment < 0) {
                $recommendation = 'discount';
            }

            $results[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'current_price' => $product->price,
                'suggested_price' => round($adjustedPrice, 2),
                'stock_quantity' => $stockLevel,
                'stock_ratio' => round($stockRatio, 2),
                'adjustment_percentage' => round($priceAdjustment, 2),
                'recommendation' => $recommendation,
                'urgency' => $stockLevel <= $stockThreshold ? 'high' : 'normal',
            ];
        }

        return [
            'stock_threshold' => $stockThreshold,
            'products_analyzed' => count($results),
            'high_urgency_count' => count(array_filter($results, fn($r) => $r['urgency'] === 'high')),
            'clearance_candidates' => count(array_filter($results, fn($r) => $r['recommendation'] === 'clearance_sale')),
            'results' => $results,
            'analyzed_at' => now()->toISOString(),
        ];
    }

    /**
     * Bundle pricing
     */
    private function bundlePricing(array $params): array
    {
        $productIds = $params['product_ids'];
        $bundleDiscount = $params['bundle_discount'] ?? 10; // Default 10% bundle discount

        $products = Product::whereIn('id', $productIds)->get();
        $totalPrice = $products->sum('price');
        $bundlePrice = $totalPrice * (1 - $bundleDiscount / 100);

        $bundle = [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'individual_price' => $product->price,
                    'sku' => $product->sku,
                ];
            }),
            'individual_total' => $totalPrice,
            'bundle_price' => round($bundlePrice, 2),
            'bundle_discount_percentage' => $bundleDiscount,
            'total_savings' => round($totalPrice - $bundlePrice, 2),
            'savings_per_product' => round(($totalPrice - $bundlePrice) / count($productIds), 2),
        ];

        return [
            'bundle' => $bundle,
            'products_count' => count($productIds),
            'calculated_at' => now()->toISOString(),
        ];
    }

    // Helper Methods

    private function getCustomerGroupDiscount(User $user, Product $product): float
    {
        $userGroup = $user->customer_group ?? 'retail';
        return $this->getGroupDiscount($userGroup, $product);
    }

    private function getGroupDiscount(string $group, Product $product): float
    {
        $discounts = [
            'retail' => 0,
            'wholesale' => 15,
            'vip' => 10,
            'staff' => 20,
            'student' => 5,
        ];

        $baseDiscount = $discounts[$group] ?? 0;

        // Category-specific adjustments
        if ($product->category) {
            $categoryMultiplier = match ($product->category->slug) {
                'electronics' => 0.8,
                'fashion' => 1.2,
                'books' => 1.5,
                default => 1.0
            };

            $baseDiscount *= $categoryMultiplier;
        }

        return min(50, $baseDiscount); // Max 50% discount
    }

    private function getQuantityDiscount(Product $product, int $quantity): float
    {
        // Quantity discount tiers
        $tiers = [
            ['min' => 1, 'max' => 4, 'discount' => 0],
            ['min' => 5, 'max' => 9, 'discount' => 5],
            ['min' => 10, 'max' => 19, 'discount' => 10],
            ['min' => 20, 'max' => 49, 'discount' => 15],
            ['min' => 50, 'max' => 99, 'discount' => 20],
            ['min' => 100, 'max' => PHP_INT_MAX, 'discount' => 25],
        ];

        foreach ($tiers as $tier) {
            if ($quantity >= $tier['min'] && $quantity <= $tier['max']) {
                return $tier['discount'];
            }
        }

        return 0;
    }

    private function getTimeBasedDiscount(Product $product): float
    {
        $now = Carbon::now();
        $hour = $now->hour;
        $dayOfWeek = $now->dayOfWeek;

        // Happy hour discounts (2-4 PM)
        if ($hour >= 14 && $hour < 16) {
            return 5;
        }

        // Weekend discounts
        if (in_array($dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return 3;
        }

        // Late night discounts (10 PM - 6 AM)
        if ($hour >= 22 || $hour < 6) {
            return 2;
        }

        return 0;
    }

    private function getInventoryBasedDiscount(Product $product): float
    {
        if (!$product->manage_stock) {
            return 0;
        }

        $stockLevel = $product->stock_quantity;
        $threshold = $product->low_stock_threshold ?: 10;

        // High stock - no discount
        if ($stockLevel > $threshold * 3) {
            return 0;
        }

        // Medium stock - small discount
        if ($stockLevel > $threshold) {
            return 2;
        }

        // Low stock - medium discount
        if ($stockLevel > $threshold * 0.5) {
            return 5;
        }

        // Very low stock - high discount
        return 10;
    }

    private function getDemandBasedAdjustment(Product $product): float
    {
        $recentSales = $this->getRecentSalesCount($product, 7);
        $avgSales = $this->getAverageSalesCount($product, 30);

        if ($avgSales == 0) {
            return 0;
        }

        $demandRatio = $recentSales / $avgSales;

        // High demand - increase price
        if ($demandRatio > 2) {
            return 5;
        } elseif ($demandRatio > 1.5) {
            return 3;
        }

        // Low demand - decrease price
        if ($demandRatio < 0.5) {
            return -5;
        } elseif ($demandRatio < 0.8) {
            return -2;
        }

        return 0;
    }

    private function getLoyaltyDiscount(User $user, Product $product): float
    {
        $orderCount = Order::where('user_id', $user->id)
                          ->where('status', 'completed')
                          ->count();

        $totalSpent = Order::where('user_id', $user->id)
                          ->where('status', 'completed')
                          ->sum('total_amount');

        // Loyalty tiers based on order count and total spent
        if ($orderCount >= 20 || $totalSpent >= 10000000) { // 10M VND
            return 8;
        } elseif ($orderCount >= 10 || $totalSpent >= 5000000) { // 5M VND
            return 5;
        } elseif ($orderCount >= 5 || $totalSpent >= 2000000) { // 2M VND
            return 3;
        }

        return 0;
    }

    private function applyPricingRules(Product $product, array $rules): float
    {
        $price = $product->price;

        foreach ($rules as $rule) {
            switch ($rule['type']) {
                case 'percentage_increase':
                    $price *= (1 + $rule['value'] / 100);
                    break;
                case 'percentage_decrease':
                    $price *= (1 - $rule['value'] / 100);
                    break;
                case 'fixed_increase':
                    $price += $rule['value'];
                    break;
                case 'fixed_decrease':
                    $price -= $rule['value'];
                    break;
                case 'set_price':
                    $price = $rule['value'];
                    break;
            }
        }

        // Ensure minimum price
        $minPrice = $product->cost_price ?? ($product->price * 0.5);
        return max($price, $minPrice);
    }

    private function fetchCompetitorPrices(Product $product, array $competitors): array
    {
        // This would integrate with competitor monitoring APIs
        // For now, return mock data
        $prices = [];

        foreach ($competitors as $competitor) {
            $prices[] = [
                'competitor' => $competitor,
                'price' => $product->price * (0.9 + (rand(0, 20) / 100)), // Mock price ±10%
                'url' => "https://{$competitor}.com/product/{$product->sku}",
                'last_updated' => now()->subHours(rand(1, 24))->toISOString(),
            ];
        }

        return $prices;
    }

    private function analyzeMarketPosition(float $ourPrice, array $competitorPrices): array
    {
        if (empty($competitorPrices)) {
            return ['position' => 'unknown', 'message' => 'No competitor data available'];
        }

        $prices = array_column($competitorPrices, 'price');
        $avgPrice = array_sum($prices) / count($prices);
        $minPrice = min($prices);
        $maxPrice = max($prices);

        $position = 'competitive';
        $message = 'Price is competitive with market';

        if ($ourPrice < $minPrice) {
            $position = 'lowest';
            $message = 'Lowest price in market';
        } elseif ($ourPrice > $maxPrice) {
            $position = 'highest';
            $message = 'Highest price in market';
        } elseif ($ourPrice < $avgPrice * 0.95) {
            $position = 'below_average';
            $message = 'Below average market price';
        } elseif ($ourPrice > $avgPrice * 1.05) {
            $position = 'above_average';
            $message = 'Above average market price';
        }

        return [
            'position' => $position,
            'message' => $message,
            'our_price' => $ourPrice,
            'market_avg' => round($avgPrice, 2),
            'market_min' => $minPrice,
            'market_max' => $maxPrice,
            'price_difference_from_avg' => round($ourPrice - $avgPrice, 2),
            'price_difference_percentage' => round((($ourPrice - $avgPrice) / $avgPrice) * 100, 2),
        ];
    }

    private function getCompetitorPricingRecommendation(float $ourPrice, array $competitorPrices): array
    {
        $analysis = $this->analyzeMarketPosition($ourPrice, $competitorPrices);

        $recommendations = match ($analysis['position']) {
            'highest' => [
                'action' => 'decrease_price',
                'urgency' => 'high',
                'suggested_adjustment' => -10,
                'reason' => 'Price significantly higher than competitors',
            ],
            'above_average' => [
                'action' => 'monitor',
                'urgency' => 'medium',
                'suggested_adjustment' => -5,
                'reason' => 'Price above market average',
            ],
            'lowest' => [
                'action' => 'increase_price',
                'urgency' => 'low',
                'suggested_adjustment' => 5,
                'reason' => 'Opportunity to increase margin',
            ],
            'below_average' => [
                'action' => 'consider_increase',
                'urgency' => 'low',
                'suggested_adjustment' => 3,
                'reason' => 'Room for price increase',
            ],
            default => [
                'action' => 'maintain',
                'urgency' => 'low',
                'suggested_adjustment' => 0,
                'reason' => 'Price is competitive',
            ],
        };

        return $recommendations;
    }

    private function calculateDemandMetrics(Product $product, int $days): array
    {
        $recentSales = $this->getRecentSalesCount($product, $days);
        $previousSales = $this->getRecentSalesCount($product, $days, $days);
        $avgSales = $this->getAverageSalesCount($product, $days * 3);

        $trend = $previousSales > 0 ? (($recentSales - $previousSales) / $previousSales) * 100 : 0;
        $velocity = $avgSales > 0 ? ($recentSales / $avgSales) : 0;

        return [
            'recent_sales' => $recentSales,
            'previous_period_sales' => $previousSales,
            'average_sales' => round($avgSales, 2),
            'trend_percentage' => round($trend, 2),
            'velocity_ratio' => round($velocity, 2),
            'demand_level' => $this->categorizeDemandLevel($velocity),
        ];
    }

    private function calculateDemandBasedAdjustment(array $metrics): float
    {
        $velocity = $metrics['velocity_ratio'];
        $trend = $metrics['trend_percentage'];

        $adjustment = 0;

        // Base adjustment on velocity
        if ($velocity > 2) {
            $adjustment += 8;
        } elseif ($velocity > 1.5) {
            $adjustment += 5;
        } elseif ($velocity > 1.2) {
            $adjustment += 2;
        } elseif ($velocity < 0.5) {
            $adjustment -= 8;
        } elseif ($velocity < 0.8) {
            $adjustment -= 5;
        }

        // Adjust based on trend
        if ($trend > 50) {
            $adjustment += 3;
        } elseif ($trend > 20) {
            $adjustment += 1;
        } elseif ($trend < -50) {
            $adjustment -= 3;
        } elseif ($trend < -20) {
            $adjustment -= 1;
        }

        return max(-20, min(20, $adjustment)); // Cap at ±20%
    }

    private function calculateConfidenceLevel(array $metrics): string
    {
        $recentSales = $metrics['recent_sales'];
        $avgSales = $metrics['average_sales'];

        if ($recentSales >= 10 && $avgSales >= 5) {
            return 'high';
        } elseif ($recentSales >= 5 && $avgSales >= 2) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    private function getCurrentSeason(): string
    {
        $month = Carbon::now()->month;

        return match (true) {
            in_array($month, [12, 1, 2]) => 'winter',
            in_array($month, [3, 4, 5]) => 'spring',
            in_array($month, [6, 7, 8]) => 'summer',
            in_array($month, [9, 10, 11]) => 'autumn',
            default => 'unknown'
        };
    }

    private function getSeasonalRules(string $season): array
    {
        $rules = [
            'winter' => [
                'period' => 'December - February',
                'categories' => [
                    'fashion' => 10, // Winter clothes premium
                    'electronics' => -5, // Post-holiday clearance
                ],
            ],
            'spring' => [
                'period' => 'March - May',
                'categories' => [
                    'fashion' => 5, // Spring collection
                    'home-garden' => 15, // Gardening season
                ],
            ],
            'summer' => [
                'period' => 'June - August',
                'categories' => [
                    'fashion' => -10, // Summer clearance
                    'travel' => 20, // Travel season
                ],
            ],
            'autumn' => [
                'period' => 'September - November',
                'categories' => [
                    'electronics' => 5, // Back to school
                    'fashion' => 15, // Fall collection
                ],
            ],
        ];

        return $rules[$season] ?? [];
    }

    private function getSeasonalAdjustment(Product $product, string $season): float
    {
        $rules = $this->getSeasonalRules($season);
        $categorySlug = $product->category?->slug;

        if ($categorySlug && isset($rules['categories'][$categorySlug])) {
            return $rules['categories'][$categorySlug];
        }

        return 0;
    }

    private function calculateInventoryPriceAdjustment(float $stockRatio, int $stockLevel): float
    {
        // Very high stock - discount to move inventory
        if ($stockRatio > 5) {
            return -15;
        } elseif ($stockRatio > 3) {
            return -10;
        } elseif ($stockRatio > 2) {
            return -5;
        }

        // Low stock - premium pricing
        if ($stockRatio < 0.5 && $stockLevel > 0) {
            return 10;
        } elseif ($stockRatio < 1) {
            return 5;
        }

        return 0;
    }

    private function getRecentSalesCount(Product $product, int $days, int $offset = 0): int
    {
        return Order::whereHas('items', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->where('status', 'completed')
                    ->where('created_at', '>=', now()->subDays($days + $offset))
                    ->where('created_at', '<', now()->subDays($offset))
                    ->count();
    }

    private function getAverageSalesCount(Product $product, int $days): float
    {
        $totalSales = Order::whereHas('items', function ($query) use ($product) {
                            $query->where('product_id', $product->id);
                        })
                        ->where('status', 'completed')
                        ->where('created_at', '>=', now()->subDays($days))
                        ->count();

        return $totalSales / $days;
    }

    private function categorizeDemandLevel(float $velocity): string
    {
        if ($velocity > 2) {
            return 'very_high';
        } elseif ($velocity > 1.5) {
            return 'high';
        } elseif ($velocity > 0.8) {
            return 'normal';
        } elseif ($velocity > 0.5) {
            return 'low';
        } else {
            return 'very_low';
        }
    }
}
