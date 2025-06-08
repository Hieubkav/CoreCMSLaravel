<?php

namespace App\Observers;

use App\Models\Slider;
use App\Actions\DeleteFileFromStorage;
use App\Actions\ClearViewCache;

class SliderObserver
{

    /**
     * Handle the Slider "created" event.
     */
    public function created(Slider $slider): void
    {
        // Hình ảnh đã được xử lý trong form Filament
    }

    /**
     * Handle the Slider "updated" event.
     */
    public function updated(Slider $slider): void
    {
        if ($slider->wasChanged('image_link')) {
            DeleteFileFromStorage::oldFile($slider, 'image_link');
        }
        ClearViewCache::forModel($slider);
    }

    /**
     * Handle the Slider "deleted" event.
     */
    public function deleted(Slider $slider): void
    {
        DeleteFileFromStorage::fromModel($slider, 'image_link');
        ClearViewCache::forModel($slider);
    }

    /**
     * Handle the Slider "restored" event.
     */
    public function restored(Slider $slider): void
    {
        //
    }

    /**
     * Handle the Slider "force deleted" event.
     */
    public function forceDeleted(Slider $slider): void
    {
        //
    }
}
