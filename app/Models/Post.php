<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'post_type',
        'status',
        'order',
        'seo_title',
        'seo_description',
        'og_image',
        'is_hot',
        'view_count',
        'published_at',
        'post_category_id',
        'user_id',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
        'is_hot' => 'boolean',
        'view_count' => 'integer',
        'published_at' => 'datetime',
    ];

    // Quan hệ với PostImage
    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    // Quan hệ với MenuItem
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    // Quan hệ với PostCategory
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    // Quan hệ với User (author)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy danh sách post types có thể chọn
     */
    public static function getPostTypeOptions(): array
    {
        return [
            'tin_tuc' => 'Tin tức',
            'dich_vu' => 'Dịch vụ',
            'trang_don' => 'Trang đơn'
        ];
    }

    /**
     * Scope cho posts đã publish
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope cho posts hot/featured
     */
    public function scopeHot($query)
    {
        return $query->where('is_hot', true);
    }

    /**
     * Scope theo post type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('post_type', $type);
    }

    /**
     * Lấy excerpt tự động nếu không có
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Tự động tạo excerpt từ content
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Lấy featured image hoặc fallback về thumbnail
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }

        if ($this->thumbnail) {
            return Storage::url($this->thumbnail);
        }

        return asset('images/placeholder.jpg');
    }

    /**
     * Tăng view count
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Lấy posts liên quan
     */
    public function getRelatedPosts($limit = 3)
    {
        return static::where('id', '!=', $this->id)
                    ->where('post_category_id', $this->post_category_id)
                    ->where('status', 'active')
                    ->published()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
