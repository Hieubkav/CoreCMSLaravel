<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ModuleVisibilityService;
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
            ModuleVisibilityService::clearCache();
            $this->info('✅ Module cache cleared!');
        }

        $this->info('🔍 Module Visibility Debug Info:');
        $this->line('');

        // Get debug info
        $debugInfo = ModuleVisibilityService::getDebugInfo();

        // Show enabled modules
        $this->info('📦 Enabled Modules:');
        if (empty($debugInfo['enabled_modules'])) {
            $this->warn('   No modules enabled');
        } else {
            foreach ($debugInfo['enabled_modules'] as $module) {
                $this->line("   ✅ {$module}");
            }
        }
        $this->line('');

        // Show hidden resources
        $this->info('🚫 Hidden Resources:');
        if (empty($debugInfo['hidden_resources'])) {
            $this->warn('   No resources hidden');
        } else {
            foreach ($debugInfo['hidden_resources'] as $resource) {
                $this->line("   ❌ {$resource}");
            }
        }
        $this->line('');

        // Show module mapping
        $this->info('🗺️  Module → Resource Mapping:');
        foreach ($debugInfo['module_mapping'] as $module => $resources) {
            $enabled = in_array($module, $debugInfo['enabled_modules']) ? '✅' : '❌';
            $this->line("   {$enabled} {$module}:");
            
            if (empty($resources)) {
                $this->line("      (no resources)");
            } else {
                foreach ($resources as $resource) {
                    $this->line("      - {$resource}");
                }
            }
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
