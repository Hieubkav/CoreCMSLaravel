<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SmartInventoryManager
{
    use AsAction;

    /**
     * Quản lý inventory thông minh
     */
    public function handle(string $action, array $params = []): array
    {
        return match ($action) {
            'check_low_stock' => $this->checkLowStock(),
            'auto_reorder' => $this->autoReorder($params),
            'forecast_demand' => $this->forecastDemand($params),
            'optimize_stock' => $this->optimizeStock($params),
            'track_movement' => $this->trackMovement($params),
            'generate_report' => $this->generateReport($params),
            'alert_expiring' => $this->alertExpiringStock($params),
            'batch_tracking' => $this->batchTracking($params),
            'abc_analysis' => $this->abcAnalysis($params),
            'dead_stock_analysis' => $this->deadStockAnalysis($params),
            default => throw new \InvalidArgumentException("Unsupported action: {$action}")
        };
    }

    /**
     * Kiểm tra sản phẩm sắp hết hàng
     */
    private function checkLowStock(): array
    {
        $lowStockProducts = Product::where('manage_stock', true)
                                  ->where('status', 'active')
                                  ->whereRaw('stock_quantity <= low_stock_threshold')
                                  ->with(['category'])
                                  ->get();

        $criticalStock = $lowStockProducts->filter(function ($product) {
            return $product->stock_quantity <= ($product->low_stock_threshold * 0.5);
        });

        $alerts = [];

        foreach ($lowStockProducts as $product) {
            $severity = $criticalStock->contains($product) ? 'critical' : 'warning';

            $alerts[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'current_stock' => $product->stock_quantity,
                'threshold' => $product->low_stock_threshold,
                'category' => $product->category?->name,
                'severity' => $severity,
                'suggested_reorder' => $this->calculateReorderQuantity($product),
                'days_until_stockout' => $this->calculateDaysUntilStockout($product),
            ];

            // Send notification if enabled
            $this->sendLowStockNotification($product, $severity);
        }

        // Cache results for dashboard
        Cache::put('low_stock_alerts', $alerts, now()->addHours(1));

        return [
            'total_products' => $lowStockProducts->count(),
            'critical_count' => $criticalStock->count(),
            'warning_count' => $lowStockProducts->count() - $criticalStock->count(),
            'alerts' => $alerts,
            'last_check' => now()->toISOString(),
        ];
    }

    /**
     * Tự động đặt hàng bổ sung
     */
    private function autoReorder(array $params): array
    {
        $enableAutoReorder = $params['enable'] ?? false;
        $dryRun = $params['dry_run'] ?? true;

        if (!$enableAutoReorder) {
            return ['message' => 'Auto reorder is disabled'];
        }

        $productsToReorder = Product::where('manage_stock', true)
                                   ->where('status', 'active')
                                   ->where('auto_reorder_enabled', true)
                                   ->whereRaw('stock_quantity <= reorder_point')
                                   ->get();

        $reorderSuggestions = [];

        foreach ($productsToReorder as $product) {
            $reorderQuantity = $this->calculateOptimalReorderQuantity($product);
            $estimatedCost = $reorderQuantity * ($product->cost_price ?? $product->price * 0.7);

            $suggestion = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'current_stock' => $product->stock_quantity,
                'reorder_point' => $product->reorder_point ?? $product->low_stock_threshold,
                'suggested_quantity' => $reorderQuantity,
                'estimated_cost' => $estimatedCost,
                'supplier' => $product->primary_supplier ?? 'Not specified',
                'lead_time_days' => $product->lead_time_days ?? 7,
                'priority' => $this->calculateReorderPriority($product),
            ];

            if (!$dryRun) {
                // Create purchase order or notification
                $this->createReorderRequest($product, $reorderQuantity);
            }

            $reorderSuggestions[] = $suggestion;
        }

        return [
            'products_to_reorder' => count($reorderSuggestions),
            'total_estimated_cost' => array_sum(array_column($reorderSuggestions, 'estimated_cost')),
            'suggestions' => $reorderSuggestions,
            'dry_run' => $dryRun,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Dự báo nhu cầu
     */
    private function forecastDemand(array $params): array
    {
        $productId = $params['product_id'] ?? null;
        $days = $params['days'] ?? 30;
        $method = $params['method'] ?? 'moving_average'; // moving_average, linear_trend, seasonal

        $query = InventoryLog::where('type', 'sale')
                            ->where('created_at', '>=', now()->subDays($days * 2));

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $salesData = $query->selectRaw('
                                product_id,
                                DATE(created_at) as sale_date,
                                SUM(ABS(quantity_change)) as daily_sales
                            ')
                            ->groupBy('product_id', 'sale_date')
                            ->orderBy('sale_date')
                            ->get()
                            ->groupBy('product_id');

        $forecasts = [];

        foreach ($salesData as $productId => $sales) {
            $product = Product::find($productId);
            if (!$product) continue;

            $dailySales = $sales->pluck('daily_sales')->toArray();

            $forecast = match ($method) {
                'moving_average' => $this->movingAverageForecast($dailySales, $days),
                'linear_trend' => $this->linearTrendForecast($dailySales, $days),
                'seasonal' => $this->seasonalForecast($dailySales, $days),
                default => $this->movingAverageForecast($dailySales, $days)
            };

            $forecasts[] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'current_stock' => $product->stock_quantity,
                'historical_avg_daily_sales' => round(array_sum($dailySales) / count($dailySales), 2),
                'forecasted_daily_sales' => $forecast['daily_average'],
                'forecasted_total_demand' => $forecast['total_demand'],
                'recommended_stock_level' => $forecast['recommended_stock'],
                'stockout_risk' => $this->calculateStockoutRisk($product, $forecast),
                'confidence_level' => $forecast['confidence'],
            ];
        }

        return [
            'forecast_period_days' => $days,
            'method_used' => $method,
            'products_analyzed' => count($forecasts),
            'forecasts' => $forecasts,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Tối ưu hóa tồn kho
     */
    private function optimizeStock(array $params): array
    {
        $categoryId = $params['category_id'] ?? null;
        $targetDays = $params['target_days'] ?? 30;

        $query = Product::where('manage_stock', true)->where('status', 'active');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->with(['category'])->get();
        $optimizations = [];

        foreach ($products as $product) {
            $currentStock = $product->stock_quantity;
            $avgDailySales = $this->getAverageDailySales($product, 30);
            $optimalStock = $avgDailySales * $targetDays;
            $variance = $currentStock - $optimalStock;

            $status = 'optimal';
            $action = 'maintain';

            if ($variance > $optimalStock * 0.5) {
                $status = 'overstocked';
                $action = 'reduce';
            } elseif ($variance < -$optimalStock * 0.2) {
                $status = 'understocked';
                $action = 'increase';
            }

            $optimizations[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category?->name,
                'current_stock' => $currentStock,
                'optimal_stock' => round($optimalStock),
                'variance' => round($variance),
                'variance_percentage' => $optimalStock > 0 ? round(($variance / $optimalStock) * 100, 1) : 0,
                'avg_daily_sales' => round($avgDailySales, 2),
                'days_of_stock' => $avgDailySales > 0 ? round($currentStock / $avgDailySales, 1) : 999,
                'status' => $status,
                'recommended_action' => $action,
                'potential_savings' => $this->calculatePotentialSavings($product, $variance),
            ];
        }

        return [
            'target_days_of_stock' => $targetDays,
            'products_analyzed' => count($optimizations),
            'overstocked_count' => count(array_filter($optimizations, fn($o) => $o['status'] === 'overstocked')),
            'understocked_count' => count(array_filter($optimizations, fn($o) => $o['status'] === 'understocked')),
            'optimal_count' => count(array_filter($optimizations, fn($o) => $o['status'] === 'optimal')),
            'total_potential_savings' => array_sum(array_column($optimizations, 'potential_savings')),
            'optimizations' => $optimizations,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Theo dõi chuyển động kho
     */
    private function trackMovement(array $params): array
    {
        $productId = $params['product_id'] ?? null;
        $days = $params['days'] ?? 7;
        $type = $params['type'] ?? 'all'; // all, in, out, adjustment

        $query = InventoryLog::with(['product'])
                            ->where('created_at', '>=', now()->subDays($days));

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($type !== 'all') {
            $typeMapping = [
                'in' => ['purchase', 'return', 'adjustment_in'],
                'out' => ['sale', 'damage', 'adjustment_out'],
                'adjustment' => ['adjustment', 'bulk_adjustment'],
            ];

            if (isset($typeMapping[$type])) {
                $query->whereIn('type', $typeMapping[$type]);
            }
        }

        $movements = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_movements' => $movements->count(),
            'total_in' => $movements->where('quantity_change', '>', 0)->sum('quantity_change'),
            'total_out' => abs($movements->where('quantity_change', '<', 0)->sum('quantity_change')),
            'net_change' => $movements->sum('quantity_change'),
            'by_type' => $movements->groupBy('type')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_quantity' => $group->sum('quantity_change'),
                ];
            }),
            'by_product' => $movements->groupBy('product_id')->map(function ($group) {
                $product = $group->first()->product;
                return [
                    'product_name' => $product?->name,
                    'sku' => $product?->sku,
                    'movements_count' => $group->count(),
                    'net_change' => $group->sum('quantity_change'),
                ];
            })->take(10),
        ];

        return [
            'period_days' => $days,
            'filter_type' => $type,
            'summary' => $summary,
            'movements' => $movements->take(100)->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'product_name' => $movement->product?->name,
                    'sku' => $movement->product?->sku,
                    'type' => $movement->type,
                    'quantity_before' => $movement->quantity_before,
                    'quantity_change' => $movement->quantity_change,
                    'quantity_after' => $movement->quantity_after,
                    'reference' => $movement->reference,
                    'notes' => $movement->notes,
                    'created_at' => $movement->created_at->format('Y-m-d H:i:s'),
                ];
            }),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Tạo báo cáo inventory
     */
    private function generateReport(array $params): array
    {
        $type = $params['type'] ?? 'summary'; // summary, detailed, valuation
        $categoryId = $params['category_id'] ?? null;

        $query = Product::where('manage_stock', true);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->with(['category'])->get();

        $report = match ($type) {
            'summary' => $this->generateSummaryReport($products),
            'detailed' => $this->generateDetailedReport($products),
            'valuation' => $this->generateValuationReport($products),
            default => $this->generateSummaryReport($products)
        };

        return array_merge($report, [
            'report_type' => $type,
            'products_count' => $products->count(),
            'generated_at' => now()->toISOString(),
        ]);
    }

    /**
     * Cảnh báo hàng sắp hết hạn
     */
    private function alertExpiringStock(array $params): array
    {
        $days = $params['days'] ?? 30;

        // This would require expiry_date field in products table
        $expiringProducts = Product::where('manage_stock', true)
                                  ->where('status', 'active')
                                  ->whereNotNull('expiry_date')
                                  ->where('expiry_date', '<=', now()->addDays($days))
                                  ->where('stock_quantity', '>', 0)
                                  ->orderBy('expiry_date')
                                  ->get();

        $alerts = $expiringProducts->map(function ($product) {
            $daysUntilExpiry = now()->diffInDays($product->expiry_date, false);

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'stock_quantity' => $product->stock_quantity,
                'expiry_date' => $product->expiry_date->format('Y-m-d'),
                'days_until_expiry' => $daysUntilExpiry,
                'value_at_risk' => $product->stock_quantity * ($product->cost_price ?? $product->price * 0.7),
                'urgency' => $daysUntilExpiry <= 7 ? 'critical' : ($daysUntilExpiry <= 14 ? 'high' : 'medium'),
            ];
        });

        return [
            'alert_period_days' => $days,
            'expiring_products_count' => $alerts->count(),
            'total_value_at_risk' => $alerts->sum('value_at_risk'),
            'critical_count' => $alerts->where('urgency', 'critical')->count(),
            'high_priority_count' => $alerts->where('urgency', 'high')->count(),
            'alerts' => $alerts->toArray(),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Theo dõi batch/lot
     */
    private function batchTracking(array $params): array
    {
        $batchNumber = $params['batch_number'] ?? null;
        $productId = $params['product_id'] ?? null;

        // This would require batch tracking fields in inventory_logs
        $query = InventoryLog::with(['product']);

        if ($batchNumber) {
            $query->where('batch_number', $batchNumber);
        }

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $batchMovements = $query->orderBy('created_at', 'desc')->get();

        $batches = $batchMovements->groupBy('batch_number')->map(function ($movements, $batchNumber) {
            $firstMovement = $movements->sortBy('created_at')->first();
            $lastMovement = $movements->sortByDesc('created_at')->first();

            return [
                'batch_number' => $batchNumber,
                'product_name' => $firstMovement->product?->name,
                'sku' => $firstMovement->product?->sku,
                'initial_quantity' => $movements->where('type', 'purchase')->sum('quantity_change'),
                'current_quantity' => $lastMovement->quantity_after ?? 0,
                'movements_count' => $movements->count(),
                'first_received' => $firstMovement->created_at->format('Y-m-d'),
                'last_movement' => $lastMovement->created_at->format('Y-m-d'),
                'status' => $this->getBatchStatus($movements),
            ];
        });

        return [
            'batches_found' => $batches->count(),
            'batches' => $batches->values()->toArray(),
            'movements' => $batchMovements->take(50)->map(function ($movement) {
                return [
                    'batch_number' => $movement->batch_number,
                    'product_name' => $movement->product?->name,
                    'type' => $movement->type,
                    'quantity_change' => $movement->quantity_change,
                    'quantity_after' => $movement->quantity_after,
                    'created_at' => $movement->created_at->format('Y-m-d H:i:s'),
                ];
            })->toArray(),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * ABC Analysis - phân loại sản phẩm theo giá trị
     */
    private function abcAnalysis(array $params): array
    {
        $days = $params['days'] ?? 90;

        $products = Product::with(['orderItems' => function ($query) use ($days) {
                                $query->whereHas('order', function ($q) use ($days) {
                                    $q->where('created_at', '>=', now()->subDays($days))
                                      ->where('status', '!=', 'cancelled');
                                });
                            }])
                           ->where('status', 'active')
                           ->get();

        $productValues = $products->map(function ($product) {
            $totalRevenue = $product->orderItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $totalQuantity = $product->orderItems->sum('quantity');
            $currentValue = $product->stock_quantity * ($product->cost_price ?? $product->price * 0.7);

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'total_revenue' => $totalRevenue,
                'total_quantity_sold' => $totalQuantity,
                'current_stock_value' => $currentValue,
                'stock_quantity' => $product->stock_quantity,
                'avg_price' => $totalQuantity > 0 ? $totalRevenue / $totalQuantity : $product->price,
            ];
        })->sortByDesc('total_revenue');

        $totalRevenue = $productValues->sum('total_revenue');
        $cumulativeRevenue = 0;
        $classification = [];

        foreach ($productValues as $product) {
            $cumulativeRevenue += $product['total_revenue'];
            $cumulativePercentage = $totalRevenue > 0 ? ($cumulativeRevenue / $totalRevenue) * 100 : 0;

            $class = 'C';
            if ($cumulativePercentage <= 80) {
                $class = 'A';
            } elseif ($cumulativePercentage <= 95) {
                $class = 'B';
            }

            $classification[] = array_merge($product, [
                'class' => $class,
                'cumulative_revenue' => $cumulativeRevenue,
                'cumulative_percentage' => round($cumulativePercentage, 2),
                'revenue_percentage' => $totalRevenue > 0 ? round(($product['total_revenue'] / $totalRevenue) * 100, 2) : 0,
            ]);
        }

        $summary = [
            'class_A' => collect($classification)->where('class', 'A'),
            'class_B' => collect($classification)->where('class', 'B'),
            'class_C' => collect($classification)->where('class', 'C'),
        ];

        return [
            'analysis_period_days' => $days,
            'total_products' => count($classification),
            'total_revenue' => $totalRevenue,
            'class_summary' => [
                'A' => [
                    'count' => $summary['class_A']->count(),
                    'percentage' => round(($summary['class_A']->count() / count($classification)) * 100, 1),
                    'revenue_share' => round($summary['class_A']->sum('revenue_percentage'), 1),
                ],
                'B' => [
                    'count' => $summary['class_B']->count(),
                    'percentage' => round(($summary['class_B']->count() / count($classification)) * 100, 1),
                    'revenue_share' => round($summary['class_B']->sum('revenue_percentage'), 1),
                ],
                'C' => [
                    'count' => $summary['class_C']->count(),
                    'percentage' => round(($summary['class_C']->count() / count($classification)) * 100, 1),
                    'revenue_share' => round($summary['class_C']->sum('revenue_percentage'), 1),
                ],
            ],
            'products' => $classification,
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Phân tích hàng tồn kho chết
     */
    private function deadStockAnalysis(array $params): array
    {
        $days = $params['days'] ?? 180;
        $minValue = $params['min_value'] ?? 100000; // Minimum value to consider

        $products = Product::where('manage_stock', true)
                          ->where('status', 'active')
                          ->where('stock_quantity', '>', 0)
                          ->whereDoesntHave('orderItems', function ($query) use ($days) {
                              $query->whereHas('order', function ($q) use ($days) {
                                  $q->where('created_at', '>=', now()->subDays($days))
                                    ->where('status', '!=', 'cancelled');
                              });
                          })
                          ->with(['category'])
                          ->get();

        $deadStock = $products->map(function ($product) use ($days) {
            $stockValue = $product->stock_quantity * ($product->cost_price ?? $product->price * 0.7);
            $lastSale = $product->orderItems()
                               ->whereHas('order', function ($q) {
                                   $q->where('status', '!=', 'cancelled');
                               })
                               ->latest()
                               ->first();

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category?->name,
                'stock_quantity' => $product->stock_quantity,
                'unit_cost' => $product->cost_price ?? $product->price * 0.7,
                'total_value' => $stockValue,
                'days_since_last_sale' => $lastSale ? $lastSale->created_at->diffInDays(now()) : $days + 1,
                'last_sale_date' => $lastSale?->created_at?->format('Y-m-d'),
                'age_category' => $this->getAgeCategory($lastSale ? $lastSale->created_at->diffInDays(now()) : $days + 1),
                'disposal_recommendation' => $this->getDisposalRecommendation($product, $stockValue),
            ];
        })->filter(function ($item) use ($minValue) {
            return $item['total_value'] >= $minValue;
        })->sortByDesc('total_value');

        $totalValue = $deadStock->sum('total_value');
        $ageGroups = $deadStock->groupBy('age_category');

        return [
            'analysis_period_days' => $days,
            'minimum_value_threshold' => $minValue,
            'dead_stock_products' => $deadStock->count(),
            'total_dead_stock_value' => $totalValue,
            'age_breakdown' => $ageGroups->map(function ($group, $age) {
                return [
                    'count' => $group->count(),
                    'total_value' => $group->sum('total_value'),
                    'percentage' => round(($group->sum('total_value') / $totalValue) * 100, 1),
                ];
            }),
            'products' => $deadStock->values()->toArray(),
            'recommendations' => [
                'immediate_action' => $deadStock->where('age_category', 'critical')->count(),
                'discount_candidates' => $deadStock->where('age_category', 'old')->count(),
                'monitor_closely' => $deadStock->where('age_category', 'aging')->count(),
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    // Helper methods
    private function calculateReorderQuantity(Product $product): int
    {
        $avgDailySales = $this->getAverageDailySales($product, 30);
        $leadTimeDays = $product->lead_time_days ?? 7;
        $safetyStock = $avgDailySales * 3; // 3 days safety stock

        return max(1, round(($avgDailySales * $leadTimeDays) + $safetyStock));
    }

    private function calculateDaysUntilStockout(Product $product): int
    {
        $avgDailySales = $this->getAverageDailySales($product, 30);

        if ($avgDailySales <= 0) {
            return 999; // Effectively infinite
        }

        return max(0, round($product->stock_quantity / $avgDailySales));
    }

    private function getAverageDailySales(Product $product, int $days): float
    {
        $sales = InventoryLog::where('product_id', $product->id)
                            ->where('type', 'sale')
                            ->where('created_at', '>=', now()->subDays($days))
                            ->sum('quantity_change');

        return abs($sales) / $days;
    }

    private function sendLowStockNotification(Product $product, string $severity): void
    {
        try {
            $notification = NotificationSetting::where('type', 'low_stock_alert')
                                              ->where('status', 'active')
                                              ->first();

            if ($notification) {
                $data = [
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'current_stock' => $product->stock_quantity,
                    'threshold' => $product->low_stock_threshold,
                    'severity' => $severity,
                ];

                $notification->send($data);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send low stock notification", [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function calculateOptimalReorderQuantity(Product $product): int
    {
        $avgDailySales = $this->getAverageDailySales($product, 30);
        $leadTime = $product->lead_time_days ?? 7;
        $reviewPeriod = $product->review_period_days ?? 30;
        $serviceLevel = 0.95; // 95% service level

        // Economic Order Quantity (EOQ) calculation
        $annualDemand = $avgDailySales * 365;
        $orderingCost = $product->ordering_cost ?? 50000; // Default ordering cost
        $holdingCost = ($product->cost_price ?? $product->price * 0.7) * 0.2; // 20% holding cost

        if ($holdingCost > 0) {
            $eoq = sqrt((2 * $annualDemand * $orderingCost) / $holdingCost);
        } else {
            $eoq = $avgDailySales * 30; // Fallback to 30 days supply
        }

        return max(1, round($eoq));
    }

    private function calculateReorderPriority(Product $product): string
    {
        $daysUntilStockout = $this->calculateDaysUntilStockout($product);
        $leadTime = $product->lead_time_days ?? 7;

        if ($daysUntilStockout <= $leadTime) {
            return 'urgent';
        } elseif ($daysUntilStockout <= $leadTime * 2) {
            return 'high';
        } elseif ($daysUntilStockout <= $leadTime * 3) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    private function createReorderRequest(Product $product, int $quantity): void
    {
        // This would create a purchase order or send notification to procurement team
        Log::info("Auto reorder request created", [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'quantity' => $quantity,
            'estimated_cost' => $quantity * ($product->cost_price ?? $product->price * 0.7),
        ]);
    }

    private function movingAverageForecast(array $salesData, int $days): array
    {
        $recentSales = array_slice($salesData, -min(7, count($salesData)));
        $avgDaily = count($recentSales) > 0 ? array_sum($recentSales) / count($recentSales) : 0;

        return [
            'daily_average' => round($avgDaily, 2),
            'total_demand' => round($avgDaily * $days),
            'recommended_stock' => round($avgDaily * $days * 1.2), // 20% buffer
            'confidence' => count($recentSales) >= 7 ? 'high' : 'medium',
        ];
    }

    private function linearTrendForecast(array $salesData, int $days): array
    {
        if (count($salesData) < 2) {
            return $this->movingAverageForecast($salesData, $days);
        }

        // Simple linear regression
        $n = count($salesData);
        $sumX = array_sum(range(1, $n));
        $sumY = array_sum($salesData);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1;
            $y = $salesData[$i];
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        $nextPeriodSales = $intercept + $slope * ($n + 1);
        $avgDaily = max(0, $nextPeriodSales);

        return [
            'daily_average' => round($avgDaily, 2),
            'total_demand' => round($avgDaily * $days),
            'recommended_stock' => round($avgDaily * $days * 1.3), // 30% buffer for trend
            'confidence' => $n >= 14 ? 'high' : 'medium',
        ];
    }

    private function seasonalForecast(array $salesData, int $days): array
    {
        // Simplified seasonal adjustment
        if (count($salesData) < 28) {
            return $this->movingAverageForecast($salesData, $days);
        }

        $weeklyAvg = array_chunk($salesData, 7);
        $seasonalFactors = [];

        foreach ($weeklyAvg as $week) {
            $seasonalFactors[] = array_sum($week) / 7;
        }

        $overallAvg = array_sum($seasonalFactors) / count($seasonalFactors);
        $currentSeasonFactor = end($seasonalFactors) / $overallAvg;

        $baseDaily = $overallAvg;
        $adjustedDaily = $baseDaily * $currentSeasonFactor;

        return [
            'daily_average' => round($adjustedDaily, 2),
            'total_demand' => round($adjustedDaily * $days),
            'recommended_stock' => round($adjustedDaily * $days * 1.4), // 40% buffer for seasonality
            'confidence' => count($weeklyAvg) >= 8 ? 'high' : 'medium',
        ];
    }

    private function calculateStockoutRisk(Product $product, array $forecast): string
    {
        $currentStock = $product->stock_quantity;
        $forecastedDemand = $forecast['total_demand'];

        if ($currentStock <= $forecastedDemand * 0.5) {
            return 'high';
        } elseif ($currentStock <= $forecastedDemand * 0.8) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    private function calculatePotentialSavings(Product $product, float $variance): float
    {
        if ($variance <= 0) return 0;

        $unitCost = $product->cost_price ?? $product->price * 0.7;
        $holdingCostRate = 0.2; // 20% annual holding cost

        return $variance * $unitCost * $holdingCostRate / 12; // Monthly savings
    }

    private function generateSummaryReport(Collection $products): array
    {
        $totalValue = $products->sum(function ($product) {
            return $product->stock_quantity * ($product->cost_price ?? $product->price * 0.7);
        });

        return [
            'total_products' => $products->count(),
            'total_stock_value' => $totalValue,
            'in_stock_products' => $products->where('in_stock', true)->count(),
            'out_of_stock_products' => $products->where('in_stock', false)->count(),
            'low_stock_products' => $products->filter(fn($p) => $p->isLowStock())->count(),
            'average_stock_value' => $products->count() > 0 ? $totalValue / $products->count() : 0,
        ];
    }

    private function generateDetailedReport(Collection $products): array
    {
        return [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category?->name,
                    'stock_quantity' => $product->stock_quantity,
                    'stock_value' => $product->stock_quantity * ($product->cost_price ?? $product->price * 0.7),
                    'status' => $product->getStockStatusAttribute(),
                    'last_updated' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            })->toArray(),
        ];
    }

    private function generateValuationReport(Collection $products): array
    {
        $valuation = $products->map(function ($product) {
            $unitCost = $product->cost_price ?? $product->price * 0.7;
            $stockValue = $product->stock_quantity * $unitCost;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'stock_quantity' => $product->stock_quantity,
                'unit_cost' => $unitCost,
                'stock_value' => $stockValue,
                'category' => $product->category?->name,
            ];
        })->sortByDesc('stock_value');

        return [
            'total_inventory_value' => $valuation->sum('stock_value'),
            'products_by_value' => $valuation->values()->toArray(),
            'top_10_by_value' => $valuation->take(10)->values()->toArray(),
        ];
    }

    private function getBatchStatus(Collection $movements): string
    {
        $lastMovement = $movements->sortByDesc('created_at')->first();
        $currentQuantity = $lastMovement->quantity_after ?? 0;

        if ($currentQuantity <= 0) {
            return 'depleted';
        } elseif ($currentQuantity < $movements->where('type', 'purchase')->sum('quantity_change') * 0.2) {
            return 'low';
        } else {
            return 'active';
        }
    }

    private function getAgeCategory(int $daysSinceLastSale): string
    {
        if ($daysSinceLastSale > 365) {
            return 'critical';
        } elseif ($daysSinceLastSale > 180) {
            return 'old';
        } elseif ($daysSinceLastSale > 90) {
            return 'aging';
        } else {
            return 'recent';
        }
    }

    private function getDisposalRecommendation(Product $product, float $stockValue): string
    {
        if ($stockValue > 1000000) { // > 1M VND
            return 'discount_sale';
        } elseif ($stockValue > 500000) { // > 500K VND
            return 'bundle_deal';
        } else {
            return 'clearance';
        }
    }
}
