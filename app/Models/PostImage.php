<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image_path',
        'alt_text',
        'caption',
        'order',
        'type',
    ];

    protected $casts = [
        'post_id' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với Post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Lấy URL đầy đủ của image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }

        return asset('images/placeholder.jpg');
    }

    /**
     * Scope theo type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Lấy danh sách types có thể chọn
     */
    public static function getTypeOptions(): array
    {
        return [
            'gallery' => 'Gallery',
            'featured' => 'Featured Image',
            'thumbnail' => 'Thumbnail'
        ];
    }
}
