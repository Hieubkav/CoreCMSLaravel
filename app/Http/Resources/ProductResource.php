<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'sku' => $this->sku,

            // Pricing
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'discount_percentage' => $this->discount_percentage,
            'formatted_price' => number_format($this->price, 0, ',', '.') . 'đ',
            'formatted_sale_price' => $this->sale_price ? number_format($this->sale_price, 0, ',', '.') . 'đ' : null,

            // Stock
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->stock_quantity > 0,
            'stock_status' => $this->stock_quantity > 0 ? 'in_stock' : 'out_of_stock',

            // Media
            'image' => $this->image,
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->image_path,
                        'alt' => $image->alt_text,
                        'order' => $image->order,
                    ];
                });
            }),

            // Category
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),

            // Attributes & Variants
            'variants' => $this->whenLoaded('variants', function () {
                return $this->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'price' => $variant->price,
                        'stock_quantity' => $variant->stock_quantity,
                        'attributes' => $variant->attributeValues->map(function ($value) {
                            return [
                                'attribute' => $value->attribute->name,
                                'value' => $value->value,
                            ];
                        }),
                    ];
                });
            }),

            // Reviews & Rating
            'average_rating' => $this->average_rating,
            'reviews_count' => $this->reviews_count,
            'rating_breakdown' => $this->when($this->relationLoaded('reviews'), function () {
                $breakdown = [];
                for ($i = 1; $i <= 5; $i++) {
                    $breakdown[$i] = $this->reviews->where('rating', $i)->count();
                }
                return $breakdown;
            }),

            // Product Features
            'is_featured' => $this->is_featured,
            'is_digital' => $this->is_digital,
            'weight' => $this->weight,
            'dimensions' => [
                'length' => $this->length,
                'width' => $this->width,
                'height' => $this->height,
            ],

            // SEO
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,

            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // URLs
            'url' => route('products.show', $this->slug),
            'admin_url' => $this->when(
                $request->user()?->can('view', $this->resource),
                route('filament.admin.resources.products.view', $this->id)
            ),

            // Statistics
            'view_count' => $this->view_count,
            'sales_count' => $this->sales_count,

            // Status
            'status' => $this->status,
            'published_at' => $this->published_at?->toISOString(),
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
                'resource_type' => 'product',
            ],
        ];
    }
}
