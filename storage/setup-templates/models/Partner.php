<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'website_url',
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope cho partners active
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

        static::creating(function ($partner) {
            if (empty($partner->slug)) {
                $partner->slug = static::generateUniqueSlug($partner->name);
            }
        });

        static::updating(function ($partner) {
            if ($partner->isDirty('name') && empty($partner->slug)) {
                $partner->slug = static::generateUniqueSlug($partner->name);
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

    /**
     * Lấy excerpt của description
     */
    public function getDescriptionExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 100);
    }
}
