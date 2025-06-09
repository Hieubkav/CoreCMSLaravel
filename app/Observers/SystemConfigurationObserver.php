<?php

namespace App\Observers;

use App\Models\SystemConfiguration;
use App\Actions\DeleteFileFromStorage;
use App\Actions\ClearViewCache;

class SystemConfigurationObserver
{
    /**
     * Handle the SystemConfiguration "created" event.
     */
    public function created(SystemConfiguration $systemConfiguration): void
    {
        // Clear view cache khi tạo mới
        ClearViewCache::run(SystemConfiguration::class);
    }

    /**
     * Handle the SystemConfiguration "updated" event.
     */
    public function updated(SystemConfiguration $systemConfiguration): void
    {
        // Xóa favicon cũ nếu có thay đổi
        if ($systemConfiguration->isDirty('favicon_path')) {
            $oldFavicon = $systemConfiguration->getOriginal('favicon_path');
            if ($oldFavicon) {
                DeleteFileFromStorage::run($oldFavicon);
            }
        }

        // Clear view cache khi cập nhật
        ClearViewCache::run(SystemConfiguration::class);
    }

    /**
     * Handle the SystemConfiguration "deleted" event.
     */
    public function deleted(SystemConfiguration $systemConfiguration): void
    {
        // Xóa favicon khi xóa record
        if ($systemConfiguration->favicon_path) {
            DeleteFileFromStorage::run($systemConfiguration->favicon_path);
        }

        // Clear view cache
        ClearViewCache::run(SystemConfiguration::class);
    }

    /**
     * Handle the SystemConfiguration "restored" event.
     */
    public function restored(SystemConfiguration $systemConfiguration): void
    {
        ClearViewCache::run(SystemConfiguration::class);
    }

    /**
     * Handle the SystemConfiguration "force deleted" event.
     */
    public function forceDeleted(SystemConfiguration $systemConfiguration): void
    {
        // Xóa favicon khi force delete
        if ($systemConfiguration->favicon_path) {
            DeleteFileFromStorage::run($systemConfiguration->favicon_path);
        }

        ClearViewCache::run(SystemConfiguration::class);
    }
}
