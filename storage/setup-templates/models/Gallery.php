<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'images',
        'description',
        'status',
        'order',
        'seo_title',
        'seo_description',
        'og_image',
    ];

    protected $casts = [
        'images' => 'array',
        'order' => 'integer',
    ];

    /**
     * Scope cho galleries active
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
     * Lấy URLs của tất cả images
     */
    public function getImageUrlsAttribute()
    {
        if (empty($this->images)) {
            return [];
        }

        return array_map(function ($imagePath) {
            return Storage::url($imagePath);
        }, $this->images);
    }

    /**
     * Lấy image đầu tiên làm thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        if (empty($this->images)) {
            return asset('images/placeholder.jpg');
        }

        return Storage::url($this->images[0]);
    }

    /**
     * Đếm số lượng images
     */
    public function getImageCountAttribute()
    {
        return count($this->images ?? []);
    }

    /**
     * Tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = static::generateUniqueSlug($gallery->name);
            }
        });

        static::updating(function ($gallery) {
            if ($gallery->isDirty('name') && empty($gallery->slug)) {
                $gallery->slug = static::generateUniqueSlug($gallery->name);
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
     * Lấy SEO title
     */
    public function getSeoTitleAttribute($value)
    {
        return $value ?: $this->name . ' - Gallery';
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
               "Xem bộ sưu tập ảnh {$this->name} với {$this->image_count} hình ảnh chất lượng cao.";
    }

    /**
     * Lấy OG image
     */
    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return Storage::url($this->og_image);
        }

        return $this->thumbnail_url;
    }

    /**
     * Thêm image vào gallery
     */
    public function addImage(string $imagePath): void
    {
        $images = $this->images ?? [];
        $images[] = $imagePath;
        $this->update(['images' => $images]);
    }

    /**
     * Xóa image khỏi gallery
     */
    public function removeImage(string $imagePath): void
    {
        $images = $this->images ?? [];
        $images = array_filter($images, fn($img) => $img !== $imagePath);
        $this->update(['images' => array_values($images)]);
    }

    /**
     * Sắp xếp lại images
     */
    public function reorderImages(array $imageOrder): void
    {
        $currentImages = $this->images ?? [];
        $reorderedImages = [];

        foreach ($imageOrder as $imagePath) {
            if (in_array($imagePath, $currentImages)) {
                $reorderedImages[] = $imagePath;
            }
        }

        $this->update(['images' => $reorderedImages]);
    }
}
