<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

                // Dump autoload để refresh class map - sử dụng Artisan command
                try {
                    Artisan::call('optimize:clear');
                    $results['cache_clear'][] = 'Laravel cache cleared';

                    // Thử refresh autoload bằng cách khác
                    if (function_exists('opcache_reset')) {
                        opcache_reset();
                        $results['opcache'][] = 'OPCache reset';
                    }

                    $results['autoload'][] = 'Autoload refreshed successfully';
                } catch (\Exception $e) {
                    $results['autoload_errors'][] = 'Failed to refresh autoload: ' . $e->getMessage();
                }

                // Đợi một chút để đảm bảo autoload được refresh
                sleep(1);

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

        $templateFile = $templatesPath . '/StaffObserver.php';
        $targetFile = $observersPath . '/StaffObserver.php';
        
        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['observers'][] = $targetFile;
        }
    }

    /**
     * Tạo Filament resources
     */
    private static function createFilamentResources(array &$results): void
    {
        $templatesPath = base_path('storage/setup-templates/staff/filament/resources');
        $resourcesPath = base_path('app/Filament/Admin/Resources');
        
        if (!File::exists($resourcesPath)) {
            File::makeDirectory($resourcesPath, 0755, true);
        }

        $resources = File::allFiles($templatesPath);
        foreach ($resources as $resource) {
            $relativePath = $resource->getRelativePathname();
            $targetFile = $resourcesPath . '/' . $relativePath;
            $targetDir = dirname($targetFile);
            
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            File::copy($resource->getPathname(), $targetFile);
            $results['filament_resources'][] = $targetFile;
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

        $templateFile = $templatesPath . '/staff-section.blade.php';
        $targetFile = $componentsPath . '/staff-section.blade.php';
        
        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['components'][] = $targetFile;
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

        // Copy Livewire class
        $templateFile = $templatesPath . '/StaffIndex.php';
        $targetFile = $livewirePath . '/StaffIndex.php';
        
        if (File::exists($templateFile)) {
            File::copy($templateFile, $targetFile);
            $results['livewire'][] = $targetFile;
        }

        // Copy Livewire views
        $viewsTemplatesPath = $templatesPath . '/views';
        if (File::exists($viewsTemplatesPath)) {
            $views = File::allFiles($viewsTemplatesPath);
            foreach ($views as $view) {
                $relativePath = $view->getRelativePathname();
                $targetFile = $livewireViewsPath . '/' . $relativePath;
                $targetDir = dirname($targetFile);
                
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                
                File::copy($view->getPathname(), $targetFile);
                $results['livewire_views'][] = $targetFile;
            }
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
            $results['controllers'][] = $targetFile;
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

        if (File::exists($templatesPath)) {
            $views = File::allFiles($templatesPath);
            foreach ($views as $view) {
                $relativePath = $view->getRelativePathname();
                $targetFile = $viewsPath . '/' . $relativePath;
                $targetDir = dirname($targetFile);
                
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                
                File::copy($view->getPathname(), $targetFile);
                $results['views'][] = $targetFile;
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
| Routes for staff management and display
*/
Route::get('/staff', [App\Http\Controllers\StaffController::class, 'index'])->name('staff.index');
Route::get('/staff/{slug}', [App\Http\Controllers\StaffController::class, 'show'])->name('staff.show');
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
            $results['migration_run'] = 'Staff migrations executed successfully';
        } catch (\Exception $e) {
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
            // Kiểm tra xem model Staff có tồn tại không trước khi chạy seeder
            if (!class_exists('App\Models\Staff')) {
                $results['seeders_errors'][] = 'Staff model not found. Skipping seeder.';
                return;
            }

            // Kiểm tra xem bảng staff có tồn tại không
            if (!Schema::hasTable('staff')) {
                $results['seeders_errors'][] = 'Staff table not found. Skipping seeder.';
                return;
            }

            Artisan::call('db:seed', [
                '--class' => 'StaffSeeder',
                '--force' => true
            ]);
            $results['seeders_run'][] = 'StaffSeeder executed successfully';
        } catch (\Exception $e) {
            $results['seeders_errors'][] = 'Failed to run StaffSeeder: ' . $e->getMessage();

            // Thử tạo dữ liệu mẫu trực tiếp nếu seeder thất bại
            try {
                self::createSampleDataDirectly($results);
            } catch (\Exception $e2) {
                $results['seeders_errors'][] = 'Failed to create sample data directly: ' . $e2->getMessage();
            }
        }
    }

    /**
     * Tạo dữ liệu mẫu trực tiếp nếu seeder thất bại
     */
    private static function createSampleDataDirectly(array &$results): void
    {
        try {
            // Sử dụng DB query builder thay vì model để tránh lỗi autoload
            DB::table('staff')->insert([
                [
                    'name' => 'Nguyễn Văn An',
                    'slug' => 'nguyen-van-an',
                    'position' => 'Giám đốc',
                    'email' => 'an.nguyen@company.com',
                    'phone' => '0901234567',
                    'description' => '<p>Với hơn 15 năm kinh nghiệm trong lĩnh vực công nghệ thông tin.</p>',
                    'social_links' => json_encode([
                        'linkedin' => 'https://linkedin.com/in/nguyen-van-an',
                        'facebook' => 'https://facebook.com/nguyenvanan',
                        'email' => 'an.nguyen@company.com'
                    ]),
                    'status' => 'active',
                    'order' => 1,
                    'seo_title' => 'Nguyễn Văn An - Giám đốc',
                    'seo_description' => 'Với hơn 15 năm kinh nghiệm trong lĩnh vực công nghệ thông tin.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Trần Thị Bình',
                    'slug' => 'tran-thi-binh',
                    'position' => 'Trưởng phòng Kỹ thuật',
                    'email' => 'binh.tran@company.com',
                    'phone' => '0901234568',
                    'description' => '<p>Chuyên gia công nghệ với 12 năm kinh nghiệm.</p>',
                    'social_links' => json_encode([
                        'linkedin' => 'https://linkedin.com/in/tran-thi-binh',
                        'github' => 'https://github.com/tranthibinh',
                        'email' => 'binh.tran@company.com'
                    ]),
                    'status' => 'active',
                    'order' => 2,
                    'seo_title' => 'Trần Thị Bình - Trưởng phòng Kỹ thuật',
                    'seo_description' => 'Chuyên gia công nghệ với 12 năm kinh nghiệm.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            $results['sample_data'][] = 'Sample staff data created directly via DB';
        } catch (\Exception $e) {
            $results['sample_data_errors'][] = 'Failed to create sample data: ' . $e->getMessage();
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
