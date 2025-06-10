<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'image',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer',
    ];

    /**
     * Scope cho timelines active
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
        return $query->orderBy('order')->orderBy('start_date');
    }

    /**
     * Scope sắp xếp theo thời gian
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('start_date')->orderBy('order');
    }

    /**
     * Scope cho events đang diễn ra
     */
    public function scopeOngoing($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $today);
                    });
    }

    /**
     * Scope cho events đã kết thúc
     */
    public function scopeCompleted($query)
    {
        $today = now()->toDateString();
        return $query->whereNotNull('end_date')
                    ->where('end_date', '<', $today);
    }

    /**
     * Scope cho events sắp tới
     */
    public function scopeUpcoming($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '>', $today);
    }

    /**
     * Lấy URL image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        return asset('images/placeholder-timeline.jpg');
    }

    /**
     * Kiểm tra event có đang diễn ra không
     */
    public function isOngoing(): bool
    {
        $today = now()->toDate();
        
        if ($this->start_date > $today) {
            return false; // Chưa bắt đầu
        }

        if ($this->end_date && $this->end_date < $today) {
            return false; // Đã kết thúc
        }

        return true; // Đang diễn ra
    }

    /**
     * Kiểm tra event đã hoàn thành chưa
     */
    public function isCompleted(): bool
    {
        return $this->end_date && $this->end_date < now()->toDate();
    }

    /**
     * Kiểm tra event sắp tới
     */
    public function isUpcoming(): bool
    {
        return $this->start_date > now()->toDate();
    }

    /**
     * Lấy trạng thái timeline
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->isCompleted()) {
            return 'Đã hoàn thành';
        } elseif ($this->isOngoing()) {
            return 'Đang diễn ra';
        } else {
            return 'Sắp tới';
        }
    }

    /**
     * Lấy màu cho trạng thái
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->isCompleted()) {
            return 'text-gray-500';
        } elseif ($this->isOngoing()) {
            return 'text-green-500';
        } else {
            return 'text-blue-500';
        }
    }

    /**
     * Lấy thời gian formatted
     */
    public function getFormattedDateRangeAttribute(): string
    {
        $start = $this->start_date->format('d/m/Y');
        
        if ($this->end_date) {
            $end = $this->end_date->format('d/m/Y');
            return $start . ' - ' . $end;
        }

        return $start;
    }

    /**
     * Lấy duration
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Lấy excerpt của description
     */
    public function getDescriptionExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->description), 150);
    }
}
