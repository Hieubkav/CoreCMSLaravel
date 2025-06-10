<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Service extends Model
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
        'seo_title',
        'seo_description',
        'og_image',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope cho services active
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
        return $this->icon ?: 'fas fa-cog';
    }

    /**
     * Tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name') && empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name);
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
     * Lấy SEO title
     */
    public function getSeoTitleAttribute($value)
    {
        return $value ?: $this->name . ' - Dịch vụ';
    }

    /**
     * Lấy SEO description
     */
    public function getSeoDescriptionAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->description), 160) ?: 
               "Tìm hiểu về dịch vụ {$this->name} chất lượng cao.";
    }

    /**
     * Lấy OG image
     */
    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return Storage::url($this->og_image);
        }

        return asset('images/service-default.jpg');
    }

    /**
     * Lấy danh sách icons phổ biến cho services
     */
    public static function getPopularIcons(): array
    {
        return [
            'fas fa-cog' => 'Cài đặt/Cấu hình',
            'fas fa-tools' => 'Công cụ',
            'fas fa-laptop-code' => 'Lập trình',
            'fas fa-mobile-alt' => 'Mobile',
            'fas fa-cloud' => 'Cloud',
            'fas fa-shield-alt' => 'Bảo mật',
            'fas fa-chart-line' => 'Phân tích',
            'fas fa-users' => 'Tư vấn',
            'fas fa-headset' => 'Hỗ trợ',
            'fas fa-rocket' => 'Tối ưu',
            'fas fa-database' => 'Dữ liệu',
            'fas fa-server' => 'Server',
            'fas fa-globe' => 'Web',
            'fas fa-paint-brush' => 'Thiết kế',
            'fas fa-bullhorn' => 'Marketing',
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
