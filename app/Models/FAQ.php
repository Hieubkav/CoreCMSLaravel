<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'status',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Scope cho FAQs active
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
        return $query->orderBy('order')->orderBy('question');
    }

    /**
     * Scope theo category
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Lấy danh sách categories
     */
    public static function getCategories(): array
    {
        return static::whereNotNull('category')
                    ->where('category', '!=', '')
                    ->distinct()
                    ->pluck('category')
                    ->toArray();
    }

    /**
     * Lấy FAQs theo category
     */
    public static function getByCategory(): array
    {
        $faqs = static::active()->ordered()->get();
        $grouped = [];

        foreach ($faqs as $faq) {
            $category = $faq->category ?: 'Chung';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $faq;
        }

        return $grouped;
    }

    /**
     * Tìm kiếm FAQs
     */
    public static function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
                    ->where(function ($q) use ($query) {
                        $q->where('question', 'like', "%{$query}%")
                          ->orWhere('answer', 'like', "%{$query}%");
                    })
                    ->ordered()
                    ->get();
    }

    /**
     * Lấy excerpt của answer
     */
    public function getAnswerExcerptAttribute()
    {
        return \Str::limit(strip_tags($this->answer), 100);
    }
}
