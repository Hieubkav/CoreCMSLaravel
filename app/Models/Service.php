<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'duration',
        'category',
        'features',
        'image',
        'gallery_images',
        'status',
        'order',
        'is_featured',
        'seo_title',
        'seo_description',
        'og_image',
        'meta_keywords',
        'user_id',
    ];

    protected $casts = [
        'features' => 'array',
        'gallery_images' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    protected $appends = [
        'image_url',
        'og_image_url',
        'formatted_price',
        'excerpt',
    ];

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured services
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by price range
     */
    public function scopeByPriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Polymorphic relationship with images
     */
    public function images(): MorphMany
    {
        return $this->morphMany(ServiceImage::class, 'imageable');
    }

    /**
     * Get gallery images
     */
    public function galleryImages()
    {
        return $this->images()->where('type', 'gallery')->orderBy('order');
    }

    /**
     * Get cover image
     */
    public function coverImage()
    {
        return $this->images()->where('type', 'cover')->first();
    }

    /**
     * Get main image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        $coverImage = $this->coverImage();
        if ($coverImage) {
            return asset('storage/' . $coverImage->image_path);
        }

        return asset('images/service-placeholder.jpg');
    }

    /**
     * Get OG image URL
     */
    public function getOgImageUrlAttribute(): string
    {
        if ($this->og_image) {
            return asset('storage/' . $this->og_image);
        }

        return $this->image_url;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->price === null || $this->price == 0) {
            return 'Liên hệ';
        }

        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Get excerpt from description
     */
    public function getExcerptAttribute(): string
    {
        if ($this->short_description) {
            return $this->short_description;
        }

        return Str::limit(strip_tags($this->description), 150);
    }

    /**
     * Check if service has features
     */
    public function hasFeatures(): bool
    {
        return !empty($this->features) && is_array($this->features);
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            'web-development' => 'Phát triển Website',
            'mobile-app' => 'Ứng dụng Mobile',
            'ecommerce' => 'Thương mại điện tử',
            'seo-marketing' => 'SEO & Marketing',
            'design' => 'Thiết kế',
            'consulting' => 'Tư vấn',
            'maintenance' => 'Bảo trì & Hỗ trợ',
            'other' => 'Khác',
        ];
    }

    /**
     * Get category name
     */
    public function getCategoryNameAttribute(): string
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Get duration options
     */
    public static function getDurationOptions(): array
    {
        return [
            '1-3-days' => '1-3 ngày',
            '1-week' => '1 tuần',
            '2-weeks' => '2 tuần',
            '1-month' => '1 tháng',
            '2-3-months' => '2-3 tháng',
            '3-6-months' => '3-6 tháng',
            '6-months+' => '6 tháng+',
            'ongoing' => 'Liên tục',
        ];
    }

    /**
     * Get duration name
     */
    public function getDurationNameAttribute(): string
    {
        $durations = self::getDurationOptions();
        return $durations[$this->duration] ?? $this->duration;
    }

    /**
     * Get price ranges for filtering
     */
    public static function getPriceRanges(): array
    {
        return [
            '0-5000000' => 'Dưới 5 triệu',
            '5000000-10000000' => '5-10 triệu',
            '10000000-20000000' => '10-20 triệu',
            '20000000-50000000' => '20-50 triệu',
            '50000000-100000000' => '50-100 triệu',
            '100000000+' => 'Trên 100 triệu',
        ];
    }

    /**
     * Get related services
     */
    public function getRelatedServices($limit = 3)
    {
        return self::where('id', '!=', $this->id)
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('category', $this->category)
                      ->orWhereBetween('price', [
                          $this->price * 0.7,
                          $this->price * 1.3
                      ]);
            })
            ->ordered()
            ->limit($limit)
            ->get();
    }

    /**
     * Search services
     */
    public static function search($query)
    {
        return self::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            });
    }
}
