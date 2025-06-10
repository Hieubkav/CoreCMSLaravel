<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'website_url',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope cho brands active
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
     * Lấy URL logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return Storage::url($this->logo);
        }

        return asset('images/placeholder-logo.png');
    }

    /**
     * Tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = static::generateUniqueSlug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && empty($brand->slug)) {
                $brand->slug = static::generateUniqueSlug($brand->name);
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
     * Kiểm tra có website không
     */
    public function hasWebsite(): bool
    {
        return !empty($this->website_url);
    }

    /**
     * Lấy domain từ website URL
     */
    public function getDomainAttribute()
    {
        if (!$this->website_url) {
            return null;
        }

        return parse_url($this->website_url, PHP_URL_HOST);
    }
}
