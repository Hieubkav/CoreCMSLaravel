<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'products' => $this->collection->map(function ($product) use ($request) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'sku' => $product->sku,

                    // Pricing
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                    'discount_percentage' => $product->discount_percentage,
                    'formatted_price' => number_format($product->price, 0, ',', '.') . 'đ',
                    'formatted_sale_price' => $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . 'đ' : null,

                    // Stock
                    'stock_quantity' => $product->stock_quantity,
                    'in_stock' => $product->stock_quantity > 0,
                    'stock_status' => $product->stock_quantity > 0 ? 'in_stock' : 'out_of_stock',

                    // Media
                    'image' => $product->image,
                    'thumbnail' => $product->image, // For backward compatibility

                    // Category
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ] : null,

                    // Rating
                    'average_rating' => $product->average_rating,
                    'reviews_count' => $product->reviews_count,

                    // Features
                    'is_featured' => $product->is_featured,
                    'is_digital' => $product->is_digital,

                    // URLs
                    'url' => route('products.show', $product->slug),

                    // Timestamps
                    'created_at' => $product->created_at?->toISOString(),
                    'updated_at' => $product->updated_at?->toISOString(),
                ];
            }),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'api_version' => '1.0',
                'resource_type' => 'product_collection',
                'collection_size' => $this->collection->count(),
            ],
        ];
    }

    /**
     * Customize the pagination information for the resource.
     *
     * @return array<string, mixed>
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'pagination' => [
                'current_page' => $default['meta']['current_page'],
                'last_page' => $default['meta']['last_page'],
                'per_page' => $default['meta']['per_page'],
                'total' => $default['meta']['total'],
                'from' => $default['meta']['from'],
                'to' => $default['meta']['to'],
                'has_more_pages' => $default['meta']['current_page'] < $default['meta']['last_page'],
            ],
            'links' => $default['links'],
        ];
    }
}
