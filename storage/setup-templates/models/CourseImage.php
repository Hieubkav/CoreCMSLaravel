<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'image_link',
        'alt_text',
        'is_main',
        'order',
        'status',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'order' => 'integer',
        'status' => 'string',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    // Accessors
    public function getFullImageUrlAttribute()
    {
        if (str_starts_with($this->image_link, 'http')) {
            return $this->image_link;
        }

        // Kiểm tra file có tồn tại không
        if (Storage::disk('public')->exists($this->image_link)) {
            return asset('storage/' . $this->image_link);
        }

        return asset('images/placeholder-course.jpg');
    }

    public function getAltTextAttribute($value)
    {
        return $value ?: $this->course->title ?? 'Course Image';
    }

    // Helper methods
    public function exists()
    {
        if (str_starts_with($this->image_link, 'http')) {
            return true; // Assume external URLs exist
        }
        return Storage::disk('public')->exists($this->image_link);
    }
}
