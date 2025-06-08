<?php

namespace App\Observers;

use App\Models\CourseMaterial;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CourseMaterialObserver
{
    use HandlesFileObserver;

    /**
     * Handle the CourseMaterial "updating" event.
     */
    public function updating(CourseMaterial $courseMaterial): void
    {
        // Lưu file cũ để xóa sau khi update
        if ($courseMaterial->isDirty('file_path') && $courseMaterial->getOriginal('file_path')) {
            $this->storeOldFile(
                CourseMaterial::class,
                $courseMaterial->id,
                'file_path',
                $courseMaterial->getOriginal('file_path')
            );
        }
    }

    /**
     * Handle the CourseMaterial "updated" event.
     */
    public function updated(CourseMaterial $courseMaterial): void
    {
        // Xóa file cũ nếu có
        $oldFile = $this->getAndDeleteOldFile(CourseMaterial::class, $courseMaterial->id, 'file_path');
        if ($oldFile) {
            $this->deleteFileIfExists($oldFile);
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the CourseMaterial "deleted" event.
     */
    public function deleted(CourseMaterial $courseMaterial): void
    {
        // Xóa file tài liệu
        $this->deleteFileIfExists($courseMaterial->file_path);
        $this->clearRelatedCache();
    }

    /**
     * Handle the CourseMaterial "force deleted" event.
     */
    public function forceDeleted(CourseMaterial $courseMaterial): void
    {
        // Xóa file tài liệu
        $this->deleteFileIfExists($courseMaterial->file_path);
        $this->clearRelatedCache();
    }

    /**
     * Xóa file nếu tồn tại
     */
    private function deleteFileIfExists(?string $filePath): void
    {
        if (!$filePath) return;

        // Xóa file trong storage
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    /**
     * Clear cache liên quan
     */
    private function clearRelatedCache(): void
    {
        Cache::forget('storefront_courses');
        Cache::forget('navigation_data');
    }
}
