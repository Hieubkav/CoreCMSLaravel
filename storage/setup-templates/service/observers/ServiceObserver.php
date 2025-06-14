<?php

namespace App\Observers;

use App\Models\Service;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ServiceObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Service "creating" event.
     */
    public function creating(Service $service): void
    {
        // Auto generate slug if empty
        if (empty($service->slug)) {
            $service->slug = $this->generateUniqueSlug($service->name, Service::class);
        }

        // Auto generate SEO fields if empty
        if (empty($service->seo_title)) {
            $service->seo_title = $service->name . ' - Dịch vụ chuyên nghiệp';
        }

        if (empty($service->seo_description)) {
            $description = strip_tags($service->short_description ?: $service->description ?: '');
            $service->seo_description = $description ? Str::limit($description, 160) : 
                "Dịch vụ {$service->name} chuyên nghiệp với chất lượng cao và giá cả hợp lý.";
        }

        // Auto generate short description if empty
        if (empty($service->short_description) && $service->description) {
            $service->short_description = Str::limit(strip_tags($service->description), 200);
        }
    }

    /**
     * Handle the Service "updating" event.
     */
    public function updating(Service $service): void
    {
        // Auto generate slug if empty
        if (empty($service->slug)) {
            $service->slug = $this->generateUniqueSlug($service->name, Service::class, $service->id);
        }

        // Auto generate SEO fields if empty
        if (empty($service->seo_title)) {
            $service->seo_title = $service->name . ' - Dịch vụ chuyên nghiệp';
        }

        if (empty($service->seo_description)) {
            $description = strip_tags($service->short_description ?: $service->description ?: '');
            $service->seo_description = $description ? Str::limit($description, 160) : 
                "Dịch vụ {$service->name} chuyên nghiệp với chất lượng cao và giá cả hợp lý.";
        }

        // Auto generate short description if empty
        if (empty($service->short_description) && $service->description) {
            $service->short_description = Str::limit(strip_tags($service->description), 200);
        }

        // Handle file cleanup for changed images
        if ($service->isDirty('image')) {
            $this->deleteFileIfExists($service->getOriginal('image'));
        }

        if ($service->isDirty('og_image')) {
            $this->deleteFileIfExists($service->getOriginal('og_image'));
        }
    }

    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        // Clean up associated files
        $this->deleteFileIfExists($service->image);
        $this->deleteFileIfExists($service->og_image);

        // Delete associated images (polymorphic)
        $service->images()->each(function ($image) {
            $this->deleteFileIfExists($image->image_path);
            $image->delete();
        });

        $this->clearCache();
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $name, string $model, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = $model::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Clear related cache
     */
    private function clearCache(): void
    {
        Cache::forget('services_featured');
        Cache::forget('services_by_category');
        Cache::forget('services_popular');
        Cache::forget('services_latest');
        
        // Clear view cache
        if (function_exists('cache_clear')) {
            cache_clear();
        }
    }
}
