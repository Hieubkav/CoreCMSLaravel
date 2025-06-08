<?php

namespace App\Observers;

use App\Models\Album;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;

class AlbumObserver
{
    use HandlesFileObserver;

    /**
     * Handle the Album "created" event.
     */
    public function created(Album $album): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Album "updating" event.
     */
    public function updating(Album $album): void
    {
        // Lưu thumbnail cũ để xóa sau khi update
        if ($album->isDirty('thumbnail')) {
            $this->storeOldFile(
                get_class($album),
                $album->id,
                'thumbnail',
                $album->getOriginal('thumbnail')
            );
        }
    }

    /**
     * Handle the Album "updated" event.
     */
    public function updated(Album $album): void
    {
        // Xóa thumbnail cũ nếu có
        if ($album->wasChanged('thumbnail')) {
            $oldFile = $this->getAndDeleteOldFile(
                get_class($album),
                $album->id,
                'thumbnail'
            );

            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the Album "deleted" event.
     */
    public function deleted(Album $album): void
    {
        // Xóa thumbnail khi xóa record
        if ($album->thumbnail && Storage::disk('public')->exists($album->thumbnail)) {
            Storage::disk('public')->delete($album->thumbnail);
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the Album "restored" event.
     */
    public function restored(Album $album): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Album "force deleted" event.
     */
    public function forceDeleted(Album $album): void
    {
        // Xóa thumbnail khi force delete
        if ($album->thumbnail && Storage::disk('public')->exists($album->thumbnail)) {
            Storage::disk('public')->delete($album->thumbnail);
        }

        $this->clearRelatedCache();
    }

    /**
     * Clear related cache
     */
    protected function clearRelatedCache(): void
    {
        // Clear cache liên quan đến albums
        if (method_exists($this, 'clearCache')) {
            $this->clearCache();
        }
    }
}
