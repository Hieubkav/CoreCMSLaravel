<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductApiController extends Controller
{
    /**
     * Display a listing of products with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Product::with(['category', 'reviews', 'variants'])
                           ->where('status', 'active');

            // Apply filters
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('category_slug')) {
                $category = ProductCategory::where('slug', $request->category_slug)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }

            if ($request->filled('featured')) {
                $query->where('is_featured', $request->boolean('featured'));
            }

            if ($request->filled('in_stock')) {
                $query->where('stock_quantity', '>', 0);
            }

            if ($request->filled('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->filled('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%");
                });
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSorts = ['name', 'price', 'created_at', 'rating', 'stock_quantity'];
            if (in_array($sortBy, $allowedSorts)) {
                if ($sortBy === 'rating') {
                    $query->orderBy('average_rating', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 50); // Max 50 items per page
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => new ProductCollection($products),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'links' => [
                    'first' => $products->url(1),
                    'last' => $products->url($products->lastPage()),
                    'prev' => $products->previousPageUrl(),
                    'next' => $products->nextPageUrl(),
                ],
                'filters_applied' => $request->only([
                    'category_id', 'category_slug', 'featured', 'in_stock',
                    'price_min', 'price_max', 'search', 'sort_by', 'sort_order'
                ]),
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $product = Product::with([
                'category',
                'reviews.user',
                'variants.attributeValues.attribute',
                'images'
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            // Increment view count
            $product->increment('view_count');

            // Get related products
            $relatedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->where('status', 'active')
                                    ->limit(4)
                                    ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => new ProductResource($product),
                    'related_products' => ProductResource::collection($relatedProducts),
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Get product categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = ProductCategory::where('status', 'active')
                                       ->withCount('products')
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
                        'image' => $category->image,
                        'products_count' => $category->products_count,
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

    /**
     * Search products with suggestions
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:1|max:255',
                'limit' => 'integer|min:1|max:20',
            ]);

            $query = $request->q;
            $limit = $request->get('limit', 10);

            $products = Product::where('status', 'active')
                             ->where(function ($q) use ($query) {
                                 $q->where('name', 'like', "%{$query}%")
                                   ->orWhere('description', 'like', "%{$query}%")
                                   ->orWhere('short_description', 'like', "%{$query}%");
                             })
                             ->with('category')
                             ->limit($limit)
                             ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'query' => $query,
                    'results' => $products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'price' => $product->price,
                            'sale_price' => $product->sale_price,
                            'image' => $product->image,
                            'category' => $product->category?->name,
                            'url' => route('products.show', $product->slug),
                        ];
                    }),
                    'total_found' => $products->count(),
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'timestamp' => now()->toISOString(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }
}
