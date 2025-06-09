<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'user_id',
        'order_id',
        'type',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'reference',
        'notes',
        'meta_data',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after' => 'integer',
        'meta_data' => 'array',
    ];

    /**
     * Quan hệ với product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với variant
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Quan hệ với user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope theo product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope theo variant
     */
    public function scopeForVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    /**
     * Scope theo type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Lấy danh sách inventory types
     */
    public static function getInventoryTypes(): array
    {
        return [
            'sale' => 'Bán hàng',
            'return' => 'Trả hàng',
            'adjustment' => 'Điều chỉnh',
            'restock' => 'Nhập kho',
            'damaged' => 'Hàng hỏng',
            'expired' => 'Hàng hết hạn',
            'transfer' => 'Chuyển kho',
            'initial' => 'Tồn kho ban đầu',
        ];
    }

    /**
     * Lấy type label
     */
    public function getTypeLabelAttribute(): string
    {
        return static::getInventoryTypes()[$this->type] ?? $this->type;
    }

    /**
     * Lấy product name
     */
    public function getProductNameAttribute(): string
    {
        $name = $this->product->name ?? 'Unknown Product';

        if ($this->variant) {
            $name .= ' - ' . $this->variant->name;
        }

        return $name;
    }

    /**
     * Lấy change direction
     */
    public function getChangeDirectionAttribute(): string
    {
        if ($this->quantity_change > 0) {
            return 'increase';
        } elseif ($this->quantity_change < 0) {
            return 'decrease';
        } else {
            return 'no_change';
        }
    }

    /**
     * Lấy change icon
     */
    public function getChangeIconAttribute(): string
    {
        return match($this->change_direction) {
            'increase' => 'fas fa-arrow-up text-green-500',
            'decrease' => 'fas fa-arrow-down text-red-500',
            default => 'fas fa-minus text-gray-500'
        };
    }

    /**
     * Log inventory change
     */
    public static function logChange(
        int $productId,
        ?int $variantId,
        string $type,
        int $quantityBefore,
        int $quantityChange,
        int $quantityAfter,
        ?int $userId = null,
        ?int $orderId = null,
        ?string $reference = null,
        ?string $notes = null,
        ?array $metaData = null
    ): self {
        return static::create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'user_id' => $userId ?: auth()->id(),
            'order_id' => $orderId,
            'type' => $type,
            'quantity_before' => $quantityBefore,
            'quantity_change' => $quantityChange,
            'quantity_after' => $quantityAfter,
            'reference' => $reference,
            'notes' => $notes,
            'meta_data' => $metaData,
        ]);
    }

    /**
     * Get inventory history for product
     */
    public static function getProductHistory(int $productId, ?int $variantId = null, int $limit = 50)
    {
        $query = static::where('product_id', $productId);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        }

        return $query->with(['user', 'order'])
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get stock movements summary
     */
    public static function getStockMovementsSummary(int $productId, ?int $variantId = null, ?string $period = null): array
    {
        $query = static::where('product_id', $productId);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        }

        if ($period) {
            $query->where('created_at', '>=', now()->sub($period));
        }

        $movements = $query->get();

        $summary = [
            'total_in' => 0,
            'total_out' => 0,
            'net_change' => 0,
            'by_type' => [],
        ];

        foreach ($movements as $movement) {
            $change = $movement->quantity_change;
            
            if ($change > 0) {
                $summary['total_in'] += $change;
            } else {
                $summary['total_out'] += abs($change);
            }

            if (!isset($summary['by_type'][$movement->type])) {
                $summary['by_type'][$movement->type] = 0;
            }
            $summary['by_type'][$movement->type] += $change;
        }

        $summary['net_change'] = $summary['total_in'] - $summary['total_out'];

        return $summary;
    }

    /**
     * Clean old logs
     */
    public static function cleanOldLogs(int $days = 365): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
