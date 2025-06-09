<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {

    // Products API
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductApiController::class, 'index'])->name('api.products.index');
        Route::get('/categories', [ProductApiController::class, 'categories'])->name('api.products.categories');
        Route::get('/search', [ProductApiController::class, 'search'])->name('api.products.search');
        Route::get('/{slug}', [ProductApiController::class, 'show'])->name('api.products.show');
    });

    // Posts API
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostApiController::class, 'index'])->name('api.posts.index');
        Route::get('/categories', [PostApiController::class, 'categories'])->name('api.posts.categories');
        Route::get('/{slug}', [PostApiController::class, 'show'])->name('api.posts.show');
    });

    // Cart API (session-based, no auth required)
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartApiController::class, 'index'])->name('api.cart.index');
        Route::post('/', [CartApiController::class, 'store'])->name('api.cart.store');
        Route::put('/{id}', [CartApiController::class, 'update'])->name('api.cart.update');
        Route::delete('/{id}', [CartApiController::class, 'destroy'])->name('api.cart.destroy');
        Route::delete('/', [CartApiController::class, 'clear'])->name('api.cart.clear');
    });

    // Search suggestions endpoint
    Route::get('/search/suggestions', function (Request $request) {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = [];

        // Search products
        $products = \App\Models\Product::where('name', 'like', "%{$query}%")
                                     ->where('status', 'active')
                                     ->limit(5)
                                     ->get(['id', 'name', 'slug', 'image']);

        foreach ($products as $product) {
            $suggestions[] = [
                'title' => $product->name,
                'type' => 'Sản phẩm',
                'url' => route('products.show', $product->slug),
                'image' => $product->image,
            ];
        }

        // Search posts
        $posts = \App\Models\Post::where('title', 'like', "%{$query}%")
                                ->where('status', 'active')
                                ->limit(3)
                                ->get(['id', 'title', 'slug', 'thumbnail']);

        foreach ($posts as $post) {
            $suggestions[] = [
                'title' => $post->title,
                'type' => 'Bài viết',
                'url' => route('posts.show', $post->slug),
                'image' => $post->thumbnail,
            ];
        }

        return response()->json([
            'suggestions' => $suggestions,
            'query' => $query,
            'timestamp' => now()->toISOString(),
        ]);
    })->name('api.search.suggestions');

});

// Protected API routes (authentication required)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // User-specific cart operations (when logged in)
    Route::prefix('user')->group(function () {
        Route::get('/cart', [CartApiController::class, 'index'])->name('api.user.cart');
        Route::post('/cart/merge', function (Request $request) {
            // Merge guest cart with user cart after login
            $sessionId = $request->session()->getId();
            $userId = auth()->id();

            \App\Models\Cart::mergeGuestCart($sessionId, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Cart merged successfully',
                'timestamp' => now()->toISOString(),
            ]);
        })->name('api.user.cart.merge');
    });

    // Admin API routes (admin only)
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        // Admin statistics
        Route::get('/stats', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    'products_count' => \App\Models\Product::count(),
                    'posts_count' => \App\Models\Post::count(),
                    'orders_count' => \App\Models\Order::count(),
                    'users_count' => \App\Models\User::count(),
                    'active_products' => \App\Models\Product::where('status', 'active')->count(),
                    'active_posts' => \App\Models\Post::where('status', 'active')->count(),
                    'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
                ],
                'timestamp' => now()->toISOString(),
            ]);
        })->name('api.admin.stats');

        // Clear caches
        Route::post('/cache/clear', function () {
            try {
                \Illuminate\Support\Facades\Cache::flush();
                \App\Providers\ViewServiceProvider::clearCache();

                return response()->json([
                    'success' => true,
                    'message' => 'Cache cleared successfully',
                    'timestamp' => now()->toISOString(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear cache',
                    'error' => $e->getMessage(),
                    'timestamp' => now()->toISOString(),
                ], 500);
            }
        })->name('api.admin.cache.clear');
    });
});

// Webhook routes (no authentication, but should be secured by signature verification)
Route::prefix('webhooks')->group(function () {

    // Payment webhooks
    Route::post('/payment/{provider}', [WebhookController::class, 'handlePayment'])
         ->name('webhooks.payment')
         ->where('provider', 'vnpay|momo|stripe|paypal');

    // Inventory webhooks
    Route::post('/inventory', [WebhookController::class, 'handleInventory'])
         ->name('webhooks.inventory');

    // Shipping webhooks
    Route::post('/shipping/{provider}', [WebhookController::class, 'handleShipping'])
         ->name('webhooks.shipping')
         ->where('provider', 'ghn|viettel_post|vnpost');

    // Generic webhook endpoint for testing
    Route::post('/test', function (Request $request) {
        Log::info('Test webhook received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook received successfully',
            'timestamp' => now()->toISOString(),
        ]);
    })->name('webhooks.test');
});
