<?php

namespace App\Observers;

use App\Models\Post;
use App\Providers\ViewServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostObserver
{
    protected $oldThumbnails = []; // Array to store old thumbnails by post ID
    protected $oldOgImages = []; // Array to store old OG images by post ID

    /**
     * Handle the Post "creating" event.
     */
    public function creating(Post $post): void
    {
        $this->generateSeoFields($post);
        $this->generateSlug($post);
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        $this->generateSeoFields($post);
        $this->generateSlug($post);

        // Xử lý xóa file cũ
        if ($post->isDirty('thumbnail') && $post->getOriginal('thumbnail')) {
            // Lưu đường dẫn hình ảnh cũ vào mảng tạm thời để xóa sau
            $this->oldThumbnails[$post->id] = $post->getOriginal('thumbnail');
        }

        if ($post->isDirty('og_image_link') && $post->getOriginal('og_image_link')) {
            // Lưu đường dẫn OG image cũ vào mảng tạm thời để xóa sau
            $this->oldOgImages[$post->id] = $post->getOriginal('og_image_link');
        }
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // Nếu có hình ảnh thumbnail cũ cần xóa
        if (isset($this->oldThumbnails[$post->id])) {
            Storage::disk('public')->delete($this->oldThumbnails[$post->id]);
            unset($this->oldThumbnails[$post->id]);
        }

        // Nếu có OG image cũ cần xóa
        if (isset($this->oldOgImages[$post->id])) {
            Storage::disk('public')->delete($this->oldOgImages[$post->id]);
            unset($this->oldOgImages[$post->id]);
        }

        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Xóa hình ảnh thumbnail khi xóa bài viết
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        // Xóa OG image khi xóa bài viết
        if ($post->og_image_link) {
            Storage::disk('public')->delete($post->og_image_link);
        }

        // Xóa tất cả hình ảnh liên quan trong PostImage
        foreach ($post->images as $postImage) {
            if ($postImage->image_link) {
                Storage::disk('public')->delete($postImage->image_link);
            }
        }

        ViewServiceProvider::refreshCache('posts');
    }

    /**
     * Tự động sinh SEO fields nếu để trống
     */
    private function generateSeoFields(Post $post): void
    {
        // Tự động sinh SEO title nếu để trống
        if (empty($post->seo_title)) {
            $post->seo_title = $post->title;
        }

        // Tự động sinh SEO description nếu để trống
        if (empty($post->seo_description)) {
            $post->seo_description = Str::limit(strip_tags($post->content), 160);
        }

        // Tự động sinh OG image nếu để trống và có thumbnail
        if (empty($post->og_image_link) && !empty($post->thumbnail)) {
            $post->og_image_link = asset('storage/' . $post->thumbnail);
        }
    }

    /**
     * Tự động sinh slug nếu để trống
     */
    private function generateSlug(Post $post): void
    {
        if (empty($post->slug)) {
            $baseSlug = Str::slug($post->title);
            $slug = $baseSlug;
            $counter = 1;

            // Kiểm tra slug trùng lặp
            while (Post::where('slug', $slug)->where('id', '!=', $post->id ?? 0)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $post->slug = $slug;
        }
    }
}
