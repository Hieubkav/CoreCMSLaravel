<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'review_text',
        'rating',
        'avatar',
        'company',
        'position',
        'status',
        'order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Scope cho testimonials active
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
        return $query->orderBy('order')->orderBy('customer_name');
    }

    /**
     * Scope theo rating
     */
    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope rating cao
     */
    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Lấy URL avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        return asset('images/default-avatar.jpg');
    }

    /**
     * Lấy tên đầy đủ với company
     */
    public function getFullNameAttribute()
    {
        $name = $this->customer_name;
        
        if ($this->position && $this->company) {
            $name .= ', ' . $this->position . ' tại ' . $this->company;
        } elseif ($this->position) {
            $name .= ', ' . $this->position;
        } elseif ($this->company) {
            $name .= ' - ' . $this->company;
        }

        return $name;
    }

    /**
     * Lấy excerpt của review
     */
    public function getReviewExcerptAttribute()
    {
        return \Str::limit($this->review_text, 150);
    }

    /**
     * Lấy stars HTML
     */
    public function getStarsHtmlAttribute()
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                $html .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        return $html;
    }

    /**
     * Validation rules cho rating
     */
    public static function getRatingOptions(): array
    {
        return [
            1 => '1 sao - Rất tệ',
            2 => '2 sao - Tệ',
            3 => '3 sao - Bình thường',
            4 => '4 sao - Tốt',
            5 => '5 sao - Xuất sắc',
        ];
    }

    /**
     * Lấy màu cho rating
     */
    public function getRatingColorAttribute()
    {
        return match($this->rating) {
            1, 2 => 'text-red-500',
            3 => 'text-yellow-500',
            4, 5 => 'text-green-500',
            default => 'text-gray-500'
        };
    }

    /**
     * Kiểm tra có thông tin công ty không
     */
    public function hasCompanyInfo(): bool
    {
        return !empty($this->company) || !empty($this->position);
    }
}
