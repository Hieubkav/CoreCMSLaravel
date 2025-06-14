<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StaffImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'image_path',
        'alt_text',
        'title',
        'type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Quan hệ polymorphic
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Lấy URL ảnh
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }

        return asset('images/placeholder.jpg');
    }

    /**
     * Lấy danh sách image types
     */
    public static function getImageTypes(): array
    {
        return [
            'avatar' => 'Ảnh đại diện',
            'gallery' => 'Ảnh gallery',
            'cover' => 'Ảnh bìa',
        ];
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
        return $query->orderBy('order');
    }
}
