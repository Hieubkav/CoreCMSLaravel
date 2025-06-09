<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class BulkProductOperation
{
    use AsAction;

    /**
     * Thực hiện bulk operations trên products
     */
    public function handle(string $operation, array $productIds, array $data = []): array
    {
        try {
            DB::beginTransaction();

            $results = match ($operation) {
                'update_status' => $this->bulkUpdateStatus($productIds, $data['status']),
                'update_category' => $this->bulkUpdateCategory($productIds, $data['category_id']),
                'update_prices' => $this->bulkUpdatePrices($productIds, $data),
                'update_stock' => $this->bulkUpdateStock($productIds, $data),
                'delete' => $this->bulkDelete($productIds),
                'duplicate' => $this->bulkDuplicate($productIds, $data),
                'export' => $this->bulkExport($productIds, $data['format'] ?? 'csv'),
                'apply_discount' => $this->bulkApplyDiscount($productIds, $data),
                'update_seo' => $this->bulkUpdateSeo($productIds, $data),
                'update_attributes' => $this->bulkUpdateAttributes($productIds, $data),
                default => throw new \InvalidArgumentException("Unsupported operation: {$operation}")
            };

            DB::commit();

            Log::info("Bulk operation completed", [
                'operation' => $operation,
                'product_count' => count($productIds),
                'success_count' => $results['success_count'],
                'error_count' => $results['error_count'],
            ]);

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Bulk operation failed", [
                'operation' => $operation,
                'error' => $e->getMessage(),
                'product_ids' => $productIds,
            ]);

            throw $e;
        }
    }

    /**
     * Bulk update product status
     */
    private function bulkUpdateStatus(array $productIds, string $status): array
    {
        $validStatuses = ['active', 'inactive', 'draft', 'archived'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $updated = Product::whereIn('id', $productIds)->update(['status' => $status]);

        return [
            'success_count' => $updated,
            'error_count' => count($productIds) - $updated,
            'message' => "Updated status to '{$status}' for {$updated} products",
        ];
    }

    /**
     * Bulk update product category
     */
    private function bulkUpdateCategory(array $productIds, int $categoryId): array
    {
        $category = ProductCategory::findOrFail($categoryId);

        $updated = Product::whereIn('id', $productIds)->update(['category_id' => $categoryId]);

        return [
            'success_count' => $updated,
            'error_count' => count($productIds) - $updated,
            'message' => "Moved {$updated} products to category '{$category->name}'",
        ];
    }

    /**
     * Bulk update prices
     */
    private function bulkUpdatePrices(array $productIds, array $data): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);
                $updateData = [];

                if (isset($data['price_adjustment'])) {
                    $adjustment = $data['price_adjustment'];
                    $type = $data['adjustment_type'] ?? 'percentage'; // percentage or fixed

                    if ($type === 'percentage') {
                        $newPrice = $product->price * (1 + $adjustment / 100);
                    } else {
                        $newPrice = $product->price + $adjustment;
                    }

                    $updateData['price'] = max(0, round($newPrice, 2));
                }

                if (isset($data['sale_price_adjustment'])) {
                    $adjustment = $data['sale_price_adjustment'];
                    $type = $data['sale_adjustment_type'] ?? 'percentage';

                    if ($product->sale_price) {
                        if ($type === 'percentage') {
                            $newSalePrice = $product->sale_price * (1 + $adjustment / 100);
                        } else {
                            $newSalePrice = $product->sale_price + $adjustment;
                        }

                        $updateData['sale_price'] = max(0, round($newSalePrice, 2));
                    }
                }

                if (isset($data['set_price'])) {
                    $updateData['price'] = $data['set_price'];
                }

                if (isset($data['set_sale_price'])) {
                    $updateData['sale_price'] = $data['set_sale_price'];
                }

                if (!empty($updateData)) {
                    $product->update($updateData);
                    $successCount++;
                }

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Updated prices for {$successCount} products",
        ];
    }

    /**
     * Bulk update stock
     */
    private function bulkUpdateStock(array $productIds, array $data): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);
                $oldStock = $product->stock_quantity;
                $newStock = $oldStock;

                if (isset($data['stock_adjustment'])) {
                    $adjustment = $data['stock_adjustment'];
                    $type = $data['adjustment_type'] ?? 'add'; // add, subtract, set

                    $newStock = match ($type) {
                        'add' => $oldStock + $adjustment,
                        'subtract' => $oldStock - $adjustment,
                        'set' => $adjustment,
                        default => $oldStock
                    };
                }

                if (isset($data['set_stock'])) {
                    $newStock = $data['set_stock'];
                }

                $newStock = max(0, $newStock);

                $product->update([
                    'stock_quantity' => $newStock,
                    'in_stock' => $newStock > 0,
                ]);

                // Log inventory change
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'bulk_adjustment',
                    'quantity_before' => $oldStock,
                    'quantity_change' => $newStock - $oldStock,
                    'quantity_after' => $newStock,
                    'reference_type' => 'bulk_operation',
                    'notes' => 'Bulk stock update',
                ]);

                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Updated stock for {$successCount} products",
        ];
    }

    /**
     * Bulk delete products
     */
    private function bulkDelete(array $productIds): array
    {
        $products = Product::whereIn('id', $productIds)->get();
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($products as $product) {
            try {
                // Check if product has orders
                if ($product->orderItems()->exists()) {
                    $product->update(['status' => 'archived']);
                    $successCount++;
                } else {
                    $product->delete();
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$product->id}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Processed {$successCount} products for deletion",
        ];
    }

    /**
     * Bulk duplicate products
     */
    private function bulkDuplicate(array $productIds, array $data): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $duplicatedIds = [];

        foreach ($productIds as $productId) {
            try {
                $originalProduct = Product::with(['variants', 'attributeValues'])->findOrFail($productId);

                $duplicateData = $originalProduct->toArray();
                unset($duplicateData['id'], $duplicateData['created_at'], $duplicateData['updated_at']);

                // Modify name and slug
                $suffix = $data['suffix'] ?? ' - Copy';
                $duplicateData['name'] = $originalProduct->name . $suffix;
                $duplicateData['slug'] = $originalProduct->slug . '-copy-' . time();
                $duplicateData['sku'] = $originalProduct->sku . '-COPY-' . time();

                // Reset stats
                $duplicateData['view_count'] = 0;
                $duplicateData['order_count'] = 0;
                $duplicateData['average_rating'] = 0;
                $duplicateData['review_count'] = 0;

                $newProduct = Product::create($duplicateData);

                // Duplicate variants
                foreach ($originalProduct->variants as $variant) {
                    $variantData = $variant->toArray();
                    unset($variantData['id'], $variantData['created_at'], $variantData['updated_at']);
                    $variantData['product_id'] = $newProduct->id;
                    $variantData['sku'] = $variant->sku . '-COPY-' . time();

                    $newProduct->variants()->create($variantData);
                }

                // Duplicate attribute values
                foreach ($originalProduct->attributeValues as $attributeValue) {
                    $attrData = $attributeValue->toArray();
                    unset($attrData['id'], $attrData['created_at'], $attrData['updated_at']);
                    $attrData['product_id'] = $newProduct->id;

                    $newProduct->attributeValues()->create($attrData);
                }

                $duplicatedIds[] = $newProduct->id;
                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'duplicated_ids' => $duplicatedIds,
            'message' => "Duplicated {$successCount} products",
        ];
    }

    /**
     * Bulk export products
     */
    private function bulkExport(array $productIds, string $format): array
    {
        $products = Product::with(['category', 'variants', 'attributeValues.attribute'])
                          ->whereIn('id', $productIds)
                          ->get();

        $exportData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'category' => $product->category?->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'stock_quantity' => $product->stock_quantity,
                'status' => $product->status,
                'is_featured' => $product->is_featured ? 'Yes' : 'No',
                'weight' => $product->weight,
                'length' => $product->length,
                'width' => $product->width,
                'height' => $product->height,
                'short_description' => $product->short_description,
                'description' => strip_tags($product->description),
                'variants_count' => $product->variants->count(),
                'attributes' => $product->attributeValues->map(function ($attr) {
                    return $attr->attribute->name . ': ' . $attr->value;
                })->implode('; '),
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        $filename = 'products_export_' . now()->format('Y_m_d_H_i_s') . '.' . $format;
        $filepath = storage_path('app/exports/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        if ($format === 'csv') {
            $this->exportToCsv($exportData, $filepath);
        } elseif ($format === 'json') {
            $this->exportToJson($exportData, $filepath);
        } else {
            throw new \InvalidArgumentException("Unsupported export format: {$format}");
        }

        return [
            'success_count' => $products->count(),
            'error_count' => 0,
            'file_path' => $filepath,
            'filename' => $filename,
            'download_url' => route('admin.products.download-export', $filename),
            'message' => "Exported {$products->count()} products to {$format}",
        ];
    }

    /**
     * Export to CSV
     */
    private function exportToCsv(Collection $data, string $filepath): void
    {
        $file = fopen($filepath, 'w');

        // Write headers
        if ($data->isNotEmpty()) {
            fputcsv($file, array_keys($data->first()));
        }

        // Write data
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }

    /**
     * Export to JSON
     */
    private function exportToJson(Collection $data, string $filepath): void
    {
        file_put_contents($filepath, json_encode($data->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Bulk apply discount
     */
    private function bulkApplyDiscount(array $productIds, array $data): array
    {
        $discountPercent = $data['discount_percent'] ?? 0;
        $startDate = $data['start_date'] ?? null;
        $endDate = $data['end_date'] ?? null;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);

                $salePrice = $product->price * (1 - $discountPercent / 100);
                $salePrice = round($salePrice, 2);

                $updateData = ['sale_price' => $salePrice];

                if ($startDate) {
                    $updateData['sale_start_date'] = $startDate;
                }

                if ($endDate) {
                    $updateData['sale_end_date'] = $endDate;
                }

                $product->update($updateData);
                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Applied {$discountPercent}% discount to {$successCount} products",
        ];
    }

    /**
     * Bulk update SEO
     */
    private function bulkUpdateSeo(array $productIds, array $data): array
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);
                $updateData = [];

                if (isset($data['seo_title_template'])) {
                    $updateData['seo_title'] = $this->replacePlaceholders($data['seo_title_template'], $product);
                }

                if (isset($data['seo_description_template'])) {
                    $updateData['seo_description'] = $this->replacePlaceholders($data['seo_description_template'], $product);
                }

                if (!empty($updateData)) {
                    $product->update($updateData);
                    $successCount++;
                }

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Updated SEO for {$successCount} products",
        ];
    }

    /**
     * Bulk update attributes
     */
    private function bulkUpdateAttributes(array $productIds, array $data): array
    {
        $attributeId = $data['attribute_id'];
        $value = $data['value'];

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($productIds as $productId) {
            try {
                $product = Product::findOrFail($productId);

                $product->attributeValues()->updateOrCreate(
                    ['attribute_id' => $attributeId],
                    ['value' => $value]
                );

                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'message' => "Updated attribute for {$successCount} products",
        ];
    }

    /**
     * Replace placeholders in templates
     */
    private function replacePlaceholders(string $template, Product $product): string
    {
        $placeholders = [
            '{product_name}' => $product->name,
            '{category_name}' => $product->category?->name ?? '',
            '{price}' => number_format($product->price, 0, ',', '.') . 'đ',
            '{sku}' => $product->sku,
            '{brand}' => $product->brand ?? '',
            '{short_description}' => $product->short_description,
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }
}
