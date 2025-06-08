<?php

namespace App\Observers;

use App\Models\Setting;
use App\Actions\DeleteFileFromStorage;
use App\Actions\ClearViewCache;

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
            DeleteFileFromStorage::oldFile($setting, 'logo_link');
        }

        if ($setting->wasChanged('favicon_link')) {
            DeleteFileFromStorage::oldFile($setting, 'favicon_link');
        }

        if ($setting->wasChanged('og_image_link')) {
            DeleteFileFromStorage::oldFile($setting, 'og_image_link');
        }

        ClearViewCache::forModel($setting);
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        // Delete all associated files
        DeleteFileFromStorage::fromModel($setting, 'logo_link');
        DeleteFileFromStorage::fromModel($setting, 'favicon_link');
        DeleteFileFromStorage::fromModel($setting, 'og_image_link');

        ClearViewCache::forModel($setting);
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
