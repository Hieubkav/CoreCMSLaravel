<?php

namespace App\Observers;

use App\Models\MenuItem;
use App\Actions\ClearViewCache;

class MenuItemObserver
{
    /**
     * Handle the MenuItem "created" event.
     */
    public function created(MenuItem $menuItem): void
    {
        // Clear view cache khi tạo menu item mới
        ClearViewCache::run(MenuItem::class);
    }

    /**
     * Handle the MenuItem "updated" event.
     */
    public function updated(MenuItem $menuItem): void
    {
        // Validate parent relationship để tránh circular reference
        if ($menuItem->isDirty('parent_id') && $menuItem->parent_id) {
            if (!$menuItem->canSetParent($menuItem->parent_id)) {
                throw new \InvalidArgumentException('Không thể tạo circular reference trong menu');
            }
        }

        // Clear view cache khi cập nhật menu
        ClearViewCache::run(MenuItem::class);
    }

    /**
     * Handle the MenuItem "deleted" event.
     */
    public function deleted(MenuItem $menuItem): void
    {
        // Khi xóa menu item, set parent_id của children về null
        MenuItem::where('parent_id', $menuItem->id)->update(['parent_id' => null]);

        // Clear view cache
        ClearViewCache::run(MenuItem::class);
    }

    /**
     * Handle the MenuItem "restored" event.
     */
    public function restored(MenuItem $menuItem): void
    {
        ClearViewCache::run(MenuItem::class);
    }

    /**
     * Handle the MenuItem "force deleted" event.
     */
    public function forceDeleted(MenuItem $menuItem): void
    {
        // Xóa tất cả children khi force delete
        MenuItem::where('parent_id', $menuItem->id)->delete();

        ClearViewCache::run(MenuItem::class);
    }
}
