<?php

namespace App\Services;

use App\Models\SetupModule;
use Illuminate\Support\Facades\Cache;

class ModuleVisibilityService
{
    /**
     * Cache key cho module status
     */
    private const CACHE_KEY = 'enabled_modules';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Mapping giữa module names và Filament resources (dựa trên resources thực tế)
     */
    private static array $moduleResourceMapping = [
        'user_roles' => [
            'App\Filament\Admin\Resources\UserResource',
        ],
        'blog_posts' => [
            'App\Filament\Admin\Resources\PostResource',
            'App\Filament\Admin\Resources\PostCategoryResource',
        ],
        'staff' => [
            'App\Filament\Admin\Resources\StaffResource',
            'App\Filament\Admin\Resources\ScheduleResource',
        ],
        'content_sections' => [
            'App\Filament\Admin\Resources\SliderResource',
            'App\Filament\Admin\Resources\TestimonialResource',
            'App\Filament\Admin\Resources\GalleryResource',
            'App\Filament\Admin\Resources\FAQResource',
            'App\Filament\Admin\Resources\FeatureResource',
            'App\Filament\Admin\Resources\ServiceResource',
            'App\Filament\Admin\Resources\StatisticResource',
            'App\Filament\Admin\Resources\TimelineResource',
            'App\Filament\Admin\Resources\PartnerResource',
        ],
        'ecommerce' => [
            'App\Filament\Admin\Resources\ProductResource',
            'App\Filament\Admin\Resources\ProductCategoryResource',
            'App\Filament\Admin\Resources\OrderResource',
            'App\Filament\Admin\Resources\BrandResource',
            'App\Filament\Admin\Resources\CouponResource',
        ],
        'layout_components' => [
            'App\Filament\Admin\Resources\MenuItemResource',
        ],
        'settings_expansion' => [
            // Settings sẽ được quản lý qua SystemConfigurationResource
        ],
        'web_design_management' => [
            // Web design management sẽ được quản lý qua pages
        ],
        'advanced_features' => [
            // Advanced features chưa có resources cụ thể
        ],
    ];

    /**
     * Lấy danh sách modules đã được enable
     */
    public static function getEnabledModules(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                // Lấy từ session trước (trong quá trình setup)
                $moduleConfigs = session('module_configs', []);
                if (!empty($moduleConfigs)) {
                    return array_keys(array_filter($moduleConfigs, function($config) {
                        return $config['enable_module'] ?? false;
                    }));
                }

                // Lấy từ database (sau khi setup hoàn thành)
                return SetupModule::where('is_installed', true)
                    ->whereJsonContains('configuration->enable_module', true)
                    ->pluck('module_name')
                    ->toArray();
            } catch (\Exception $e) {
                // Fallback: return all modules if error
                return array_keys(self::$moduleResourceMapping);
            }
        });
    }

    /**
     * Kiểm tra module có được enable không
     */
    public static function isModuleEnabled(string $moduleName): bool
    {
        $enabledModules = self::getEnabledModules();
        return in_array($moduleName, $enabledModules);
    }

    /**
     * Kiểm tra resource có nên hiển thị không
     */
    public static function shouldShowResource(string $resourceClass): bool
    {
        // Tìm module tương ứng với resource
        foreach (self::$moduleResourceMapping as $moduleName => $resources) {
            if (in_array($resourceClass, $resources)) {
                return self::isModuleEnabled($moduleName);
            }
        }

        // Nếu không tìm thấy mapping, mặc định hiển thị (core resources)
        return true;
    }

    /**
     * Lấy danh sách resources nên ẩn
     */
    public static function getHiddenResources(): array
    {
        $hiddenResources = [];
        
        foreach (self::$moduleResourceMapping as $moduleName => $resources) {
            if (!self::isModuleEnabled($moduleName)) {
                $hiddenResources = array_merge($hiddenResources, $resources);
            }
        }

        return $hiddenResources;
    }

    /**
     * Clear cache khi có thay đổi module status
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Lấy mapping giữa modules và resources
     */
    public static function getModuleResourceMapping(): array
    {
        return self::$moduleResourceMapping;
    }

    /**
     * Thêm resource vào module mapping
     */
    public static function addResourceToModule(string $moduleName, string $resourceClass): void
    {
        if (!isset(self::$moduleResourceMapping[$moduleName])) {
            self::$moduleResourceMapping[$moduleName] = [];
        }

        if (!in_array($resourceClass, self::$moduleResourceMapping[$moduleName])) {
            self::$moduleResourceMapping[$moduleName][] = $resourceClass;
        }

        self::clearCache();
    }

    /**
     * Debug: Hiển thị trạng thái modules
     */
    public static function getDebugInfo(): array
    {
        return [
            'enabled_modules' => self::getEnabledModules(),
            'hidden_resources' => self::getHiddenResources(),
            'module_mapping' => self::$moduleResourceMapping,
            'cache_key' => self::CACHE_KEY,
        ];
    }
}
