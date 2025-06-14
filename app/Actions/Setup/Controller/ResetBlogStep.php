<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Exception;

class ResetBlogStep
{
    /**
     * Reset blog step - xóa tất cả dữ liệu và files liên quan đến blog
     */
    public static function handle(Request $request): array
    {
        // Chỉ cho phép trong local environment
        if (!app()->environment('local')) {
            return [
                'success' => false,
                'message' => 'Reset chỉ được phép trong môi trường development'
            ];
        }

        try {
            $resetResults = [];

            // 1. Xóa dữ liệu blog trong database
            self::resetBlogDatabase($resetResults);

            // 2. Xóa blog models
            self::resetBlogModels($resetResults);

            // 3. Xóa blog migrations
            self::resetBlogMigrations($resetResults);

            // 4. Xóa blog Filament resources
            self::resetBlogFilamentResources($resetResults);

            // 5. Xóa blog observers
            self::resetBlogObservers($resetResults);

            // 6. Xóa blog Livewire components
            self::resetBlogLivewireComponents($resetResults);

            // 7. Xóa blog controller
            self::resetBlogController($resetResults);

            // 8. Xóa blog views
            self::resetBlogViews($resetResults);

            // 9. Xóa blog routes
            self::resetBlogRoutes($resetResults);

            // 10. Xóa blog seeders
            self::resetBlogSeeders($resetResults);

            // 11. Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return [
                'success' => true,
                'message' => 'Reset blog step thành công!',
                'details' => $resetResults
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi reset blog step: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa dữ liệu blog trong database
     */
    private static function resetBlogDatabase(array &$results): void
    {
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Xóa dữ liệu trong các bảng blog (nếu tồn tại)
            $tables = ['post_images', 'posts', 'post_categories'];

            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                    $results['database'][] = "Truncated table: {$table}";
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        } catch (Exception $e) {
            // Re-enable foreign key checks in case of error
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (Exception $e2) {
                // Ignore
            }
            $results['database_errors'][] = 'Failed to reset blog database: ' . $e->getMessage();
        }
    }

    /**
     * Xóa blog models
     */
    private static function resetBlogModels(array &$results): void
    {
        $models = [
            'app/Models/Post.php',
            'app/Models/PostCategory.php',
            'app/Models/PostImage.php'
        ];

        foreach ($models as $model) {
            $path = base_path($model);
            if (File::exists($path)) {
                File::delete($path);
                $results['models'][] = "Deleted: {$model}";
            }
        }
    }

    /**
     * Xóa blog migrations
     */
    private static function resetBlogMigrations(array &$results): void
    {
        $migrationPath = base_path('database/migrations');
        $files = File::glob($migrationPath . '/*_create_post*.php');
        
        foreach ($files as $file) {
            File::delete($file);
            $results['migrations'][] = "Deleted: " . basename($file);
        }
    }

    /**
     * Xóa blog Filament resources
     */
    private static function resetBlogFilamentResources(array &$results): void
    {
        $resourcesPath = base_path('app/Filament/Admin/Resources');
        
        // Xóa resource files
        $resources = [
            'PostResource.php',
            'PostCategoryResource.php'
        ];

        foreach ($resources as $resource) {
            $path = $resourcesPath . '/' . $resource;
            if (File::exists($path)) {
                File::delete($path);
                $results['filament_resources'][] = "Deleted: {$resource}";
            }
        }

        // Xóa resource directories
        $resourceDirs = [
            'PostResource',
            'PostCategoryResource'
        ];

        foreach ($resourceDirs as $dir) {
            $path = $resourcesPath . '/' . $dir;
            if (File::exists($path)) {
                File::deleteDirectory($path);
                $results['filament_resource_dirs'][] = "Deleted directory: {$dir}";
            }
        }
    }

    /**
     * Xóa blog observers
     */
    private static function resetBlogObservers(array &$results): void
    {
        $observerPath = base_path('app/Observers/PostObserver.php');
        if (File::exists($observerPath)) {
            File::delete($observerPath);
            $results['observers'][] = "Deleted: PostObserver.php";
        }
    }

    /**
     * Xóa blog Livewire components
     */
    private static function resetBlogLivewireComponents(array &$results): void
    {
        // Xóa Livewire component class
        $livewirePath = base_path('app/Livewire/BlogIndex.php');
        if (File::exists($livewirePath)) {
            File::delete($livewirePath);
            $results['livewire'][] = "Deleted: BlogIndex.php";
        }

        // Xóa Livewire view
        $livewireViewPath = base_path('resources/views/livewire/blog-index.blade.php');
        if (File::exists($livewireViewPath)) {
            File::delete($livewireViewPath);
            $results['livewire_views'][] = "Deleted: blog-index.blade.php";
        }
    }

    /**
     * Xóa blog controller
     */
    private static function resetBlogController(array &$results): void
    {
        $controllerPath = base_path('app/Http/Controllers/BlogController.php');
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
            $results['controllers'][] = "Deleted: BlogController.php";
        }
    }

    /**
     * Xóa blog views
     */
    private static function resetBlogViews(array &$results): void
    {
        $viewsPath = base_path('resources/views/blog');
        if (File::exists($viewsPath)) {
            File::deleteDirectory($viewsPath);
            $results['views'][] = "Deleted blog views directory";
        }

        // Xóa blog components
        $components = [
            'resources/views/components/blog-section.blade.php',
            'resources/views/components/post-detail.blade.php'
        ];

        foreach ($components as $component) {
            $path = base_path($component);
            if (File::exists($path)) {
                File::delete($path);
                $results['components'][] = "Deleted: " . basename($component);
            }
        }
    }

    /**
     * Xóa blog routes
     */
    private static function resetBlogRoutes(array &$results): void
    {
        $routesFile = base_path('routes/web.php');
        if (File::exists($routesFile)) {
            $content = File::get($routesFile);
            
            // Xóa blog routes section
            $pattern = '/\/\*\s*\|\-+\s*\|\s*Blog Routes\s*\|\-+.*?\*\/.*?Route::get\(\'\/blog\/category\/\{slug\}\'.*?\);/s';
            $newContent = preg_replace($pattern, '', $content);
            
            if ($newContent !== $content) {
                File::put($routesFile, $newContent);
                $results['routes'][] = "Removed blog routes from web.php";
            }
        }
    }

    /**
     * Xóa blog seeders
     */
    private static function resetBlogSeeders(array &$results): void
    {
        $seederPath = base_path('database/seeders/BlogSeeder.php');
        if (File::exists($seederPath)) {
            File::delete($seederPath);
            $results['seeders'][] = "Deleted: BlogSeeder.php";
        }
    }
}
