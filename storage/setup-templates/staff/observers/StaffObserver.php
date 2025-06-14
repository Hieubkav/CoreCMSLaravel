<?php

namespace App\Observers;

use App\Models\Staff;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class StaffObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Staff "creating" event.
     */
    public function creating(Staff $staff): void
    {
        // Auto generate slug if empty
        if (empty($staff->slug)) {
            $staff->slug = $this->generateUniqueSlug($staff->name, Staff::class);
        }

        // Auto generate SEO fields if empty
        if (empty($staff->seo_title)) {
            $staff->seo_title = $staff->name . ' - ' . ($staff->position ?: 'Nhân viên');
        }

        if (empty($staff->seo_description)) {
            $description = strip_tags($staff->description ?: '');
            $staff->seo_description = $description ? Str::limit($description, 160) : 
                "Thông tin về {$staff->name}, {$staff->position} tại công ty.";
        }
    }

    /**
     * Handle the Staff "updating" event.
     */
    public function updating(Staff $staff): void
    {
        // Auto generate slug if empty
        if (empty($staff->slug)) {
            $staff->slug = $this->generateUniqueSlug($staff->name, Staff::class, $staff->id);
        }

        // Auto generate SEO fields if empty
        if (empty($staff->seo_title)) {
            $staff->seo_title = $staff->name . ' - ' . ($staff->position ?: 'Nhân viên');
        }

        if (empty($staff->seo_description)) {
            $description = strip_tags($staff->description ?: '');
            $staff->seo_description = $description ? Str::limit($description, 160) : 
                "Thông tin về {$staff->name}, {$staff->position} tại công ty.";
        }

        // Handle file cleanup for changed images
        if ($staff->isDirty('image')) {
            $this->deleteFileIfExists($staff->getOriginal('image'));
        }

        if ($staff->isDirty('og_image')) {
            $this->deleteFileIfExists($staff->getOriginal('og_image'));
        }
    }

    /**
     * Handle the Staff "created" event.
     */
    public function created(Staff $staff): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Staff "updated" event.
     */
    public function updated(Staff $staff): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Staff "deleted" event.
     */
    public function deleted(Staff $staff): void
    {
        // Clean up associated files
        $this->deleteFileIfExists($staff->image);
        $this->deleteFileIfExists($staff->og_image);

        // Delete associated images (polymorphic)
        $staff->images()->each(function ($image) {
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
        Cache::forget('staff_members');
        Cache::forget('active_staff');
        Cache::forget('staff_by_position');
        
        // Clear view cache
        if (function_exists('cache_clear')) {
            cache_clear();
        }
    }
}
