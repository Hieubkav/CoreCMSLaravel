<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display the specified post.
     */
    public function show(string $slug)
    {
        $post = Post::with(['category', 'author', 'images'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

        // Get related posts
        $relatedPosts = $this->getRelatedPosts($post);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Display posts by category.
     */
    public function category(string $slug)
    {
        $category = PostCategory::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $posts = Post::with(['category', 'author'])
            ->where('post_category_id', $category->id)
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.category', compact('category', 'posts'));
    }

    /**
     * Get related posts based on category and tags.
     */
    private function getRelatedPosts(Post $post, int $limit = 3)
    {
        return Cache::remember("related_posts_{$post->id}", 3600, function() use ($post, $limit) {
            $query = Post::with(['category', 'author'])
                ->where('id', '!=', $post->id)
                ->where('status', 'active')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());

            // Prioritize posts from the same category
            if ($post->post_category_id) {
                $query->where('post_category_id', $post->post_category_id);
            }

            return $query->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}
