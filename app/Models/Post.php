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
        'content',
        'excerpt',
        'seo_title',
        'seo_description',
        'og_image_link',
        'slug',
        'thumbnail',
        'featured_image',
        'post_type',
        'published_at',
        'author_name',
        'tags',
        'is_featured',
        'view_count',
        'order',
        'status',
        'category_id',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
        'tags' => 'array',
        'is_featured' => 'boolean',
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

    // Quan hệ với CatPost (category chính)
    public function category()
    {
        return $this->belongsTo(CatPost::class, 'category_id');
    }

    /**
     * Lấy danh sách post types có thể chọn
     */
    public static function getPostTypeOptions(): array
    {
        return [
            'blog' => 'Bài viết Blog',
            'news' => 'Tin tức',
            'page' => 'Trang nội dung',
            'policy' => 'Chính sách'
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
     * Scope cho posts featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
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
                    ->where('category_id', $this->category_id)
                    ->where('status', 'active')
                    ->published()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
}
