<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ShippingMethod;

class CalculateCartTotal
{
    use AsAction;

    public function handle(?int $userId = null, ?string $sessionId = null, ?string $couponCode = null, ?int $shippingMethodId = null): array
    {
        // Get cart items
        $cartItems = $this->getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return [
                'items' => [],
                'subtotal' => 0,
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total' => 0,
                'item_count' => 0,
                'total_weight' => 0,
                'coupon' => null,
                'shipping_method' => null,
            ];
        }

        // Calculate subtotal and prepare items
        $subtotal = 0;
        $totalWeight = 0;
        $itemCount = 0;
        $items = [];

        foreach ($cartItems as $cartItem) {
            $itemTotal = $cartItem->price * $cartItem->quantity;
            $subtotal += $itemTotal;
            $itemCount += $cartItem->quantity;

            // Calculate weight
            if ($cartItem->variant) {
                $weight = $cartItem->variant->weight ?: $cartItem->product->weight ?: 0;
            } else {
                $weight = $cartItem->product->weight ?: 0;
            }
            $totalWeight += $weight * $cartItem->quantity;

            $items[] = [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'variant_id' => $cartItem->variant_id,
                'name' => $cartItem->product_name,
                'price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'total_price' => $itemTotal,
                'image' => $cartItem->product_image,
            ];
        }

        // Apply coupon discount
        $discountAmount = 0;
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::findByCode($couponCode);
            if ($coupon) {
                $couponValidation = $coupon->isValidForCart($items, $subtotal);
                if ($couponValidation['valid']) {
                    $discountAmount = $couponValidation['discount_amount'];
                }
            }
        }

        // Calculate tax using TaxSetting
        $taxCalculation = \App\Actions\CalculateTax::run($userId ? ['user_id' => $userId] : [], $items);
        $taxAmount = $taxCalculation['total_tax'];

        // Calculate shipping
        $shippingAmount = 0;
        $shippingMethod = null;
        if ($shippingMethodId) {
            $shippingMethod = ShippingMethod::find($shippingMethodId);
            if ($shippingMethod) {
                $orderData = [
                    'total' => $subtotal - $discountAmount + $taxAmount,
                    'total_weight' => $totalWeight,
                ];

                if ($shippingMethod->isAvailableForOrder($orderData)) {
                    $shippingAmount = $shippingMethod->calculateCost($orderData);
                }
            }
        }

        // Calculate final total
        $total = $subtotal + $taxAmount + $shippingAmount - $discountAmount;

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'shipping_amount' => round($shippingAmount, 2),
            'discount_amount' => round($discountAmount, 2),
            'total' => round($total, 2),
            'item_count' => $itemCount,
            'total_weight' => round($totalWeight, 2),
            'coupon' => $coupon,
            'shipping_method' => $shippingMethod,
            'tax_calculation' => $taxCalculation,
        ];
    }

    /**
     * Get cart items for user or session
     */
    private function getCartItems(?int $userId, ?string $sessionId)
    {
        $query = Cart::with(['product', 'variant']);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return collect();
        }

        return $query->get();
    }


}
