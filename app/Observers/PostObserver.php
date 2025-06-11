<?php

namespace App\Observers;

use App\Models\Post;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        // Auto generate slug if empty
        if (empty($post->slug)) {
            $post->slug = $this->generateUniqueSlug($post->title, Post::class);
        }

        // Auto generate SEO fields if empty
        if (empty($post->seo_title)) {
            $post->seo_title = $post->title;
        }

        if (empty($post->seo_description)) {
            $post->seo_description = Str::limit(strip_tags($post->content), 160);
        }

        // Set published_at if not set and status is active
        if ($post->status === 'active' && empty($post->published_at)) {
            $post->published_at = now();
        }
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        // Auto generate slug if empty
        if (empty($post->slug)) {
            $post->slug = $this->generateUniqueSlug($post->title, Post::class, $post->id);
        }

        // Auto generate SEO fields if empty
        if (empty($post->seo_title)) {
            $post->seo_title = $post->title;
        }

        if (empty($post->seo_description)) {
            $post->seo_description = Str::limit(strip_tags($post->content), 160);
        }

        // Handle file cleanup for changed images
        if ($post->isDirty('thumbnail')) {
            $this->deleteFileIfExists($post->getOriginal('thumbnail'));
        }

        if ($post->isDirty('og_image')) {
            $this->deleteFileIfExists($post->getOriginal('og_image'));
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Clean up associated files
        $this->deleteFileIfExists($post->thumbnail);
        $this->deleteFileIfExists($post->og_image);

        // Delete associated images
        $post->images()->each(function ($image) {
            $this->deleteFileIfExists($image->image_path);
            $image->delete();
        });

        $this->clearCache();
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $title, string $model, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = $model::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Clear related cache
     */
    private function clearCache(): void
    {
        Cache::forget('blog_posts');
        Cache::forget('hot_posts');
        Cache::forget('recent_posts');
        Cache::forget('post_categories');
        
        // Clear view cache
        if (function_exists('cache_clear')) {
            cache_clear();
        }
    }
}
