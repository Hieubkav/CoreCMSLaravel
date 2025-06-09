<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'options',
        'is_required',
        'is_filterable',
        'is_variation',
        'status',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_variation' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với attribute values
     */
    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }

    /**
     * Scope cho attributes active
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
     * Scope cho variation attributes
     */
    public function scopeVariation($query)
    {
        return $query->where('is_variation', true);
    }

    /**
     * Scope cho filterable attributes
     */
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    /**
     * Lấy danh sách attribute types
     */
    public static function getAttributeTypes(): array
    {
        return [
            'text' => 'Văn bản',
            'number' => 'Số',
            'select' => 'Lựa chọn đơn',
            'multiselect' => 'Lựa chọn nhiều',
            'boolean' => 'Có/Không',
            'date' => 'Ngày tháng',
        ];
    }

    /**
     * Validate giá trị theo type
     */
    public function validateValue($value): bool
    {
        switch ($this->type) {
            case 'text':
                return is_string($value);
                
            case 'number':
                return is_numeric($value);
                
            case 'select':
                return $this->options && in_array($value, $this->options);
                
            case 'multiselect':
                if (!is_array($value)) {
                    return false;
                }
                return $this->options && empty(array_diff($value, $this->options));
                
            case 'boolean':
                return is_bool($value) || in_array($value, [0, 1, '0', '1', 'true', 'false']);
                
            case 'date':
                return strtotime($value) !== false;
                
            default:
                return true;
        }
    }

    /**
     * Format giá trị để hiển thị
     */
    public function formatValue($value): string
    {
        switch ($this->type) {
            case 'boolean':
                return $value ? 'Có' : 'Không';
                
            case 'multiselect':
                return is_array($value) ? implode(', ', $value) : $value;
                
            case 'date':
                return date('d/m/Y', strtotime($value));
                
            default:
                return (string) $value;
        }
    }

    /**
     * Lấy unique values cho attribute này
     */
    public function getUniqueValues()
    {
        return $this->attributeValues()
                   ->distinct('value')
                   ->pluck('value')
                   ->filter()
                   ->sort()
                   ->values();
    }

    /**
     * Lấy products có attribute này
     */
    public function getProducts()
    {
        $productIds = $this->attributeValues()->pluck('product_id')->unique();
        
        return Product::whereIn('id', $productIds)
                     ->where('status', 'active')
                     ->get();
    }

    /**
     * Kiểm tra có được sử dụng không
     */
    public function isUsed(): bool
    {
        return $this->attributeValues()->exists();
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
