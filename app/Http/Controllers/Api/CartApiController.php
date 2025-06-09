<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Actions\CalculateCartTotal;
use App\Actions\CalculateTax;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{
    /**
     * Get cart contents
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $sessionId = $request->session()->getId();

            $cartItems = Cart::with(['product', 'variant'])
                               ->where(function ($query) use ($userId, $sessionId) {
                                   if ($userId) {
                                       $query->where('user_id', $userId);
                                   } else {
                                       $query->where('session_id', $sessionId);
                                   }
                               })
                               ->get();

            // Calculate cart totals
            $cartData = $this->calculateCartTotals($cartItems, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'variant_id' => $item->variant_id,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total_price' => $item->total_price,
                            'product' => [
                                'id' => $item->product->id,
                                'name' => $item->product->name,
                                'slug' => $item->product->slug,
                                'image' => $item->product->image,
                                'stock_quantity' => $item->product->stock_quantity,
                                'max_quantity' => min($item->product->stock_quantity, 10),
                            ],
                            'variant' => $item->variant ? [
                                'id' => $item->variant->id,
                                'sku' => $item->variant->sku,
                                'attributes' => $item->variant->attributeValues->map(function ($value) {
                                    return [
                                        'attribute' => $value->attribute->name,
                                        'value' => $value->value,
                                    ];
                                }),
                            ] : null,
                            'created_at' => $item->created_at?->toISOString(),
                        ];
                    }),
                    'totals' => $cartData,
                    'items_count' => $cartItems->count(),
                    'total_quantity' => $cartItems->sum('quantity'),
                ],
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'variant_id' => 'nullable|exists:product_variants,id',
                'quantity' => 'required|integer|min:1|max:10',
            ]);

            $userId = Auth::id();
            $sessionId = $request->session()->getId();
            $productId = $request->product_id;
            $variantId = $request->variant_id;
            $quantity = $request->quantity;

            // Check product availability
            $product = Product::find($productId);
            if (!$product || $product->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not available',
                    'timestamp' => now()->toISOString(),
                ], 400);
            }

            if ($product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock',
                    'available_quantity' => $product->stock_quantity,
                    'timestamp' => now()->toISOString(),
                ], 400);
            }

            // Check if item already exists in cart
            $existingItem = Cart::where('product_id', $productId)
                                  ->where('variant_id', $variantId)
                                  ->where(function ($query) use ($userId, $sessionId) {
                                      if ($userId) {
                                          $query->where('user_id', $userId);
                                      } else {
                                          $query->where('session_id', $sessionId);
                                      }
                                  })
                                  ->first();

            if ($existingItem) {
                // Update quantity
                $newQuantity = $existingItem->quantity + $quantity;
                if ($newQuantity > $product->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Insufficient stock.',
                        'current_quantity' => $existingItem->quantity,
                        'available_quantity' => $product->stock_quantity,
                        'timestamp' => now()->toISOString(),
                    ], 400);
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                ]);

                $cartItem = $existingItem;
            } else {
                // Create new cart item
                $price = $product->sale_price ?: $product->price;
                $cartItem = Cart::create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            // Get updated cart totals
            $cartItems = Cart::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->get();

            $cartData = $this->calculateCartTotals($cartItems, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => [
                    'item' => [
                        'id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'total_price' => $cartItem->total_price,
                    ],
                    'cart_totals' => $cartData,
                    'items_count' => $cartItems->count(),
                    'total_quantity' => $cartItems->sum('quantity'),
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
                'message' => 'Failed to add item to cart',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:10',
            ]);

            $userId = Auth::id();
            $sessionId = $request->session()->getId();

            $cartItem = Cart::where('id', $id)
                              ->where(function ($query) use ($userId, $sessionId) {
                                  if ($userId) {
                                      $query->where('user_id', $userId);
                                  } else {
                                      $query->where('session_id', $sessionId);
                                  }
                              })
                              ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found',
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            // Check stock availability
            $product = $cartItem->product;
            if ($product->stock_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock',
                    'available_quantity' => $product->stock_quantity,
                    'timestamp' => now()->toISOString(),
                ], 400);
            }

            $cartItem->update([
                'quantity' => $request->quantity,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => [
                    'item' => [
                        'id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                        'total_price' => $cartItem->total_price,
                    ],
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
                'message' => 'Failed to update cart item',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $userId = Auth::id();
            $sessionId = $request->session()->getId();

            $cartItem = Cart::where('id', $id)
                              ->where(function ($query) use ($userId, $sessionId) {
                                  if ($userId) {
                                      $query->where('user_id', $userId);
                                  } else {
                                      $query->where('session_id', $sessionId);
                                  }
                              })
                              ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found',
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove cart item',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $sessionId = $request->session()->getId();

            Cart::where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Calculate cart totals using CalculateCartTotal action
     */
    private function calculateCartTotals($cartItems, ?int $userId = null): array
    {
        $items = $cartItems->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'category_id' => $item->product->category_id ?? 0,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->total_price,
                'weight' => $item->product->weight ?? 0,
            ];
        })->toArray();

        return CalculateCartTotal::run($items, $userId);
    }
}
