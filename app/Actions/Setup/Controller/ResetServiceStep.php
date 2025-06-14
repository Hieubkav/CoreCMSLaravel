<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Exception;

class ResetServiceStep
{
    /**
     * Reset service step - xóa tất cả dữ liệu và files liên quan đến service
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

            // 1. Xóa dữ liệu service trong database
            self::resetServiceDatabase($resetResults);

            // 2. Xóa service models
            self::resetServiceModels($resetResults);

            // 3. Xóa service migrations
            self::resetServiceMigrations($resetResults);

            // 4. Xóa service Filament resources
            self::resetServiceFilamentResources($resetResults);

            // 5. Xóa service observers
            self::resetServiceObservers($resetResults);

            // 6. Xóa service seeders
            self::resetServiceSeeders($resetResults);

            // 7. Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return [
                'success' => true,
                'message' => 'Reset service step thành công!',
                'details' => $resetResults
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi reset service step: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa dữ liệu service trong database
     */
    private static function resetServiceDatabase(array &$results): void
    {
        try {
            // Xóa dữ liệu trong các bảng service (nếu tồn tại)
            $tables = ['services'];
            
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                    $results['database'][] = "Truncated table: {$table}";
                }
            }
        } catch (Exception $e) {
            $results['database_errors'][] = 'Failed to reset service database: ' . $e->getMessage();
        }
    }

    /**
     * Xóa service models
     */
    private static function resetServiceModels(array &$results): void
    {
        $models = [
            'app/Models/Service.php'
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
     * Xóa service migrations
     */
    private static function resetServiceMigrations(array &$results): void
    {
        $migrationPath = base_path('database/migrations');
        $files = File::glob($migrationPath . '/*_create_services*.php');
        
        foreach ($files as $file) {
            File::delete($file);
            $results['migrations'][] = "Deleted: " . basename($file);
        }
    }

    /**
     * Xóa service Filament resources
     */
    private static function resetServiceFilamentResources(array &$results): void
    {
        $resourcesPath = base_path('app/Filament/Admin/Resources');
        
        // Xóa resource files
        $resources = [
            'ServiceResource.php'
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
            'ServiceResource'
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
     * Xóa service observers
     */
    private static function resetServiceObservers(array &$results): void
    {
        $observerPath = base_path('app/Observers/ServiceObserver.php');
        if (File::exists($observerPath)) {
            File::delete($observerPath);
            $results['observers'][] = "Deleted: ServiceObserver.php";
        }
    }

    /**
     * Xóa service seeders
     */
    private static function resetServiceSeeders(array &$results): void
    {
        $seederPath = base_path('database/seeders/ServiceSeeder.php');
        if (File::exists($seederPath)) {
            File::delete($seederPath);
            $results['seeders'][] = "Deleted: ServiceSeeder.php";
        }
    }
}
