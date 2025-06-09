<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\CatPost;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PostApiController extends Controller
{
    /**
     * Display a listing of posts with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Post::with(['category', 'author'])
                        ->where('status', 'active');

            // Apply filters
            if ($request->filled('category_id')) {
                $query->where('cat_post_id', $request->category_id);
            }

            if ($request->filled('category_slug')) {
                $category = CatPost::where('slug', $request->category_slug)->first();
                if ($category) {
                    $query->where('cat_post_id', $category->id);
                }
            }

            if ($request->filled('featured')) {
                $query->where('is_featured', $request->boolean('featured'));
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }

            if ($request->filled('author_id')) {
                $query->where('author_id', $request->author_id);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSorts = ['title', 'created_at', 'updated_at', 'view_count'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50);
            $posts = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'posts' => collect($posts->items())->map(function ($post) {
                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                            'slug' => $post->slug,
                            'excerpt' => $post->excerpt,
                            'thumbnail' => $post->thumbnail,
                            'author' => [
                                'id' => $post->author?->id,
                                'name' => $post->author?->name,
                            ],
                            'category' => [
                                'id' => $post->category?->id,
                                'name' => $post->category?->name,
                                'slug' => $post->category?->slug,
                            ],
                            'is_featured' => $post->is_featured,
                            'view_count' => $post->view_count,
                            'created_at' => $post->created_at?->toISOString(),
                            'url' => route('posts.show', $post->slug),
                        ];
                    }),
                ],
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch posts',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Display the specified post
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $post = Post::with(['category', 'author'])
                       ->where('slug', $slug)
                       ->where('status', 'active')
                       ->first();

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            // Increment view count
            $post->increment('view_count');

            // Get related posts
            $relatedPosts = Post::where('cat_post_id', $post->cat_post_id)
                              ->where('id', '!=', $post->id)
                              ->where('status', 'active')
                              ->limit(4)
                              ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'post' => [
                        'id' => $post->id,
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'content' => $post->content,
                        'excerpt' => $post->excerpt,
                        'thumbnail' => $post->thumbnail,
                        'author' => [
                            'id' => $post->author?->id,
                            'name' => $post->author?->name,
                            'email' => $post->author?->email,
                        ],
                        'category' => [
                            'id' => $post->category?->id,
                            'name' => $post->category?->name,
                            'slug' => $post->category?->slug,
                        ],
                        'is_featured' => $post->is_featured,
                        'view_count' => $post->view_count,
                        'meta_title' => $post->meta_title,
                        'meta_description' => $post->meta_description,
                        'meta_keywords' => $post->meta_keywords,
                        'created_at' => $post->created_at?->toISOString(),
                        'updated_at' => $post->updated_at?->toISOString(),
                    ],
                    'related_posts' => $relatedPosts->map(function ($relatedPost) {
                        return [
                            'id' => $relatedPost->id,
                            'title' => $relatedPost->title,
                            'slug' => $relatedPost->slug,
                            'excerpt' => $relatedPost->excerpt,
                            'thumbnail' => $relatedPost->thumbnail,
                            'created_at' => $relatedPost->created_at?->toISOString(),
                            'url' => route('posts.show', $relatedPost->slug),
                        ];
                    }),
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Get post categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = CatPost::where('status', 'active')
                                ->withCount('posts')
                                ->orderBy('order')
                                ->orderBy('name')
                                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'posts_count' => $category->posts_count,
                        'order' => $category->order,
                    ];
                }),
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }
}
