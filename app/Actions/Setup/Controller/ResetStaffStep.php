<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Exception;

class ResetStaffStep
{
    /**
     * Reset staff step - xóa tất cả dữ liệu và files liên quan đến staff
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

            // 1. Xóa dữ liệu staff trong database
            self::resetStaffDatabase($resetResults);

            // 2. Xóa staff models
            self::resetStaffModels($resetResults);

            // 3. Xóa staff migrations
            self::resetStaffMigrations($resetResults);

            // 4. Xóa staff Filament resources
            self::resetStaffFilamentResources($resetResults);

            // 5. Xóa staff observers
            self::resetStaffObservers($resetResults);

            // 6. Xóa staff seeders
            self::resetStaffSeeders($resetResults);

            // 7. Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return [
                'success' => true,
                'message' => 'Reset staff step thành công!',
                'details' => $resetResults
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi reset staff step: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa dữ liệu staff trong database
     */
    private static function resetStaffDatabase(array &$results): void
    {
        try {
            // Xóa dữ liệu trong các bảng staff (nếu tồn tại)
            $tables = ['staff'];
            
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                    $results['database'][] = "Truncated table: {$table}";
                }
            }
        } catch (Exception $e) {
            $results['database_errors'][] = 'Failed to reset staff database: ' . $e->getMessage();
        }
    }

    /**
     * Xóa staff models
     */
    private static function resetStaffModels(array &$results): void
    {
        $models = [
            'app/Models/Staff.php'
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
     * Xóa staff migrations
     */
    private static function resetStaffMigrations(array &$results): void
    {
        $migrationPath = base_path('database/migrations');
        $files = File::glob($migrationPath . '/*_create_staff*.php');
        
        foreach ($files as $file) {
            File::delete($file);
            $results['migrations'][] = "Deleted: " . basename($file);
        }
    }

    /**
     * Xóa staff Filament resources
     */
    private static function resetStaffFilamentResources(array &$results): void
    {
        $resourcesPath = base_path('app/Filament/Admin/Resources');
        
        // Xóa resource files
        $resources = [
            'StaffResource.php'
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
            'StaffResource'
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
     * Xóa staff observers
     */
    private static function resetStaffObservers(array &$results): void
    {
        $observerPath = base_path('app/Observers/StaffObserver.php');
        if (File::exists($observerPath)) {
            File::delete($observerPath);
            $results['observers'][] = "Deleted: StaffObserver.php";
        }
    }

    /**
     * Xóa staff seeders
     */
    private static function resetStaffSeeders(array &$results): void
    {
        $seederPath = base_path('database/seeders/StaffSeeder.php');
        if (File::exists($seederPath)) {
            File::delete($seederPath);
            $results['seeders'][] = "Deleted: StaffSeeder.php";
        }
    }
}
