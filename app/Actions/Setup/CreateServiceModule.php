<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class CreateServiceModule
{
    /**
     * Tạo module service hoàn chỉnh
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
            
            // 7. Tạo ServiceController
            self::createServiceController($results);

            // 8. Tạo service views
            self::createServiceViews($results);

            // 9. Tạo routes
            self::createRoutes($results);

            // 10. Chạy migrations
            self::runMigrations($results);

            // 11. Cập nhật ViewServiceProvider
            self::updateViewServiceProvider($results);

            // 12. Đợi một chút để đảm bảo files được ghi xong
            sleep(1);

            // 13. Tạo và chạy seeders nếu được yêu cầu
            if (isset($config['create_sample_data']) && $config['create_sample_data']) {
                self::createSeeders($results);
                self::runSeeders($results);
            }

            return [
                'success' => true,
                'message' => 'Tạo module service thành công!',
                'data' => [
                    'config' => $config,
                    'results' => $results
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo module service: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo migrations
     */
    private static function createMigrations(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/migrations');
        $migrationsPath = base_path('database/migrations');
        
        $migrations = [
            'create_services_table.php',
            'create_service_images_table.php'
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
        $templatesPath = base_path('storage/setup-templates/service/models');
        $modelsPath = base_path('app/Models');
        
        $models = ['Service.php', 'ServiceImage.php'];

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
        $templatesPath = base_path('storage/setup-templates/service/observers');
        $observersPath = base_path('app/Observers');
        
        if (!File::exists($observersPath)) {
            File::makeDirectory($observersPath, 0755, true);
        }

        $observers = ['ServiceObserver.php'];

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
        $templatesPath = base_path('storage/setup-templates/service/filament/resources');
        $resourcesPath = base_path('app/Filament/Admin/Resources');

        // Copy resource files
        $resources = ['ServiceResource.php'];

        foreach ($resources as $resource) {
            $templateFile = $templatesPath . '/' . $resource;
            $targetFile = $resourcesPath . '/' . $resource;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['filament_resources'][] = $targetFile;
            }
        }

        // Copy ServiceResource Pages từ templates
        $pagesTemplatePath = $templatesPath . '/ServiceResource/Pages';
        $pagesPath = $resourcesPath . '/ServiceResource/Pages';

        if (!File::exists($pagesPath)) {
            File::makeDirectory($pagesPath, 0755, true);
        }

        // Copy page files từ templates
        $pageFiles = [
            'ListServices.php',
            'CreateService.php',
            'EditService.php'
        ];

        foreach ($pageFiles as $pageFile) {
            $templatePageFile = $pagesTemplatePath . '/' . $pageFile;
            $targetPageFile = $pagesPath . '/' . $pageFile;

            if (File::exists($templatePageFile)) {
                File::copy($templatePageFile, $targetPageFile);
                $results['filament_pages'][] = $pageFile . ' copied from template';
            } else {
                $results['filament_pages_missing'][] = $pageFile . ' template not found';
            }
        }
    }

    /**
     * Tạo frontend components
     */
    private static function createFrontendComponents(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/views/components');
        $componentsPath = base_path('resources/views/components');

        if (!File::exists($componentsPath)) {
            File::makeDirectory($componentsPath, 0755, true);
        }

        $components = [
            'service-section.blade.php'
        ];

        foreach ($components as $component) {
            $templateFile = $templatesPath . '/' . $component;
            $targetFile = $componentsPath . '/' . $component;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['components'][] = $targetFile;
            } else {
                $results['components_missing'][] = $component;
            }
        }
    }

    /**
     * Tạo Livewire components
     */
    private static function createLivewireComponents(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/livewire');
        $livewirePath = base_path('app/Livewire');
        $livewireViewsPath = base_path('resources/views/livewire');

        if (!File::exists($livewirePath)) {
            File::makeDirectory($livewirePath, 0755, true);
        }

        if (!File::exists($livewireViewsPath)) {
            File::makeDirectory($livewireViewsPath, 0755, true);
        }

        // Copy ServiceIndex Livewire component
        $templateFile = $templatesPath . '/ServiceIndex.php';
        $targetFile = $livewirePath . '/ServiceIndex.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['livewire'][] = 'ServiceIndex component created';
        } else {
            $results['livewire_missing'][] = 'ServiceIndex.php template not found';
        }

        // Copy ServiceIndex view
        $templateViewPath = base_path('storage/setup-templates/service/livewire/views');
        $templateViewFile = $templateViewPath . '/service-index.blade.php';
        $targetViewFile = $livewireViewsPath . '/service-index.blade.php';

        if (File::exists($templateViewFile)) {
            File::copy($templateViewFile, $targetViewFile);
            $results['livewire_views'][] = 'service-index.blade.php view created';
        } else {
            $results['livewire_views_missing'][] = 'service-index.blade.php template not found';
        }
    }

    /**
     * Tạo ServiceController
     */
    private static function createServiceController(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/controllers');
        $controllersPath = base_path('app/Http/Controllers');

        $templateFile = $templatesPath . '/ServiceController.php';
        $targetFile = $controllersPath . '/ServiceController.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['controllers'][] = 'ServiceController created from template';
        } else {
            $results['controllers'][] = 'ServiceController template not found';
        }
    }

    /**
     * Tạo service views
     */
    private static function createServiceViews(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/views/service');
        $viewsPath = base_path('resources/views/service');

        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }

        // Các view cần tạo
        $views = ['index.blade.php', 'show.blade.php'];

        foreach ($views as $view) {
            $templateFile = $templatesPath . '/' . $view;
            $targetFile = $viewsPath . '/' . $view;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['views'][] = "Service view {$view} created from template";
            } else {
                $results['views'][] = "Service view template {$view} not found";
            }
        }
    }

    /**
     * Tạo routes
     */
    private static function createRoutes(array &$results): void
    {
        $routesFile = base_path('routes/web.php');
        $serviceRoutes = "
/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
| Routes for service functionality with Livewire
*/
Route::get('/services', function() {
    return view('service.index');
})->name('services.index');
Route::get('/services/category/{category}', [App\Http\Controllers\ServiceController::class, 'category'])->name('services.category');
Route::get('/services/{slug}', [App\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
Route::get('/services-featured', [App\Http\Controllers\ServiceController::class, 'featured'])->name('service.featured');
Route::get('/api/services', [App\Http\Controllers\ServiceController::class, 'api'])->name('service.api');
Route::get('/api/services/search', [App\Http\Controllers\ServiceController::class, 'search'])->name('service.search');
";

        if (File::exists($routesFile)) {
            $content = File::get($routesFile);
            if (strpos($content, 'Service Routes') === false) {
                File::append($routesFile, $serviceRoutes);
                $results['routes'][] = 'Service routes added to web.php';
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
     * Tạo seeders
     */
    private static function createSeeders(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/service/seeders');
        $seedersPath = base_path('database/seeders');

        $templateFile = $templatesPath . '/ServiceSeeder.php';
        $targetFile = $seedersPath . '/ServiceSeeder.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['seeders'][] = 'ServiceSeeder created from template';
        } else {
            $results['seeders'][] = 'ServiceSeeder template not found';
        }
    }

    /**
     * Chạy seeders
     */
    private static function runSeeders(array &$results): void
    {
        try {
            // Kiểm tra xem bảng services có tồn tại không
            if (!\Illuminate\Support\Facades\Schema::hasTable('services')) {
                $results['seeders_errors'][] = 'Services table not found. Skipping seeder.';
                return;
            }

            // Force load model Service bằng cách require file trực tiếp
            $servicePath = base_path('app/Models/Service.php');
            if (file_exists($servicePath)) {
                require_once $servicePath;
            }

            // Kiểm tra lại xem model có load được không
            if (!class_exists('App\Models\Service')) {
                $results['seeders_errors'][] = 'Service model could not be loaded. Skipping seeder.';
                return;
            }

            Artisan::call('db:seed', [
                '--class' => 'ServiceSeeder',
                '--force' => true
            ]);
            $results['seeders_run'][] = 'ServiceSeeder executed successfully';
        } catch (\Exception $e) {
            $results['seeders_errors'][] = 'Failed to run ServiceSeeder: ' . $e->getMessage();
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
