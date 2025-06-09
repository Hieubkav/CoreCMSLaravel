<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_email',
        'customer_phone',
        'customer_name',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'ship_to_different_address',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'notes',
        'meta_data',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'ship_to_different_address' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'meta_data' => 'array',
    ];

    /**
     * Quan hệ với user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Quan hệ với inventory logs
     */
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    /**
     * Quan hệ với product reviews
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(\Str::random(6));
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Scope theo status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope theo payment status
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope cho orders của user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Lấy danh sách order statuses
     */
    public static function getOrderStatuses(): array
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã gửi hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
            'failed' => 'Thất bại',
        ];
    }

    /**
     * Lấy danh sách payment statuses
     */
    public static function getPaymentStatuses(): array
    {
        return [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refunded' => 'Đã hoàn tiền',
            'partially_refunded' => 'Hoàn tiền một phần',
        ];
    }

    /**
     * Lấy status label
     */
    public function getStatusLabelAttribute(): string
    {
        return static::getOrderStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Lấy payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return static::getPaymentStatuses()[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Lấy billing address formatted
     */
    public function getBillingAddressAttribute(): string
    {
        $address = [];
        
        if ($this->billing_company) {
            $address[] = $this->billing_company;
        }
        
        $address[] = trim($this->billing_first_name . ' ' . $this->billing_last_name);
        $address[] = $this->billing_address_1;
        
        if ($this->billing_address_2) {
            $address[] = $this->billing_address_2;
        }
        
        $address[] = $this->billing_city . ', ' . $this->billing_state . ' ' . $this->billing_postcode;
        $address[] = $this->billing_country;

        return implode("\n", array_filter($address));
    }

    /**
     * Lấy shipping address formatted
     */
    public function getShippingAddressAttribute(): string
    {
        if (!$this->ship_to_different_address) {
            return $this->billing_address;
        }

        $address = [];
        
        if ($this->shipping_company) {
            $address[] = $this->shipping_company;
        }
        
        $address[] = trim($this->shipping_first_name . ' ' . $this->shipping_last_name);
        $address[] = $this->shipping_address_1;
        
        if ($this->shipping_address_2) {
            $address[] = $this->shipping_address_2;
        }
        
        $address[] = $this->shipping_city . ', ' . $this->shipping_state . ' ' . $this->shipping_postcode;
        $address[] = $this->shipping_country;

        return implode("\n", array_filter($address));
    }

    /**
     * Kiểm tra có thể cancel không
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Kiểm tra có thể refund không
     */
    public function canBeRefunded(): bool
    {
        return $this->payment_status === 'paid' && 
               in_array($this->status, ['delivered', 'shipped']);
    }

    /**
     * Cancel order
     */
    public function cancel(string $reason = ''): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->status = 'cancelled';
        $this->notes = $this->notes . "\nOrder cancelled: " . $reason;
        
        // Restore stock
        foreach ($this->items as $item) {
            $this->restoreStock($item);
        }

        return $this->save();
    }

    /**
     * Restore stock for order item
     */
    private function restoreStock(OrderItem $item): void
    {
        if ($item->variant_id) {
            $variant = ProductVariant::find($item->variant_id);
            if ($variant && $variant->manage_stock) {
                $variant->increment('stock_quantity', $item->quantity);
            }
        } else {
            $product = Product::find($item->product_id);
            if ($product && $product->manage_stock) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }

        // Log inventory change
        InventoryLog::create([
            'product_id' => $item->product_id,
            'variant_id' => $item->variant_id,
            'order_id' => $this->id,
            'type' => 'return',
            'quantity_before' => 0, // Will be updated
            'quantity_change' => $item->quantity,
            'quantity_after' => 0, // Will be updated
            'reference' => $this->order_number,
            'notes' => 'Stock restored due to order cancellation',
        ]);
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}
