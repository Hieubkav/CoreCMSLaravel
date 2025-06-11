<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Hiển thị danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Filter by category
        if ($request->filled('category')) {
            $category = PostCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('post_category_id', $category->id);
            }
        }

        // Filter by post type
        if ($request->filled('type')) {
            $query->where('post_type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default: // latest
                $query->orderBy('published_at', 'desc');
                break;
        }

        $posts = $query->paginate(9);

        // Get categories for filter
        $categories = Cache::remember('post_categories_active', 3600, function() {
            return PostCategory::where('status', 'active')
                ->withCount('posts')
                ->orderBy('order', 'asc')
                ->get();
        });

        return view('blog.index', compact('posts', 'categories'));
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function show($slug)
    {
        $post = Post::with(['category', 'author', 'images'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment view count
        $post->incrementViewCount();

        // Get related posts
        $relatedPosts = $post->getRelatedPosts(3);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Hiển thị bài viết theo danh mục
     */
    public function category($slug)
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
            ->paginate(9);

        return view('blog.category', compact('category', 'posts'));
    }
}
