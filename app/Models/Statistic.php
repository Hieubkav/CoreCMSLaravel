<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'value',
        'icon',
        'description',
        'animation_enabled',
        'status',
        'order',
    ];

    protected $casts = [
        'animation_enabled' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope cho statistics active
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
        return $query->orderBy('order')->orderBy('label');
    }

    /**
     * Kiểm tra value có phải là số không
     */
    public function isNumeric(): bool
    {
        return is_numeric($this->value);
    }

    /**
     * Format value cho hiển thị
     */
    public function getFormattedValueAttribute()
    {
        if ($this->isNumeric()) {
            $number = (float) $this->value;
            
            // Format số lớn
            if ($number >= 1000000) {
                return number_format($number / 1000000, 1) . 'M';
            } elseif ($number >= 1000) {
                return number_format($number / 1000, 1) . 'K';
            } else {
                return number_format($number);
            }
        }

        return $this->value;
    }

    /**
     * Lấy icon với fallback
     */
    public function getIconClassAttribute()
    {
        return $this->icon ?: 'fas fa-chart-line';
    }

    /**
     * Lấy danh sách icons phổ biến
     */
    public static function getPopularIcons(): array
    {
        return [
            'fas fa-users' => 'Người dùng',
            'fas fa-chart-line' => 'Biểu đồ tăng trưởng',
            'fas fa-trophy' => 'Thành tích',
            'fas fa-star' => 'Đánh giá',
            'fas fa-download' => 'Lượt tải',
            'fas fa-eye' => 'Lượt xem',
            'fas fa-heart' => 'Yêu thích',
            'fas fa-thumbs-up' => 'Like',
            'fas fa-clock' => 'Thời gian',
            'fas fa-globe' => 'Toàn cầu',
            'fas fa-building' => 'Doanh nghiệp',
            'fas fa-handshake' => 'Đối tác',
            'fas fa-award' => 'Giải thưởng',
            'fas fa-rocket' => 'Tăng trưởng',
            'fas fa-shield-alt' => 'Bảo mật',
        ];
    }

    /**
     * Tạo animation data attributes
     */
    public function getAnimationAttributesAttribute()
    {
        if (!$this->animation_enabled || !$this->isNumeric()) {
            return '';
        }

        return 'data-animate="true" data-target="' . $this->value . '"';
    }
}
