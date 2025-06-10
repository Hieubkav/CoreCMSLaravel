<?php

namespace App\Actions\Module;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\SetupModule;
use Illuminate\Support\Facades\Cache;

class CheckModuleVisibility
{
    use AsAction;

    /**
     * Kiểm tra module có được enable không
     */
    public function handle(string $moduleName): bool
    {
        $enabledModules = $this->getEnabledModules();
        return in_array($moduleName, $enabledModules);
    }

    /**
     * Lấy danh sách modules đã được enable
     */
    public function getEnabledModules(): array
    {
        return Cache::remember('enabled_modules', 3600, function () {
            try {
                // Ưu tiên lấy từ session (trong quá trình setup)
                $moduleConfigs = session('module_configs', []);
                
                if (!empty($moduleConfigs)) {
                    $enabledModules = [];
                    foreach ($moduleConfigs as $moduleKey => $config) {
                        if ($config['enable_module'] ?? false) {
                            $enabledModules[] = $moduleKey;
                        }
                    }
                    return $enabledModules;
                }

                // Fallback: lấy từ database (sau khi setup hoàn thành)
                return SetupModule::where('is_installed', true)
                    ->whereJsonContains('configuration->enable_module', true)
                    ->pluck('module_name')
                    ->toArray();
            } catch (\Exception $e) {
                // Fallback: return core modules if error
                return ['system_configuration', 'layout_components', 'settings_expansion', 'web_design_management'];
            }
        });
    }

    /**
     * Kiểm tra nhiều modules cùng lúc
     */
    public static function checkMultiple(array $moduleNames): array
    {
        $checker = new self();
        $enabledModules = $checker->getEnabledModules();
        
        $results = [];
        foreach ($moduleNames as $moduleName) {
            $results[$moduleName] = in_array($moduleName, $enabledModules);
        }
        
        return $results;
    }

    /**
     * Clear cache modules
     */
    public static function clearModuleCache(): void
    {
        Cache::forget('enabled_modules');
    }

    /**
     * Refresh cache modules
     */
    public static function refreshModuleCache(): array
    {
        self::clearModuleCache();
        return (new self())->getEnabledModules();
    }

    /**
     * Kiểm tra module có phải core module không
     */
    public static function isCoreModule(string $moduleName): bool
    {
        $coreModules = [
            'system_configuration',
            'layout_components', 
            'settings_expansion',
            'web_design_management'
        ];
        
        return in_array($moduleName, $coreModules);
    }

    /**
     * Kiểm tra module có phải optional module không
     */
    public static function isOptionalModule(string $moduleName): bool
    {
        $optionalModules = [
            'user_roles_permissions',
            'blog_posts',
            'staff',
            'content_sections',
            'ecommerce',
            'advanced_features'
        ];
        
        return in_array($moduleName, $optionalModules);
    }
}
