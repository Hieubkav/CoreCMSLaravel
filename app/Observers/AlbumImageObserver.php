<?php

namespace App\Observers;

use App\Models\AlbumImage;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;

class AlbumImageObserver
{
    use HandlesFileObserver;

    /**
     * Handle the AlbumImage "created" event.
     */
    public function created(AlbumImage $albumImage): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the AlbumImage "updating" event.
     */
    public function updating(AlbumImage $albumImage): void
    {
        // Lưu image_path cũ để xóa sau khi update
        if ($albumImage->isDirty('image_path')) {
            $this->storeOldFile(
                get_class($albumImage),
                $albumImage->id,
                'image_path',
                $albumImage->getOriginal('image_path')
            );
        }
    }

    /**
     * Handle the AlbumImage "updated" event.
     */
    public function updated(AlbumImage $albumImage): void
    {
        // Xóa image_path cũ nếu có
        if ($albumImage->wasChanged('image_path')) {
            $oldFile = $this->getAndDeleteOldFile(
                get_class($albumImage),
                $albumImage->id,
                'image_path'
            );

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the AlbumImage "deleted" event.
     */
    public function deleted(AlbumImage $albumImage): void
    {
        // Xóa image_path khi xóa record
        if ($albumImage->image_path && Storage::disk('public')->exists($albumImage->image_path)) {
            Storage::disk('public')->delete($albumImage->image_path);
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the AlbumImage "restored" event.
     */
    public function restored(AlbumImage $albumImage): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the AlbumImage "force deleted" event.
     */
    public function forceDeleted(AlbumImage $albumImage): void
    {
        // Xóa image_path khi force delete
        if ($albumImage->image_path && Storage::disk('public')->exists($albumImage->image_path)) {
            Storage::disk('public')->delete($albumImage->image_path);
        }

        $this->clearRelatedCache();
    }

    /**
     * Clear related cache
     */
    protected function clearRelatedCache(): void
    {
        // Clear cache liên quan đến album images
        if (method_exists($this, 'clearCache')) {
            $this->clearCache();
        }
    }
}
