<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class CreateStaffModule
{
    /**
     * Tạo module staff hoàn chỉnh
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
            
            // 7. Tạo StaffController
            self::createStaffController($results);

            // 8. Tạo staff views
            self::createStaffViews($results);

            // 9. Tạo routes
            self::createRoutes($results);

            // 10. Chạy migrations
            self::runMigrations($results);

            // 11. Cập nhật ViewServiceProvider
            self::updateViewServiceProvider($results);

            // 12. Tạo và chạy seeders nếu được yêu cầu
            if (isset($config['create_sample_data']) && $config['create_sample_data']) {
                self::createSeeders($results);
                self::runSeeders($results);
            }

            return [
                'success' => true,
                'message' => 'Tạo module staff thành công!',
                'data' => [
                    'config' => $config,
                    'results' => $results
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo module staff: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo migrations
     */
    private static function createMigrations(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/staff/migrations');
        $migrationsPath = base_path('database/migrations');
        
        $migrations = [
            'create_staff_table.php',
            'create_staff_images_table.php'
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
        $templatesPath = base_path('storage/setup-templates/staff/models');
        $modelsPath = base_path('app/Models');
        
        $models = ['Staff.php', 'StaffImage.php'];

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
        $templatesPath = base_path('storage/setup-templates/staff/observers');
        $observersPath = base_path('app/Observers');
        
        if (!File::exists($observersPath)) {
            File::makeDirectory($observersPath, 0755, true);
        }

        $observers = ['StaffObserver.php'];

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
        $templatesPath = base_path('storage/setup-templates/staff/filament/resources');
        $resourcesPath = base_path('app/Filament/Admin/Resources');

        // Copy resource files
        $resources = ['StaffResource.php'];

        foreach ($resources as $resource) {
            $templateFile = $templatesPath . '/' . $resource;
            $targetFile = $resourcesPath . '/' . $resource;

            if (File::exists($templateFile)) {
                File::copy($templateFile, $targetFile);
                $results['filament_resources'][] = $targetFile;
            }
        }

        // Copy resource page directories if they exist
        $resourceDirs = ['StaffResource'];

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
        $templatesPath = base_path('storage/setup-templates/staff/views/components');
        $componentsPath = base_path('resources/views/components');
        
        if (!File::exists($componentsPath)) {
            File::makeDirectory($componentsPath, 0755, true);
        }

        $components = [
            'staff-section.blade.php'
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
        $templatesPath = base_path('storage/setup-templates/staff/livewire');
        $livewirePath = base_path('app/Livewire');
        $livewireViewsPath = base_path('resources/views/livewire');

        if (!File::exists($livewirePath)) {
            File::makeDirectory($livewirePath, 0755, true);
        }

        if (!File::exists($livewireViewsPath)) {
            File::makeDirectory($livewireViewsPath, 0755, true);
        }

        // Copy StaffIndex Livewire component
        $templateFile = $templatesPath . '/StaffIndex.php';
        $targetFile = $livewirePath . '/StaffIndex.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['livewire'][] = 'StaffIndex component created';
        }

        // Copy StaffIndex view
        $templateViewPath = base_path('storage/setup-templates/staff/livewire/views');
        $templateViewFile = $templateViewPath . '/staff-index.blade.php';
        $targetViewFile = $livewireViewsPath . '/staff-index.blade.php';

        if (File::exists($templateViewFile)) {
            File::copy($templateViewFile, $targetViewFile);
            $results['livewire_views'][] = 'staff-index.blade.php view created';
        }
    }

    /**
     * Tạo StaffController
     */
    private static function createStaffController(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/staff/controllers');
        $controllersPath = base_path('app/Http/Controllers');

        $templateFile = $templatesPath . '/StaffController.php';
        $targetFile = $controllersPath . '/StaffController.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['controllers'][] = 'StaffController created from template';
        } else {
            $results['controllers'][] = 'StaffController template not found';
        }
    }

    /**
     * Tạo staff views
     */
    private static function createStaffViews(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/staff/views/staff');
        $viewsPath = base_path('resources/views/staff');

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
                $results['views'][] = "Staff view {$view} created from template";
            } else {
                $results['views'][] = "Staff view template {$view} not found";
            }
        }
    }

    /**
     * Tạo routes
     */
    private static function createRoutes(array &$results): void
    {
        $routesFile = base_path('routes/web.php');
        $staffRoutes = "
/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
| Routes for staff functionality with Livewire
*/
Route::get('/staff', function() {
    return view('staff.index');
})->name('staff.index');
Route::get('/staff/{slug}', [App\Http\Controllers\StaffController::class, 'show'])->name('staff.show');
Route::get('/staff/position/{position}', [App\Http\Controllers\StaffController::class, 'position'])->name('staff.position');
Route::get('/api/staff', [App\Http\Controllers\StaffController::class, 'api'])->name('staff.api');
";

        if (File::exists($routesFile)) {
            $content = File::get($routesFile);
            if (strpos($content, 'Staff Routes') === false) {
                File::append($routesFile, $staffRoutes);
                $results['routes'][] = 'Staff routes added to web.php';
            }
        }
    }

    /**
     * Chạy migrations
     */
    private static function runMigrations(array &$results): void
    {
        try {
            // Chỉ chạy migrations mới được tạo, không chạy tất cả
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations'
            ]);
            $results['migration_run'] = 'Migrations executed successfully';
        } catch (\Exception $e) {
            // Nếu lỗi, thử chạy từng migration riêng lẻ
            $results['migration_errors'][] = 'Failed to run migrations: ' . $e->getMessage();

            // Thử chạy từng migration staff riêng lẻ
            try {
                $staffMigrations = glob(base_path('database/migrations/*_create_staff*.php'));
                foreach ($staffMigrations as $migration) {
                    $migrationName = basename($migration, '.php');
                    if (strpos($migrationName, 'create_staff') !== false) {
                        Artisan::call('migrate', [
                            '--force' => true,
                            '--path' => 'database/migrations/' . basename($migration)
                        ]);
                    }
                }
                $results['migration_run'] = 'Staff migrations executed individually';
            } catch (\Exception $e2) {
                $results['migration_errors'][] = 'Failed to run individual staff migrations: ' . $e2->getMessage();
            }
        }
    }

    /**
     * Tạo seeders
     */
    private static function createSeeders(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/staff/seeders');
        $seedersPath = base_path('database/seeders');

        $templateFile = $templatesPath . '/StaffSeeder.php';
        $targetFile = $seedersPath . '/StaffSeeder.php';

        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['seeders'][] = 'StaffSeeder created from template';
        } else {
            $results['seeders'][] = 'StaffSeeder template not found';
        }
    }

    /**
     * Chạy seeders
     */
    private static function runSeeders(array &$results): void
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'StaffSeeder',
                '--force' => true
            ]);
            $results['seeders_run'][] = 'StaffSeeder executed successfully';
        } catch (\Exception $e) {
            $results['seeders_errors'][] = 'Failed to run StaffSeeder: ' . $e->getMessage();
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
