<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Product;

class WebhookController extends Controller
{
    /**
     * Handle payment webhook from payment providers
     */
    public function handlePayment(Request $request): JsonResponse
    {
        try {
            $provider = $request->route('provider'); // vnpay, momo, stripe, etc.

            Log::info("Payment webhook received from {$provider}", [
                'payload' => $request->all(),
                'headers' => $request->headers->all(),
            ]);

            switch ($provider) {
                case 'vnpay':
                    return $this->handleVnPayWebhook($request);
                case 'momo':
                    return $this->handleMomoWebhook($request);
                case 'stripe':
                    return $this->handleStripeWebhook($request);
                default:
                    Log::warning("Unknown payment provider: {$provider}");
                    return response()->json(['error' => 'Unknown provider'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Payment webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle VNPay webhook
     */
    private function handleVnPayWebhook(Request $request): JsonResponse
    {
        $vnpSecureHash = $request->input('vnp_SecureHash');
        $vnpTxnRef = $request->input('vnp_TxnRef');
        $vnpResponseCode = $request->input('vnp_ResponseCode');
        $vnpAmount = $request->input('vnp_Amount');

        // Verify signature (implement your VNPay signature verification)
        if (!$this->verifyVnPaySignature($request->all())) {
            Log::warning('VNPay webhook signature verification failed', $request->all());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Find order
        $order = Order::where('payment_reference', $vnpTxnRef)->first();
        if (!$order) {
            Log::warning("Order not found for VNPay transaction: {$vnpTxnRef}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Process payment result
        if ($vnpResponseCode === '00') {
            // Payment successful
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            // Trigger order confirmation events
            $this->triggerOrderEvents($order, 'payment_confirmed');

            Log::info("VNPay payment confirmed for order {$order->id}");
        } else {
            // Payment failed
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);

            Log::info("VNPay payment failed for order {$order->id}, code: {$vnpResponseCode}");
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle MoMo webhook
     */
    private function handleMomoWebhook(Request $request): JsonResponse
    {
        $signature = $request->input('signature');
        $orderId = $request->input('orderId');
        $resultCode = $request->input('resultCode');

        // Verify signature (implement your MoMo signature verification)
        if (!$this->verifyMomoSignature($request->all())) {
            Log::warning('MoMo webhook signature verification failed', $request->all());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Find order
        $order = Order::where('payment_reference', $orderId)->first();
        if (!$order) {
            Log::warning("Order not found for MoMo transaction: {$orderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Process payment result
        if ($resultCode == 0) {
            // Payment successful
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            $this->triggerOrderEvents($order, 'payment_confirmed');
            Log::info("MoMo payment confirmed for order {$order->id}");
        } else {
            // Payment failed
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);

            Log::info("MoMo payment failed for order {$order->id}, code: {$resultCode}");
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle Stripe webhook
     */
    private function handleStripeWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        // Verify webhook signature (implement Stripe signature verification)
        if (!$this->verifyStripeSignature($payload, $sigHeader)) {
            Log::warning('Stripe webhook signature verification failed');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = json_decode($payload, true);

        switch ($event['type']) {
            case 'payment_intent.succeeded':
                $this->handleStripePaymentSuccess($event['data']['object']);
                break;
            case 'payment_intent.payment_failed':
                $this->handleStripePaymentFailed($event['data']['object']);
                break;
            default:
                Log::info("Unhandled Stripe event type: {$event['type']}");
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle inventory webhook (for external inventory management)
     */
    public function handleInventory(Request $request): JsonResponse
    {
        try {
            $action = $request->input('action'); // update_stock, low_stock_alert, etc.
            $productSku = $request->input('sku');
            $quantity = $request->input('quantity');

            Log::info("Inventory webhook received", [
                'action' => $action,
                'sku' => $productSku,
                'quantity' => $quantity,
            ]);

            $product = Product::where('sku', $productSku)->first();
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            switch ($action) {
                case 'update_stock':
                    $product->update(['stock_quantity' => $quantity]);
                    Log::info("Stock updated for product {$product->id}: {$quantity}");
                    break;

                case 'low_stock_alert':
                    // Trigger low stock notification
                    $this->triggerLowStockAlert($product);
                    break;

                default:
                    Log::warning("Unknown inventory action: {$action}");
                    return response()->json(['error' => 'Unknown action'], 400);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Inventory webhook error', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle shipping webhook (for shipping providers)
     */
    public function handleShipping(Request $request): JsonResponse
    {
        try {
            $provider = $request->route('provider'); // ghn, viettel_post, etc.
            $trackingNumber = $request->input('tracking_number');
            $status = $request->input('status');

            Log::info("Shipping webhook received from {$provider}", [
                'tracking_number' => $trackingNumber,
                'status' => $status,
                'payload' => $request->all(),
            ]);

            $order = Order::where('tracking_number', $trackingNumber)->first();
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Update order shipping status
            $this->updateOrderShippingStatus($order, $status);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Shipping webhook error', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Verify VNPay signature
     */
    private function verifyVnPaySignature(array $data): bool
    {
        // Implement VNPay signature verification logic
        // This is a placeholder - implement according to VNPay documentation
        return true;
    }

    /**
     * Verify MoMo signature
     */
    private function verifyMomoSignature(array $data): bool
    {
        // Implement MoMo signature verification logic
        // This is a placeholder - implement according to MoMo documentation
        return true;
    }

    /**
     * Verify Stripe signature
     */
    private function verifyStripeSignature(string $payload, string $signature): bool
    {
        // Implement Stripe signature verification logic
        // This is a placeholder - implement according to Stripe documentation
        return true;
    }

    /**
     * Handle Stripe payment success
     */
    private function handleStripePaymentSuccess(array $paymentIntent): void
    {
        $orderId = $paymentIntent['metadata']['order_id'] ?? null;
        if (!$orderId) {
            Log::warning('Stripe payment success but no order_id in metadata', $paymentIntent);
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning("Order not found for Stripe payment: {$orderId}");
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
        ]);

        $this->triggerOrderEvents($order, 'payment_confirmed');
        Log::info("Stripe payment confirmed for order {$order->id}");
    }

    /**
     * Handle Stripe payment failed
     */
    private function handleStripePaymentFailed(array $paymentIntent): void
    {
        $orderId = $paymentIntent['metadata']['order_id'] ?? null;
        if (!$orderId) {
            Log::warning('Stripe payment failed but no order_id in metadata', $paymentIntent);
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning("Order not found for Stripe payment failure: {$orderId}");
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        Log::info("Stripe payment failed for order {$order->id}");
    }

    /**
     * Trigger order events (notifications, emails, etc.)
     */
    private function triggerOrderEvents(Order $order, string $event): void
    {
        try {
            switch ($event) {
                case 'payment_confirmed':
                    // Send order confirmation email
                    $this->sendOrderConfirmationEmail($order);

                    // Send notification to admin
                    $this->sendAdminNotification($order, 'New paid order received');

                    // Update inventory
                    $this->updateInventoryForOrder($order);
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Failed to trigger order events for order {$order->id}", [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail(Order $order): void
    {
        try {
            // Use EmailTemplate to send confirmation
            $template = \App\Models\EmailTemplate::getDefaultForType('order_confirmation');
            if ($template) {
                $variables = [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'order_total' => number_format($order->total_amount, 0, ',', '.') . 'đ',
                    'order_date' => $order->created_at->format('d/m/Y H:i'),
                    'order_items' => $this->formatOrderItemsForEmail($order),
                ];

                $template->sendEmail($order->customer_email, $variables);
                Log::info("Order confirmation email sent for order {$order->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send order confirmation email for order {$order->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send admin notification
     */
    private function sendAdminNotification(Order $order, string $message): void
    {
        try {
            // Send to admin email
            $adminEmail = config('mail.admin_email', 'admin@example.com');

            Mail::raw($message . "\n\nOrder ID: {$order->id}\nOrder Number: {$order->order_number}\nAmount: " . number_format($order->total_amount, 0, ',', '.') . 'đ', function ($mail) use ($adminEmail, $order) {
                $mail->to($adminEmail)
                     ->subject("New Order #{$order->order_number}");
            });

            Log::info("Admin notification sent for order {$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin notification for order {$order->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update inventory for order
     */
    private function updateInventoryForOrder(Order $order): void
    {
        try {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->decrement('stock_quantity', $item->quantity);

                    // Log inventory change
                    \App\Models\InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'sale',
                        'quantity_change' => -$item->quantity,
                        'quantity_after' => $product->fresh()->stock_quantity,
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' => "Sale from order #{$order->order_number}",
                    ]);
                }
            }

            Log::info("Inventory updated for order {$order->id}");
        } catch (\Exception $e) {
            Log::error("Failed to update inventory for order {$order->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Format order items for email
     */
    private function formatOrderItemsForEmail(Order $order): string
    {
        $html = '<table style="width: 100%; border-collapse: collapse;">';
        $html .= '<tr style="background: #f8f9fa;"><th style="padding: 10px; border: 1px solid #dee2e6;">Sản phẩm</th><th style="padding: 10px; border: 1px solid #dee2e6;">Số lượng</th><th style="padding: 10px; border: 1px solid #dee2e6;">Giá</th></tr>';

        foreach ($order->items as $item) {
            $html .= '<tr>';
            $html .= '<td style="padding: 10px; border: 1px solid #dee2e6;">' . $item->product_name . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #dee2e6; text-align: center;">' . $item->quantity . '</td>';
            $html .= '<td style="padding: 10px; border: 1px solid #dee2e6; text-align: right;">' . number_format($item->total_price, 0, ',', '.') . 'đ</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        return $html;
    }

    /**
     * Trigger low stock alert
     */
    private function triggerLowStockAlert(Product $product): void
    {
        try {
            // Send notification using NotificationSetting
            $notificationSetting = \App\Models\NotificationSetting::where('type', 'inventory_alert')
                                                                  ->where('status', 'active')
                                                                  ->first();

            if ($notificationSetting) {
                $data = [
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'current_stock' => $product->stock_quantity,
                    'product_url' => route('products.show', $product->slug),
                ];

                $notificationSetting->send($data);
                Log::info("Low stock alert sent for product {$product->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send low stock alert for product {$product->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update order shipping status
     */
    private function updateOrderShippingStatus(Order $order, string $status): void
    {
        $statusMapping = [
            'picked_up' => 'shipped',
            'in_transit' => 'shipped',
            'out_for_delivery' => 'shipped',
            'delivered' => 'delivered',
            'failed_delivery' => 'delivery_failed',
            'returned' => 'returned',
        ];

        $orderStatus = $statusMapping[$status] ?? $status;

        $order->update([
            'shipping_status' => $status,
            'status' => $orderStatus,
        ]);

        // Send notification to customer
        if ($orderStatus === 'delivered') {
            $this->sendDeliveryConfirmationEmail($order);
        }

        Log::info("Shipping status updated for order {$order->id}: {$status} -> {$orderStatus}");
    }

    /**
     * Send delivery confirmation email
     */
    private function sendDeliveryConfirmationEmail(Order $order): void
    {
        try {
            $template = \App\Models\EmailTemplate::getDefaultForType('order_delivered');
            if ($template) {
                $variables = [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'delivery_date' => now()->format('d/m/Y H:i'),
                ];

                $template->sendEmail($order->customer_email, $variables);
                Log::info("Delivery confirmation email sent for order {$order->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send delivery confirmation email for order {$order->id}", [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
