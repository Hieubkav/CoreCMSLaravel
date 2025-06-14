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

            // 12. Tạo và chạy seeders nếu được yêu cầu
            if (isset($config['create_sample_data']) && $config['create_sample_data']) {
                self::createSeeders($results);

                // Dump autoload để refresh class map
                try {
                    exec('composer dump-autoload 2>&1', $output, $returnCode);
                    if ($returnCode === 0) {
                        $results['autoload'][] = 'Composer autoload refreshed';
                    } else {
                        $results['autoload_errors'][] = 'Failed to refresh autoload: ' . implode(' ', $output);
                    }
                } catch (\Exception $e) {
                    $results['autoload_errors'][] = 'Failed to refresh autoload: ' . $e->getMessage();
                }

                self::runSeeders($results);
            }

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
        $templatesPath = base_path('storage/setup-templates/livewire');
        $livewirePath = base_path('app/Livewire');
        $livewireViewsPath = base_path('resources/views/livewire');

        if (!File::exists($livewirePath)) {
            File::makeDirectory($livewirePath, 0755, true);
        }

        if (!File::exists($livewireViewsPath)) {
            File::makeDirectory($livewireViewsPath, 0755, true);
        }

        // Copy BlogIndex Livewire component
        $templateFile = $templatesPath . '/BlogIndex.php';
        $targetFile = $livewirePath . '/BlogIndex.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['livewire'][] = 'BlogIndex component created';
        }

        // Copy BlogIndex view
        $templateViewPath = base_path('storage/setup-templates/views/livewire');
        $templateViewFile = $templateViewPath . '/blog-index.blade.php';
        $targetViewFile = $livewireViewsPath . '/blog-index.blade.php';

        if (File::exists($templateViewFile)) {
            File::copy($templateViewFile, $targetViewFile);
            $results['livewire_views'][] = 'blog-index.blade.php view created';
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
| Routes for blog functionality with Livewire
*/
Route::get('/blog', function() {
    return view('blog.index');
})->name('blog.index');
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

        $templateFile = $templatesPath . '/BlogController.php';
        $targetFile = $controllersPath . '/BlogController.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['controllers'][] = 'BlogController created from template';
        } else {
            $results['controllers'][] = 'BlogController template not found';
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
            $templateFile = $templatesPath . '/' . $view;
            $targetFile = $viewsPath . '/' . $view;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['views'][] = "Blog view {$view} created from template";
            } else {
                $results['views'][] = "Blog view template {$view} not found";
            }
        }
    }

    /**
     * Tạo seeders
     */
    private static function createSeeders(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/seeders');
        $seedersPath = base_path('database/seeders');

        $templateFile = $templatesPath . '/BlogSeeder.php';
        $targetFile = $seedersPath . '/BlogSeeder.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['seeders'][] = 'BlogSeeder created from template';
        } else {
            $results['seeders'][] = 'BlogSeeder template not found';
        }
    }

    /**
     * Chạy seeders
     */
    private static function runSeeders(array &$results): void
    {
        try {
            // Kiểm tra xem model Post có tồn tại không
            if (!class_exists('App\Models\Post')) {
                $results['seeders_errors'][] = 'Post model not found, skipping seeder';
                return;
            }

            Artisan::call('db:seed', [
                '--class' => 'BlogSeeder',
                '--force' => true
            ]);
            $results['seeders_run'][] = 'BlogSeeder executed successfully';
        } catch (\Exception $e) {
            $results['seeders_errors'][] = 'Failed to run BlogSeeder: ' . $e->getMessage();
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
