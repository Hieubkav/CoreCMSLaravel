<?php

namespace App\Observers;

use App\Models\CourseImage;
use App\Traits\HandlesFileObserver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class CourseImageObserver
{
    use HandlesFileObserver;

    /**
     * Handle the CourseImage "updating" event.
     */
    public function updating(CourseImage $courseImage): void
    {
        // Lưu file cũ để xóa sau khi update
        if ($courseImage->isDirty('image_link') && $courseImage->getOriginal('image_link')) {
            $this->storeOldFile(
                CourseImage::class,
                $courseImage->id,
                'image_link',
                $courseImage->getOriginal('image_link')
            );
        }
    }

    /**
     * Handle the CourseImage "updated" event.
     */
    public function updated(CourseImage $courseImage): void
    {
        // Xóa file cũ nếu có
        $oldFile = $this->getAndDeleteOldFile(CourseImage::class, $courseImage->id, 'image_link');
        if ($oldFile) {
            $this->deleteFileIfExists($oldFile);
        }

        $this->clearRelatedCache();
    }

    /**
     * Handle the CourseImage "deleted" event.
     */
    public function deleted(CourseImage $courseImage): void
    {
        // Xóa file ảnh
        $this->deleteFileIfExists($courseImage->image_link);
        $this->clearRelatedCache();
    }

    /**
     * Handle the CourseImage "force deleted" event.
     */
    public function forceDeleted(CourseImage $courseImage): void
    {
        // Xóa file ảnh
        $this->deleteFileIfExists($courseImage->image_link);
        $this->clearRelatedCache();
    }

    /**
     * Xóa file nếu tồn tại
     */
    private function deleteFileIfExists(?string $filePath): void
    {
        if (!$filePath) return;

        // Chỉ xóa file trong storage, không xóa URL external
        if (!str_starts_with($filePath, 'http') && Storage::exists($filePath)) {
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
