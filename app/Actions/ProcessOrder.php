<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class ProcessOrder
{
    use AsAction;

    public function handle(array $orderData, array $cartTotal): array
    {
        try {
            DB::beginTransaction();

            // Create order
            $order = $this->createOrder($orderData, $cartTotal);

            // Create order items from cart
            $this->createOrderItems($order, $cartTotal['items']);

            // Update stock quantities
            $this->updateStock($order);

            // Apply coupon usage
            if ($cartTotal['coupon']) {
                $cartTotal['coupon']->applyCoupon();
            }

            // Clear cart
            $this->clearCart($orderData['user_id'] ?? null, $orderData['session_id'] ?? null);

            // Process payment
            $paymentResult = $this->processPayment($order, $orderData['payment_method_id']);

            DB::commit();

            return [
                'success' => true,
                'order' => $order,
                'payment_result' => $paymentResult,
                'message' => 'Đơn hàng đã được tạo thành công',
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create order record
     */
    private function createOrder(array $orderData, array $cartTotal): Order
    {
        return Order::create([
            'user_id' => $orderData['user_id'] ?? null,
            'customer_email' => $orderData['customer_email'],
            'customer_phone' => $orderData['customer_phone'] ?? null,
            'customer_name' => $orderData['customer_name'],

            // Billing address
            'billing_first_name' => $orderData['billing_first_name'],
            'billing_last_name' => $orderData['billing_last_name'],
            'billing_company' => $orderData['billing_company'] ?? null,
            'billing_address_1' => $orderData['billing_address_1'],
            'billing_address_2' => $orderData['billing_address_2'] ?? null,
            'billing_city' => $orderData['billing_city'],
            'billing_state' => $orderData['billing_state'],
            'billing_postcode' => $orderData['billing_postcode'],
            'billing_country' => $orderData['billing_country'],

            // Shipping address
            'ship_to_different_address' => $orderData['ship_to_different_address'] ?? false,
            'shipping_first_name' => $orderData['shipping_first_name'] ?? null,
            'shipping_last_name' => $orderData['shipping_last_name'] ?? null,
            'shipping_company' => $orderData['shipping_company'] ?? null,
            'shipping_address_1' => $orderData['shipping_address_1'] ?? null,
            'shipping_address_2' => $orderData['shipping_address_2'] ?? null,
            'shipping_city' => $orderData['shipping_city'] ?? null,
            'shipping_state' => $orderData['shipping_state'] ?? null,
            'shipping_postcode' => $orderData['shipping_postcode'] ?? null,
            'shipping_country' => $orderData['shipping_country'] ?? null,

            // Order totals
            'subtotal' => $cartTotal['subtotal'],
            'tax_amount' => $cartTotal['tax_amount'],
            'shipping_amount' => $cartTotal['shipping_amount'],
            'discount_amount' => $cartTotal['discount_amount'],
            'total' => $cartTotal['total'],

            // Additional info
            'notes' => $orderData['notes'] ?? null,
            'meta_data' => [
                'coupon_code' => $cartTotal['coupon']?->code,
                'shipping_method_id' => $cartTotal['shipping_method']?->id,
            ],
        ]);
    }

    /**
     * Create order items
     */
    private function createOrderItems(Order $order, array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'product_name' => $item['name'],
                'product_sku' => $item['sku'] ?? '',
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['total_price'],
            ]);

            // Update stock
            $orderItem->updateStock();
        }
    }

    /**
     * Update stock quantities
     */
    private function updateStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $item->updateStock();
        }
    }

    /**
     * Clear cart
     */
    private function clearCart(?int $userId, ?string $sessionId): void
    {
        $query = Cart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $query->delete();
    }

    /**
     * Process payment
     */
    private function processPayment(Order $order, int $paymentMethodId): array
    {
        $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);

        $order->update([
            'payment_method' => $paymentMethod->name,
        ]);

        return $paymentMethod->processPayment($order);
    }
}
