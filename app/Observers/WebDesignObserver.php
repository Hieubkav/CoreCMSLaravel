<?php

namespace App\Observers;

use App\Models\WebDesign;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class WebDesignObserver
{
    /**
     * Handle the WebDesign "creating" event.
     */
    public function creating(WebDesign $webDesign): void
    {
        // Set updated_by khi tạo mới
        if (Auth::check()) {
            $webDesign->updated_by = Auth::id();
        }
        
        $webDesign->last_updated_at = now();
    }

    /**
     * Handle the WebDesign "created" event.
     */
    public function created(WebDesign $webDesign): void
    {
        // Clear cache khi tạo mới
        $this->clearCache();
    }

    /**
     * Handle the WebDesign "updating" event.
     */
    public function updating(WebDesign $webDesign): void
    {
        // Set updated_by khi cập nhật
        if (Auth::check()) {
            $webDesign->updated_by = Auth::id();
        }
        
        $webDesign->last_updated_at = now();
    }

    /**
     * Handle the WebDesign "updated" event.
     */
    public function updated(WebDesign $webDesign): void
    {
        // Clear cache khi cập nhật
        $this->clearCache();
    }

    /**
     * Handle the WebDesign "deleted" event.
     */
    public function deleted(WebDesign $webDesign): void
    {
        // Clear cache khi xóa
        $this->clearCache();
    }

    /**
     * Handle the WebDesign "restored" event.
     */
    public function restored(WebDesign $webDesign): void
    {
        // Clear cache khi restore
        $this->clearCache();
    }

    /**
     * Handle the WebDesign "force deleted" event.
     */
    public function forceDeleted(WebDesign $webDesign): void
    {
        // Clear cache khi force delete
        $this->clearCache();
    }

    /**
     * Clear all related caches
     */
    private function clearCache(): void
    {
        // Clear web design cache
        Cache::forget('web_design_settings');
        
        // Clear view cache
        Cache::forget('homepage_sections');
        Cache::forget('homepage_layout');
        
        // Clear other related caches
        $cacheKeys = [
            'web_design_sections',
            'homepage_config',
            'layout_settings',
            'section_order',
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
