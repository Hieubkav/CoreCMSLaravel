<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'is_active',
        'status',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope cho schedules active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope cho schedule đang active
     */
    public function scopeCurrentlyActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Lấy URL image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return asset('images/placeholder-schedule.jpg');
    }

    /**
     * Tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($schedule) {
            if (empty($schedule->slug)) {
                $schedule->slug = static::generateUniqueSlug($schedule->name);
            }
        });

        static::updating(function ($schedule) {
            if ($schedule->isDirty('name') && empty($schedule->slug)) {
                $schedule->slug = static::generateUniqueSlug($schedule->name);
            }

            // Nếu schedule này được set active, deactivate tất cả schedule khác
            if ($schedule->isDirty('is_active') && $schedule->is_active) {
                static::where('id', '!=', $schedule->id)->update(['is_active' => false]);
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
     * Lấy schedule hiện tại đang active
     */
    public static function getCurrent(): ?self
    {
        return static::active()->currentlyActive()->first();
    }

    /**
     * Set schedule này làm active và deactivate các schedule khác
     */
    public function setAsActive(): void
    {
        // Deactivate tất cả schedules khác
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate schedule này
        $this->update(['is_active' => true]);
    }

    /**
     * Lấy excerpt của description
     */
    public function getDescriptionExcerptAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }
}
