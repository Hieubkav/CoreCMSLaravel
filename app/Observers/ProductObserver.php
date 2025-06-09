<?php

namespace App\Observers;

use App\Models\Product;
use App\Actions\ClearViewCache;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        ClearViewCache::run(Product::class);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Handle file changes
        if ($product->isDirty('featured_image')) {
            $this->deleteOldFile($product->getOriginal('featured_image'));
        }

        if ($product->isDirty('og_image')) {
            $this->deleteOldFile($product->getOriginal('og_image'));
        }

        // Handle gallery images array
        if ($product->isDirty('gallery_images')) {
            $oldImages = $product->getOriginal('gallery_images') ?: [];
            $newImages = $product->gallery_images ?: [];

            $deletedImages = array_diff($oldImages, $newImages);
            foreach ($deletedImages as $image) {
                $this->deleteOldFile($image);
            }
        }

        ClearViewCache::run(Product::class);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // Delete associated files
        $this->deleteOldFile($product->featured_image);
        $this->deleteOldFile($product->og_image);

        // Delete gallery images
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                $this->deleteOldFile($image);
            }
        }

        ClearViewCache::run(Product::class);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        ClearViewCache::run(Product::class);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        // Delete associated files
        $this->deleteOldFile($product->featured_image);
        $this->deleteOldFile($product->og_image);

        // Delete gallery images
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                $this->deleteOldFile($image);
            }
        }

        ClearViewCache::run(Product::class);
    }

    /**
     * Delete old file from storage
     */
    private function deleteOldFile(?string $filePath): void
    {
        if ($filePath && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }
}
