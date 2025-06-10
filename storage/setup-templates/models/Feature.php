<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'link',
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope cho features active
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
     * Lấy icon với fallback
     */
    public function getIconClassAttribute()
    {
        return $this->icon ?: 'fas fa-star';
    }

    /**
     * Tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($feature) {
            if (empty($feature->slug)) {
                $feature->slug = static::generateUniqueSlug($feature->name);
            }
        });

        static::updating(function ($feature) {
            if ($feature->isDirty('name') && empty($feature->slug)) {
                $feature->slug = static::generateUniqueSlug($feature->name);
            }
        });
    }

    /**
     * Tạo slug unique
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Kiểm tra có link không
     */
    public function hasLink(): bool
    {
        return !empty($this->link);
    }

    /**
     * Lấy danh sách icons phổ biến cho features
     */
    public static function getPopularIcons(): array
    {
        return [
            'fas fa-star' => 'Tính năng nổi bật',
            'fas fa-check' => 'Hoàn thành',
            'fas fa-bolt' => 'Nhanh chóng',
            'fas fa-shield-alt' => 'Bảo mật',
            'fas fa-heart' => 'Yêu thích',
            'fas fa-thumbs-up' => 'Tích cực',
            'fas fa-magic' => 'Đặc biệt',
            'fas fa-gem' => 'Chất lượng cao',
            'fas fa-crown' => 'Premium',
            'fas fa-fire' => 'Hot',
            'fas fa-trophy' => 'Thành tích',
            'fas fa-medal' => 'Giải thưởng',
            'fas fa-rocket' => 'Hiệu suất',
            'fas fa-lightbulb' => 'Ý tưởng',
            'fas fa-puzzle-piece' => 'Tích hợp',
        ];
    }

    /**
     * Lấy excerpt của description
     */
    public function getDescriptionExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 100);
    }
}
