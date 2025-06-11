<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'order',
        'seo_title',
        'seo_description',
        'og_image',
    ];

    protected $casts = [
        'status' => 'string',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với Post (one-to-many)
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }

    /**
     * Lấy số lượng posts active
     */
    public function getPostsCount()
    {
        return $this->posts()->where('status', 'active')->count();
    }

    /**
     * Lấy posts published
     */
    public function getPublishedPosts()
    {
        return $this->posts()
                    ->where('status', 'active')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc');
    }

    /**
     * Scope cho categories active
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
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Auto generate slug khi tạo mới
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
