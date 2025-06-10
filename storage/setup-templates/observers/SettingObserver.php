<?php

namespace App\Observers;

use App\Models\Setting;
use App\Actions\File\DeleteFileFromStorage;
use App\Actions\Cache\ClearViewCache;

class SettingObserver
{

    /**
     * Handle the Setting "created" event.
     */
    public function created(Setting $setting): void
    {
        // Hình ảnh đã được xử lý trong form Filament
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        // Delete old files when changed
        if ($setting->wasChanged('logo_link')) {
            $oldValue = $setting->getOriginal('logo_link');
            if ($oldValue) {
                DeleteFileFromStorage::run($oldValue);
            }
        }

        if ($setting->wasChanged('favicon_link')) {
            $oldValue = $setting->getOriginal('favicon_link');
            if ($oldValue) {
                DeleteFileFromStorage::run($oldValue);
            }
        }

        if ($setting->wasChanged('og_image_link')) {
            $oldValue = $setting->getOriginal('og_image_link');
            if ($oldValue) {
                DeleteFileFromStorage::run($oldValue);
            }
        }

        if ($setting->wasChanged('placeholder_image')) {
            $oldValue = $setting->getOriginal('placeholder_image');
            if ($oldValue) {
                DeleteFileFromStorage::run($oldValue);
            }
        }

        // Clear cache if exists
        if (class_exists(ClearViewCache::class)) {
            try {
                ClearViewCache::forModel($setting);
            } catch (\Exception $e) {
                // Ignore cache clear errors
            }
        }
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        // Delete all associated files
        if ($setting->logo_link) {
            DeleteFileFromStorage::run($setting->logo_link);
        }
        if ($setting->favicon_link) {
            DeleteFileFromStorage::run($setting->favicon_link);
        }
        if ($setting->og_image_link) {
            DeleteFileFromStorage::run($setting->og_image_link);
        }
        if ($setting->placeholder_image) {
            DeleteFileFromStorage::run($setting->placeholder_image);
        }

        // Clear cache if exists
        if (class_exists(ClearViewCache::class)) {
            try {
                ClearViewCache::forModel($setting);
            } catch (\Exception $e) {
                // Ignore cache clear errors
            }
        }
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        //
    }

    /**
     * Handle the Setting "force deleted" event.
     */
    public function forceDeleted(Setting $setting): void
    {
        //
    }
}
