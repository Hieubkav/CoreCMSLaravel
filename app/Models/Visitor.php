<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'content_id', // Sửa từ course_id thành content_id
        'session_id',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Relationship với Content (generic)
     */
    public function content()
    {
        // Generic relationship - có thể là Course, Post, hoặc content khác
        return $this->belongsTo(\App\Models\Post::class, 'content_id');
    }

    /**
     * Relationship với Course (nếu content_id là course)
     */
    public function course()
    {
        if (class_exists('\App\Models\Course')) {
            return $this->belongsTo(\App\Models\Course::class, 'content_id');
        }
        return null;
    }

    /**
     * Scope để lấy unique visitors
     */
    public function scopeUniqueVisitors($query)
    {
        return $query->distinct('ip_address');
    }

    /**
     * Scope để lấy visitors hôm nay
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    /**
     * Scope để lấy visitors trong tuần
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('visited_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope để lấy visitors trong tháng
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
                    ->whereYear('visited_at', now()->year);
    }
}
