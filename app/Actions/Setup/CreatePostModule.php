<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Actions\Setup\CodeGenerator;

class CreatePostModule
{
    /**
     * Tạo module blog/post hoàn chỉnh
     */
    public static function handle(array $config): array
    {
        try {
            $results = [];
            
            // 1. Tạo migrations
            self::createMigrations($results);
            
            // 2. Tạo models
            self::createModels($results);
            
            // 3. Tạo observers
            self::createObservers($results);
            
            // 4. Tạo Filament resources
            self::createFilamentResources($results);
            
            // 5. Tạo frontend components
            self::createFrontendComponents($results);
            
            // 6. Tạo Livewire components
            self::createLivewireComponents($results);
            
            // 7. Tạo BlogController
            self::createBlogController($results);

            // 8. Tạo blog views
            self::createBlogViews($results);

            // 9. Tạo routes
            self::createRoutes($results);

            // 10. Chạy migrations
            self::runMigrations($results);

            // 11. Cập nhật ViewServiceProvider
            self::updateViewServiceProvider($results);

            return [
                'success' => true,
                'message' => 'Tạo module blog thành công!',
                'data' => [
                    'config' => $config,
                    'results' => $results
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo module blog: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo migrations
     */
    private static function createMigrations(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/migrations');
        $migrationsPath = base_path('database/migrations');
        
        $migrations = [
            'create_post_categories_table.php',
            'create_posts_table.php',
            'create_post_images_table.php'
        ];

        foreach ($migrations as $migration) {
            $templateFile = $templatesPath . '/' . $migration;
            if (File::exists($templateFile)) {
                $timestamp = date('Y_m_d_His', time() + array_search($migration, $migrations));
                $targetFile = $migrationsPath . '/' . $timestamp . '_' . $migration;
                
                File::copy($templateFile, $targetFile);
                $results['migrations'][] = $targetFile;
            }
        }
    }

    /**
     * Tạo models
     */
    private static function createModels(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/models');
        $modelsPath = base_path('app/Models');
        
        $models = ['Post.php', 'PostCategory.php', 'PostImage.php'];

        foreach ($models as $model) {
            $templateFile = $templatesPath . '/' . $model;
            $targetFile = $modelsPath . '/' . $model;
            
            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['models'][] = $targetFile;
            }
        }
    }

    /**
     * Tạo observers
     */
    private static function createObservers(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/observers');
        $observersPath = base_path('app/Observers');
        
        if (!File::exists($observersPath)) {
            File::makeDirectory($observersPath, 0755, true);
        }

        $observers = ['PostObserver.php'];

        foreach ($observers as $observer) {
            $templateFile = $templatesPath . '/' . $observer;
            $targetFile = $observersPath . '/' . $observer;
            
            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['observers'][] = $targetFile;
            }
        }
    }

    /**
     * Tạo Filament resources
     */
    private static function createFilamentResources(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/filament/resources');
        $resourcesPath = base_path('app/Filament/Admin/Resources');

        // Copy resource files
        $resources = ['PostResource.php', 'PostCategoryResource.php'];

        foreach ($resources as $resource) {
            $templateFile = $templatesPath . '/' . $resource;
            $targetFile = $resourcesPath . '/' . $resource;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['filament_resources'][] = $targetFile;
            }
        }

        // Copy resource page directories
        $resourceDirs = ['PostResource', 'PostCategoryResource'];

        foreach ($resourceDirs as $resourceDir) {
            $templateDir = $templatesPath . '/' . $resourceDir;
            $targetDir = $resourcesPath . '/' . $resourceDir;

            if (File::exists($templateDir)) {
                File::copyDirectory($templateDir, $targetDir);
                $results['filament_resource_pages'][] = $targetDir;
            }
        }
    }

    /**
     * Tạo frontend components
     */
    private static function createFrontendComponents(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/views/components');
        $componentsPath = base_path('resources/views/components');
        
        if (!File::exists($componentsPath)) {
            File::makeDirectory($componentsPath, 0755, true);
        }

        $components = [
            'blog-section.blade.php',
            'post-detail.blade.php'
        ];

        foreach ($components as $component) {
            $templateFile = $templatesPath . '/' . $component;
            $targetFile = $componentsPath . '/' . $component;
            
            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['components'][] = $targetFile;
            }
        }
    }

    /**
     * Tạo Livewire components
     */
    private static function createLivewireComponents(array &$results): void
    {
        // Tạo Livewire component cho post filter
        try {
            Artisan::call('make:livewire', [
                'name' => 'PostFilter'
            ]);
            $results['livewire'][] = 'PostFilter component created';
        } catch (\Exception $e) {
            $results['livewire_errors'][] = 'Failed to create PostFilter: ' . $e->getMessage();
        }
    }

    /**
     * Tạo routes
     */
    private static function createRoutes(array &$results): void
    {
        $routesFile = base_path('routes/web.php');
        $blogRoutes = "
/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
| Routes for blog functionality
*/
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{slug}', [App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');
";

        if (File::exists($routesFile)) {
            $content = File::get($routesFile);
            if (strpos($content, 'Blog Routes') === false) {
                File::append($routesFile, $blogRoutes);
                $results['routes'][] = 'Blog routes added to web.php';
            }
        }
    }

    /**
     * Chạy migrations
     */
    private static function runMigrations(array &$results): void
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $results['migration_run'] = 'Migrations executed successfully';
        } catch (\Exception $e) {
            $results['migration_errors'][] = 'Failed to run migrations: ' . $e->getMessage();
        }
    }

    /**
     * Tạo BlogController
     */
    private static function createBlogController(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/controllers');
        $controllersPath = base_path('app/Http/Controllers');

        // Tạo BlogController từ template hoặc copy từ file đã tạo
        $controllerFile = $controllersPath . '/BlogController.php';
        if (File::exists($controllerFile)) {
            $results['controllers'][] = 'BlogController already exists';
        } else {
            $results['controllers'][] = 'BlogController needs to be created manually';
        }
    }

    /**
     * Tạo blog views
     */
    private static function createBlogViews(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/views/blog');
        $viewsPath = base_path('resources/views/blog');

        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }

        // Các view cần tạo
        $views = ['index.blade.php', 'show.blade.php', 'category.blade.php'];

        foreach ($views as $view) {
            $viewFile = $viewsPath . '/' . $view;
            if (File::exists($viewFile)) {
                $results['views'][] = "Blog view {$view} already exists";
            } else {
                $results['views'][] = "Blog view {$view} needs to be created manually";
            }
        }
    }

    /**
     * Cập nhật ViewServiceProvider
     */
    private static function updateViewServiceProvider(array &$results): void
    {
        // Logic cập nhật ViewServiceProvider sẽ được thêm sau
        $results['view_service_provider'] = 'ViewServiceProvider update scheduled';
    }
}
