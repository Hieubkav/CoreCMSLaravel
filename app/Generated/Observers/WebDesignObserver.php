<?php

namespace App\Generated\Observers;

use App\Generated\Models\WebDesign;
use App\Traits\HandlesFileObserver;

class WebDesignObserver
{
    use HandlesFileObserver;

    /**
     * Handle the WebDesign "created" event.
     */
    public function created(WebDesign $webDesign): void
    {
        //
    }

    /**
     * Handle the WebDesign "updated" event.
     */
    public function updated(WebDesign $webDesign): void
    {
        // Handle file cleanup for preview_image
        $this->handleFileUpdate($webDesign, 'preview_image');
    }

    /**
     * Handle the WebDesign "deleted" event.
     */
    public function deleted(WebDesign $webDesign): void
    {
        // Clean up preview image file
        $this->handleFileDelete($webDesign, 'preview_image');
        
        // Clear cache
        $webDesign->clearCache();
    }

    /**
     * Handle the WebDesign "restored" event.
     */
    public function restored(WebDesign $webDesign): void
    {
        //
    }

    /**
     * Handle the WebDesign "force deleted" event.
     */
    public function forceDeleted(WebDesign $webDesign): void
    {
        // Clean up preview image file
        $this->handleFileDelete($webDesign, 'preview_image');
        
        // Clear cache
        $webDesign->clearCache();
    }
}
