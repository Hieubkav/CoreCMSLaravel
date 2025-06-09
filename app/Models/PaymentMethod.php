<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'type',
        'gateway_config',
        'fixed_fee',
        'percentage_fee',
        'min_fee',
        'max_fee',
        'min_amount',
        'max_amount',
        'allowed_countries',
        'allowed_currencies',
        'requires_verification',
        'instructions',
        'redirect_url',
        'status',
        'order',
    ];

    protected $casts = [
        'gateway_config' => 'array',
        'fixed_fee' => 'decimal:2',
        'percentage_fee' => 'decimal:2',
        'min_fee' => 'decimal:2',
        'max_fee' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'allowed_countries' => 'array',
        'allowed_currencies' => 'array',
        'requires_verification' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope cho payment methods active
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
     * Lấy danh sách payment types
     */
    public static function getPaymentTypes(): array
    {
        return [
            'cash_on_delivery' => 'Thanh toán khi nhận hàng',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            'credit_card' => 'Thẻ tín dụng',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'vnpay' => 'VNPay',
            'momo' => 'MoMo',
            'zalopay' => 'ZaloPay',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getPaymentTypes()[$this->type] ?? $this->type;
    }

    /**
     * Lấy icon class
     */
    public function getIconClassAttribute(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        // Default icons based on type
        return match($this->type) {
            'cash_on_delivery' => 'fas fa-money-bill-wave',
            'bank_transfer' => 'fas fa-university',
            'credit_card' => 'fas fa-credit-card',
            'paypal' => 'fab fa-paypal',
            'stripe' => 'fab fa-stripe',
            'vnpay' => 'fas fa-wallet',
            'momo' => 'fas fa-mobile-alt',
            'zalopay' => 'fas fa-mobile-alt',
            default => 'fas fa-credit-card'
        };
    }

    /**
     * Kiểm tra có áp dụng được cho order không
     */
    public function isAvailableForOrder(array $orderData): bool
    {
        // Check order amount
        $orderTotal = $orderData['total'] ?? 0;
        
        if ($this->min_amount && $orderTotal < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $orderTotal > $this->max_amount) {
            return false;
        }

        // Check country
        $country = $orderData['billing_country'] ?? '';
        
        if ($this->allowed_countries && !in_array($country, $this->allowed_countries)) {
            return false;
        }

        // Check currency
        $currency = $orderData['currency'] ?? 'VND';
        
        if ($this->allowed_currencies && !in_array($currency, $this->allowed_currencies)) {
            return false;
        }

        return true;
    }

    /**
     * Tính payment fee
     */
    public function calculateFee(float $amount): float
    {
        $fee = $this->fixed_fee;
        
        if ($this->percentage_fee > 0) {
            $fee += ($amount * $this->percentage_fee) / 100;
        }

        // Apply min/max fee limits
        if ($this->min_fee && $fee < $this->min_fee) {
            $fee = $this->min_fee;
        }

        if ($this->max_fee && $fee > $this->max_fee) {
            $fee = $this->max_fee;
        }

        return round($fee, 2);
    }

    /**
     * Process payment (placeholder for gateway integration)
     */
    public function processPayment(Order $order): array
    {
        switch ($this->type) {
            case 'cash_on_delivery':
                return $this->processCOD($order);
                
            case 'bank_transfer':
                return $this->processBankTransfer($order);
                
            case 'vnpay':
                return $this->processVNPay($order);
                
            case 'momo':
                return $this->processMoMo($order);
                
            case 'zalopay':
                return $this->processZaloPay($order);
                
            case 'stripe':
                return $this->processStripe($order);
                
            case 'paypal':
                return $this->processPayPal($order);
                
            default:
                return [
                    'success' => false,
                    'message' => 'Phương thức thanh toán không được hỗ trợ',
                ];
        }
    }

    /**
     * Process COD payment
     */
    private function processCOD(Order $order): array
    {
        return [
            'success' => true,
            'message' => 'Đơn hàng đã được tạo. Bạn sẽ thanh toán khi nhận hàng.',
            'redirect_url' => route('orders.success', $order->order_number),
        ];
    }

    /**
     * Process bank transfer
     */
    private function processBankTransfer(Order $order): array
    {
        return [
            'success' => true,
            'message' => 'Vui lòng chuyển khoản theo thông tin được cung cấp.',
            'redirect_url' => route('orders.bank-transfer', $order->order_number),
            'bank_info' => $this->gateway_config['bank_info'] ?? null,
        ];
    }

    /**
     * Process VNPay payment (placeholder)
     */
    private function processVNPay(Order $order): array
    {
        // Integrate with VNPay API
        return [
            'success' => true,
            'message' => 'Đang chuyển hướng đến VNPay...',
            'redirect_url' => $this->generateVNPayUrl($order),
        ];
    }

    /**
     * Process MoMo payment (placeholder)
     */
    private function processMoMo(Order $order): array
    {
        // Integrate with MoMo API
        return [
            'success' => true,
            'message' => 'Đang chuyển hướng đến MoMo...',
            'redirect_url' => $this->generateMoMoUrl($order),
        ];
    }

    /**
     * Process ZaloPay payment (placeholder)
     */
    private function processZaloPay(Order $order): array
    {
        // Integrate with ZaloPay API
        return [
            'success' => true,
            'message' => 'Đang chuyển hướng đến ZaloPay...',
            'redirect_url' => $this->generateZaloPayUrl($order),
        ];
    }

    /**
     * Process Stripe payment (placeholder)
     */
    private function processStripe(Order $order): array
    {
        // Integrate with Stripe API
        return [
            'success' => true,
            'message' => 'Đang chuyển hướng đến Stripe...',
            'redirect_url' => $this->generateStripeUrl($order),
        ];
    }

    /**
     * Process PayPal payment (placeholder)
     */
    private function processPayPal(Order $order): array
    {
        // Integrate with PayPal API
        return [
            'success' => true,
            'message' => 'Đang chuyển hướng đến PayPal...',
            'redirect_url' => $this->generatePayPalUrl($order),
        ];
    }

    /**
     * Generate payment URLs (placeholders)
     */
    private function generateVNPayUrl(Order $order): string
    {
        return $this->redirect_url ?: route('orders.success', $order->order_number);
    }

    private function generateMoMoUrl(Order $order): string
    {
        return $this->redirect_url ?: route('orders.success', $order->order_number);
    }

    private function generateZaloPayUrl(Order $order): string
    {
        return $this->redirect_url ?: route('orders.success', $order->order_number);
    }

    private function generateStripeUrl(Order $order): string
    {
        return $this->redirect_url ?: route('orders.success', $order->order_number);
    }

    private function generatePayPalUrl(Order $order): string
    {
        return $this->redirect_url ?: route('orders.success', $order->order_number);
    }

    /**
     * Get available payment methods for order
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
