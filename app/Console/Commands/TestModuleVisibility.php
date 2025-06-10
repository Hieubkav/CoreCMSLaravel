<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Module\CheckModuleVisibility;
use App\Models\SetupModule;

class TestModuleVisibility extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:module-visibility {--clear-cache : Clear module cache}';

    /**
     * The console command description.
     */
    protected $description = 'Test module visibility service and show debug info';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('clear-cache')) {
            CheckModuleVisibility::clearModuleCache();
            $this->info('✅ Module cache cleared!');
        }

        $this->info('🔍 Module Visibility Debug Info:');
        $this->line('');

        // Get debug info
        $enabledModules = (new CheckModuleVisibility())->getEnabledModules();
        $hiddenResources = [];

        // Show enabled modules
        $this->info('📦 Enabled Modules:');
        if (empty($enabledModules)) {
            $this->warn('   No modules enabled');
        } else {
            foreach ($enabledModules as $module) {
                $this->line("   ✅ {$module}");
            }
        }
        $this->line('');

        // Show hidden resources
        $this->info('🚫 Hidden Resources:');
        if (empty($hiddenResources)) {
            $this->warn('   No resources hidden');
        } else {
            foreach ($hiddenResources as $resource) {
                $this->line("   ❌ {$resource}");
            }
        }
        $this->line('');

        // Show module mapping (simplified)
        $this->info('🗺️  Module Status Summary:');
        $moduleMapping = [
            'user_roles' => ['App\Filament\Admin\Resources\UserResource'],
            'blog_posts' => ['App\Filament\Admin\Resources\PostResource', 'App\Filament\Admin\Resources\PostCategoryResource'],
            'staff' => ['App\Filament\Admin\Resources\StaffResource'],
            'content_sections' => ['App\Filament\Admin\Resources\SliderResource', 'App\Filament\Admin\Resources\TestimonialResource'],
            'ecommerce' => ['App\Filament\Admin\Resources\ProductResource'],
        ];

        foreach ($moduleMapping as $module => $resources) {
            $enabled = in_array($module, $enabledModules) ? '✅' : '❌';
            $this->line("   {$enabled} {$module} (" . count($resources) . " resources)");
        }
        $this->line('');

        // Show database status
        $this->info('💾 Database Status:');
        try {
            $modules = SetupModule::all();
            if ($modules->isEmpty()) {
                $this->warn('   No modules in database');
            } else {
                foreach ($modules as $module) {
                    $status = $module->is_installed ? '✅ Installed' : '⏳ Pending';
                    $enabled = $module->configuration['enable_module'] ?? false ? '✅ Enabled' : '❌ Disabled';
                    $this->line("   {$module->module_name}: {$status}, {$enabled}");
                }
            }
        } catch (\Exception $e) {
            $this->error("   Database error: {$e->getMessage()}");
        }
        $this->line('');

        // Show session status
        $this->info('🔄 Session Status:');
        $moduleConfigs = session('module_configs', []);
        if (empty($moduleConfigs)) {
            $this->warn('   No module configs in session');
        } else {
            foreach ($moduleConfigs as $module => $config) {
                $enabled = $config['enable_module'] ?? false ? '✅ Enabled' : '❌ Disabled';
                $this->line("   {$module}: {$enabled}");
            }
        }

        $this->line('');
        $this->info('🎯 Test completed!');
    }
}
