<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\TaxSetting;

class CalculateTax
{
    use AsAction;

    public function handle(array $orderData, array $cartItems): array
    {
        // Get applicable tax settings
        $taxSettings = TaxSetting::getApplicableForOrder($orderData);

        if ($taxSettings->isEmpty()) {
            return [
                'total_tax' => 0,
                'tax_breakdown' => [],
                'is_inclusive' => false,
            ];
        }

        $taxBreakdown = [];
        $totalTax = 0;
        $hasInclusiveTax = false;

        foreach ($taxSettings as $taxSetting) {
            $applicableAmount = $this->getApplicableAmount($taxSetting, $cartItems, $orderData);

            if ($applicableAmount <= 0) {
                continue;
            }

            $taxAmount = $taxSetting->calculateTax($applicableAmount);

            if ($taxAmount > 0) {
                $taxBreakdown[] = [
                    'id' => $taxSetting->id,
                    'name' => $taxSetting->name,
                    'type' => $taxSetting->type,
                    'rate' => $taxSetting->rate,
                    'is_inclusive' => $taxSetting->is_inclusive,
                    'applicable_amount' => $applicableAmount,
                    'tax_amount' => $taxAmount,
                ];

                if ($taxSetting->is_inclusive) {
                    $hasInclusiveTax = true;
                } else {
                    $totalTax += $taxAmount;
                }
            }
        }

        return [
            'total_tax' => round($totalTax, 2),
            'tax_breakdown' => $taxBreakdown,
            'is_inclusive' => $hasInclusiveTax,
        ];
    }

    /**
     * Get applicable amount for tax calculation
     */
    private function getApplicableAmount(TaxSetting $taxSetting, array $cartItems, array $orderData): float
    {
        $applicableAmount = 0;

        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $categoryId = $item['category_id'] ?? 0;

            if ($taxSetting->isApplicableForProduct($productId, $categoryId)) {
                $applicableAmount += $item['total_price'];
            }
        }

        return $applicableAmount;
    }

    /**
     * Calculate tax for specific amount and address
     */
    public static function calculateForAmount(float $amount, array $address, ?int $productId = null, ?int $categoryId = null): array
    {
        $orderData = [
            'billing_country' => $address['country'] ?? '',
            'billing_state' => $address['state'] ?? '',
            'billing_city' => $address['city'] ?? '',
            'billing_postcode' => $address['postcode'] ?? '',
            'customer_type' => $address['customer_type'] ?? 'individual',
        ];

        $cartItems = [];
        if ($productId) {
            $cartItems[] = [
                'product_id' => $productId,
                'category_id' => $categoryId,
                'total_price' => $amount,
            ];
        } else {
            $cartItems[] = [
                'product_id' => 0,
                'category_id' => 0,
                'total_price' => $amount,
            ];
        }

        return static::run($orderData, $cartItems);
    }
}
