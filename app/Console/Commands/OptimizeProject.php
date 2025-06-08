<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class OptimizeProject extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'project:optimize {--clear : Clear all caches before optimizing}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize Core Framework project for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Core Framework optimization...');

        if ($this->option('clear')) {
            $this->clearAllCaches();
        }

        $this->optimizeComposer();
        $this->optimizeLaravel();
        $this->optimizeAssets();
        $this->warmupCaches();
        $this->cleanupTempFiles();

        $this->info('✅ Optimization completed!');
        $this->displayOptimizationSummary();
    }

    /**
     * Clear all caches
     */
    private function clearAllCaches()
    {
        $this->info('🧹 Xóa tất cả cache...');

        $commands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear',
            'event:clear',
            'queue:clear',
        ];

        foreach ($commands as $command) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$command}");
            } catch (\Exception $e) {
                $this->warn("   ⚠ {$command} failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Optimize Composer
     */
    private function optimizeComposer()
    {
        $this->info('📦 Tối ưu hóa Composer...');

        try {
            exec('composer dump-autoload --optimize --no-dev --classmap-authoritative 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->line('   ✓ Composer autoloader optimized');
            } else {
                $this->warn('   ⚠ Composer optimization failed');
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠ Composer optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Optimize Laravel
     */
    private function optimizeLaravel()
    {
        $this->info('⚡ Tối ưu hóa Laravel...');

        $commands = [
            'config:cache' => 'Cache config',
            'route:cache' => 'Cache routes',
            'view:cache' => 'Cache views',
            'event:cache' => 'Cache events',
        ];

        foreach ($commands as $command => $description) {
            try {
                Artisan::call($command);
                $this->line("   ✓ {$description}");
            } catch (\Exception $e) {
                $this->warn("   ⚠ {$description} failed: " . $e->getMessage());
            }
        }

        // Optimize application
        try {
            Artisan::call('optimize');
            $this->line('   ✓ Application optimized');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Application optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Optimize Assets
     */
    private function optimizeAssets()
    {
        $this->info('🎨 Tối ưu hóa Assets...');

        // Build production assets
        try {
            exec('npm run build 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->line('   ✓ Assets built for production');
            } else {
                $this->warn('   ⚠ Asset build failed');
            }
        } catch (\Exception $e) {
            $this->warn('   ⚠ Asset build failed: ' . $e->getMessage());
        }

        // Optimize Filament
        try {
            Artisan::call('filament:optimize');
            $this->line('   ✓ Filament optimized');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Filament optimization failed: ' . $e->getMessage());
        }

        // Cache icons if available
        try {
            Artisan::call('icons:cache');
            $this->line('   ✓ Icons cached');
        } catch (\Exception $e) {
            // Icons cache command might not exist, skip silently
        }
    }

    /**
     * Warmup important caches
     */
    private function warmupCaches()
    {
        $this->info('🔥 Khởi tạo cache quan trọng...');

        try {
            // Warmup ViewServiceProvider caches
            \App\Providers\ViewServiceProvider::refreshCache('all');
            $this->line('   ✓ View service provider cache warmed');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Cache warmup failed: ' . $e->getMessage());
        }

        // Warmup database connections
        try {
            DB::connection()->getPdo();
            $this->line('   ✓ Database connection warmed');
        } catch (\Exception $e) {
            $this->warn('   ⚠ Database warmup failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up temporary files
     */
    private function cleanupTempFiles()
    {
        $this->info('🧽 Dọn dẹp file tạm...');

        $tempPaths = [
            storage_path('logs/*.log'),
            storage_path('framework/cache/data/*'),
            storage_path('framework/sessions/*'),
            storage_path('framework/views/*'),
        ];

        foreach ($tempPaths as $path) {
            try {
                $files = glob($path);
                if ($files) {
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            unlink($file);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip if can't clean
            }
        }

        $this->line('   ✓ Temporary files cleaned');
    }

    /**
     * Display optimization summary
     */
    private function displayOptimizationSummary()
    {
        $this->newLine();
        $this->info('📊 Tóm tắt tối ưu hóa:');
        $this->line('   • Composer autoloader: Optimized');
        $this->line('   • Laravel caches: Cached');
        $this->line('   • Assets: Built for production');
        $this->line('   • View caches: Warmed up');
        $this->line('   • Temporary files: Cleaned');
        
        $this->newLine();
        $this->info('🎯 Khuyến nghị tiếp theo:');
        $this->line('   • Kiểm tra website trên production');
        $this->line('   • Chạy tests để đảm bảo mọi thứ hoạt động');
        $this->line('   • Monitor performance metrics');
        
        $this->newLine();
        $this->comment('💡 Để tối ưu thêm, hãy chạy: php artisan project:optimize --clear');
    }
}
