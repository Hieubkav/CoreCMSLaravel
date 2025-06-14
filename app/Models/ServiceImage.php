<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ServiceImage extends Model
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

    protected $appends = [
        'image_url',
    ];

    /**
     * Get the parent imageable model (Service).
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return asset('images/service-placeholder.jpg');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get available image types
     */
    public static function getImageTypes(): array
    {
        return [
            'cover' => 'Ảnh bìa',
            'gallery' => 'Thư viện',
            'thumbnail' => 'Ảnh thu nhỏ',
            'before_after' => 'Trước/Sau',
        ];
    }

    /**
     * Get type name
     */
    public function getTypeNameAttribute(): string
    {
        $types = self::getImageTypes();
        return $types[$this->type] ?? $this->type;
    }
}
