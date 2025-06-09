<?php

namespace App\Observers;

use App\Models\Staff;
use App\Actions\DeleteFileFromStorage;
use App\Actions\ClearViewCache;

class StaffObserver
{
    /**
     * Handle the Staff "created" event.
     */
    public function created(Staff $staff): void
    {
        ClearViewCache::run(Staff::class);
    }

    /**
     * Handle the Staff "updated" event.
     */
    public function updated(Staff $staff): void
    {
        // Xóa ảnh cũ nếu có thay đổi
        if ($staff->isDirty('image')) {
            $oldImage = $staff->getOriginal('image');
            if ($oldImage) {
                DeleteFileFromStorage::run($oldImage);
            }
        }

        if ($staff->isDirty('og_image')) {
            $oldOgImage = $staff->getOriginal('og_image');
            if ($oldOgImage) {
                DeleteFileFromStorage::run($oldOgImage);
            }
        }

        ClearViewCache::run(Staff::class);
    }

    /**
     * Handle the Staff "deleted" event.
     */
    public function deleted(Staff $staff): void
    {
        // Xóa ảnh chính
        if ($staff->image) {
            DeleteFileFromStorage::run($staff->image);
        }

        // Xóa OG image
        if ($staff->og_image) {
            DeleteFileFromStorage::run($staff->og_image);
        }

        // Xóa tất cả ảnh liên quan (polymorphic)
        foreach ($staff->images as $image) {
            if ($image->image_path) {
                DeleteFileFromStorage::run($image->image_path);
            }
        }

        ClearViewCache::run(Staff::class);
    }

    /**
     * Handle the Staff "restored" event.
     */
    public function restored(Staff $staff): void
    {
        ClearViewCache::run(Staff::class);
    }

    /**
     * Handle the Staff "force deleted" event.
     */
    public function forceDeleted(Staff $staff): void
    {
        // Xóa tất cả files khi force delete
        if ($staff->image) {
            DeleteFileFromStorage::run($staff->image);
        }

        if ($staff->og_image) {
            DeleteFileFromStorage::run($staff->og_image);
        }

        // Xóa tất cả ảnh liên quan
        foreach ($staff->images as $image) {
            if ($image->image_path) {
                DeleteFileFromStorage::run($image->image_path);
            }
        }

        ClearViewCache::run(Staff::class);
    }
}
