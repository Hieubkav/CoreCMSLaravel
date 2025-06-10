<?php

namespace App\Traits;

use App\Actions\Module\CheckModuleVisibility;

trait ModuleVisibility
{
    /**
     * Kiểm tra resource có nên hiển thị trong navigation không
     */
    public static function shouldRegisterNavigation(): bool
    {
        // Nếu không có method này, mặc định hiển thị
        if (!method_exists(static::class, 'getModuleName')) {
            return true;
        }

        // Nếu có method getModuleName, sử dụng module name để kiểm tra
        $moduleName = static::getModuleName();
        return (new CheckModuleVisibility())->handle($moduleName);
    }

    /**
     * Kiểm tra user có thể truy cập resource không
     */
    public static function canAccess(): bool
    {
        // Kiểm tra permission cơ bản trước
        $canAccess = parent::canAccess();
        
        if (!$canAccess) {
            return false;
        }

        // Kiểm tra module visibility
        return static::shouldRegisterNavigation();
    }

    /**
     * Override navigation items để ẩn khi module bị disable
     */
    public static function getNavigationItems(): array
    {
        if (!static::shouldRegisterNavigation()) {
            return [];
        }

        return parent::getNavigationItems();
    }

    /**
     * Override cluster navigation để ẩn khi module bị disable
     */
    public static function getClusterNavigationItems(): array
    {
        if (!static::shouldRegisterNavigation()) {
            return [];
        }

        return parent::getClusterNavigationItems();
    }
}
