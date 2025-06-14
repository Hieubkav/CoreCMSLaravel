<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'position',
        'email',
        'phone',
        'social_links',
        'status',
        'order',
        'seo_title',
        'seo_description',
        'og_image',
    ];

    protected $casts = [
        'social_links' => 'array',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với StaffImage (polymorphic)
     */
    public function images()
    {
        return $this->morphMany(StaffImage::class, 'imageable');
    }

    /**
     * Lấy avatar chính
     */
    public function avatar()
    {
        return $this->morphOne(StaffImage::class, 'imageable')->where('type', 'avatar');
    }

    /**
     * Lấy gallery images
     */
    public function galleryImages()
    {
        return $this->morphMany(StaffImage::class, 'imageable')
                    ->where('type', 'gallery')
                    ->orderBy('order');
    }

    /**
     * Scope cho staff active
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
     * Lấy URL ảnh đại diện
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        // Fallback về avatar từ polymorphic relationship
        if ($this->avatar && $this->avatar->image_path) {
            return Storage::url($this->avatar->image_path);
        }

        return asset('images/default-avatar.jpg');
    }

    /**
     * Tự động tạo slug khi tạo mới
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            if (empty($staff->slug)) {
                $staff->slug = static::generateUniqueSlug($staff->name);
            }
        });

        static::updating(function ($staff) {
            if ($staff->isDirty('name') && empty($staff->slug)) {
                $staff->slug = static::generateUniqueSlug($staff->name);
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
     * Lấy danh sách positions có thể chọn
     */
    public static function getPositionOptions(): array
    {
        return [
            'Giám đốc' => 'Giám đốc',
            'Phó Giám đốc' => 'Phó Giám đốc',
            'Trưởng phòng' => 'Trưởng phòng',
            'Phó phòng' => 'Phó phòng',
            'Chuyên viên' => 'Chuyên viên',
            'Nhân viên' => 'Nhân viên',
            'Thực tập sinh' => 'Thực tập sinh',
            'Cố vấn' => 'Cố vấn',
            'Chuyên gia' => 'Chuyên gia',
        ];
    }

    /**
     * Lấy danh sách social platforms
     */
    public static function getSocialPlatforms(): array
    {
        return [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'zalo' => 'Zalo',
            'telegram' => 'Telegram',
            'email' => 'Email',
            'website' => 'Website',
        ];
    }

    /**
     * Lấy social link theo platform
     */
    public function getSocialLink(string $platform): ?string
    {
        return $this->social_links[$platform] ?? null;
    }

    /**
     * Kiểm tra có social link không
     */
    public function hasSocialLinks(): bool
    {
        return !empty($this->social_links) && count(array_filter($this->social_links)) > 0;
    }

    /**
     * Lấy icon cho social platform
     */
    public static function getSocialIcon(string $platform): string
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'linkedin' => 'fab fa-linkedin',
            'twitter' => 'fab fa-twitter',
            'instagram' => 'fab fa-instagram',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'zalo' => 'fas fa-comment',
            'telegram' => 'fab fa-telegram',
            'email' => 'fas fa-envelope',
            'website' => 'fas fa-globe',
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }

    /**
     * Lấy SEO title
     */
    public function getSeoTitleAttribute($value)
    {
        return $value ?: $this->name . ' - ' . $this->position;
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
               "Thông tin về {$this->name}, {$this->position} tại công ty.";
    }

    /**
     * Lấy OG image
     */
    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return Storage::url($this->og_image);
        }

        return $this->image_url;
    }
}
