<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Actions\Setup\CodeGenerator;
use Exception;

class ResetSystem
{
    /**
     * Reset toàn bộ setup - chỉ cho phép trong local environment
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
            // Xác nhận reset
            if (!$request->has('confirm') || $request->confirm !== 'yes') {
                return [
                    'success' => false,
                    'message' => 'Vui lòng xác nhận reset'
                ];
            }

            $resetResults = [];

            // 1. Xóa generated code files
            self::resetGeneratedCode($resetResults);

            // 2. Xóa migration files dư thừa
            self::resetMigrationFiles($resetResults);

            // 3. Xóa Filament resources không cần thiết
            self::resetFilamentResources($resetResults);

            // 4. Xóa Observer files dư thừa
            self::resetObserverFiles($resetResults);

            // 5. Xóa Livewire components
            self::resetLivewireComponents($resetResults);

            // 6. Bỏ qua Seeder files - giữ nguyên để đầy đủ
            // self::resetSeederFiles($resetResults);

            // 7. Reset ViewServiceProvider
            self::resetViewServiceProvider($resetResults);

            // 8. Reset routes/web.php
            self::resetWebRoutes($resetResults);

            // 9. Xóa tất cả dữ liệu trong database (trừ migrations)
            self::resetDatabase($resetResults);

            // 10. Xóa tất cả files trong storage
            self::resetStorage($resetResults);

            // 11. Reset config files về mặc định
            self::resetConfigFiles($resetResults);

            // 12. Clear tất cả cache
            self::clearAllCache($resetResults);

            // 13. Reset session
            session()->flush();

            return [
                'success' => true,
                'message' => 'Reset thành công! Hệ thống đã trở về trạng thái ban đầu.',
                'details' => $resetResults
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi reset: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset database - xóa hoàn toàn tất cả tables bao gồm cả migrations table
     */
    private static function resetDatabase(&$results): void
    {
        try {
            $databaseName = DB::getDatabaseName();
            $deletedTables = [];
            $errors = [];

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Lấy danh sách tất cả tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = "Tables_in_{$databaseName}";

            // Xóa từng table một cách an toàn
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                try {
                    // Kiểm tra table có tồn tại không
                    $exists = DB::select("SHOW TABLES LIKE '{$tableName}'");

                    if (!empty($exists)) {
                        DB::statement("DROP TABLE `{$tableName}`");
                        $deletedTables[] = $tableName;
                    }
                } catch (Exception $e) {
                    $errors[] = "Không thể xóa table '{$tableName}': " . $e->getMessage();
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Kiểm tra xem còn table nào không
            $remainingTables = DB::select('SHOW TABLES');

            if (empty($remainingTables)) {
                $results['database'] = [
                    'status' => 'success',
                    'message' => 'Đã xóa hoàn toàn tất cả bảng trong database',
                    'dropped_tables' => $deletedTables,
                    'tables_count' => count($deletedTables),
                    'remaining_tables' => 0,
                    'errors' => $errors
                ];
            } else {
                $remainingTableNames = [];
                foreach ($remainingTables as $table) {
                    $remainingTableNames[] = $table->$tableKey;
                }

                $results['database'] = [
                    'status' => 'warning',
                    'message' => 'Đã xóa hầu hết bảng, nhưng còn lại ' . count($remainingTables) . ' bảng',
                    'dropped_tables' => $deletedTables,
                    'tables_count' => count($deletedTables),
                    'remaining_tables' => count($remainingTables),
                    'remaining_table_names' => $remainingTableNames,
                    'errors' => $errors
                ];
            }

        } catch (Exception $e) {
            $results['database'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset database: ' . $e->getMessage(),
                'dropped_tables' => $deletedTables ?? [],
                'tables_count' => count($deletedTables ?? [])
            ];
        }
    }

    /**
     * Reset storage - xóa tất cả files trong storage
     */
    private static function resetStorage(&$results): void
    {
        try {
            $deletedDirectories = [];
            $deletedFiles = 0;

            // Danh sách thư mục cần xóa trong storage/app/public
            $directories = [
                'courses', 'posts', 'sliders', 'testimonials', 'associations',
                'albums', 'staff', 'partners', 'products', 'brands', 'galleries',
                'system', 'uploads', 'temp'
            ];

            foreach ($directories as $dir) {
                if (Storage::disk('public')->exists($dir)) {
                    $files = Storage::disk('public')->allFiles($dir);
                    $deletedFiles += count($files);

                    Storage::disk('public')->deleteDirectory($dir);
                    $deletedDirectories[] = $dir;
                }
            }

            // Xóa các file rời trong root của storage/app/public
            $rootFiles = Storage::disk('public')->files('');
            foreach ($rootFiles as $file) {
                if (!in_array($file, ['.gitignore'])) {
                    Storage::disk('public')->delete($file);
                    $deletedFiles++;
                }
            }

            $results['storage'] = [
                'status' => 'success',
                'message' => "Đã xóa {$deletedFiles} files và " . count($deletedDirectories) . " thư mục",
                'directories' => $deletedDirectories,
                'files_count' => $deletedFiles
            ];

        } catch (Exception $e) {
            $results['storage'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset storage: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Clear tất cả cache
     */
    private static function clearAllCache(&$results): void
    {
        try {
            $clearedCaches = [];

            // Clear Laravel caches
            $cacheCommands = [
                'cache:clear' => 'Application cache',
                'config:clear' => 'Configuration cache',
                'route:clear' => 'Route cache',
                'view:clear' => 'View cache',
                'event:clear' => 'Event cache'
            ];

            foreach ($cacheCommands as $command => $description) {
                try {
                    Artisan::call($command);
                    $clearedCaches[] = $description;
                } catch (Exception $e) {
                    // Bỏ qua nếu không clear được
                }
            }

            $results['cache'] = [
                'status' => 'success',
                'message' => 'Đã clear ' . count($clearedCaches) . ' loại cache',
                'caches' => $clearedCaches
            ];

        } catch (Exception $e) {
            $results['cache'] = [
                'status' => 'error',
                'message' => 'Lỗi khi clear cache: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset generated code files
     */
    private static function resetGeneratedCode(&$results): void
    {
        try {
            $deletedFiles = [];
            $deletedDirs = [];

            // Xóa thư mục app/Generated
            $generatedPath = app_path('Generated');
            if (is_dir($generatedPath)) {
                self::deleteDirectory($generatedPath, $deletedFiles);
                $deletedDirs[] = 'app/Generated';
            }

            $results['generated_code'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' files và ' . count($deletedDirs) . ' thư mục generated code',
                'files' => $deletedFiles,
                'directories' => $deletedDirs
            ];

        } catch (Exception $e) {
            $results['generated_code'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa generated code: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset migration files dư thừa (giữ lại core migrations)
     */
    private static function resetMigrationFiles(&$results): void
    {
        try {
            $deletedFiles = [];

            // Core migrations cần giữ lại
            $coreMigrations = [
                '2014_10_12_000000_create_users_table.php',
                '2014_10_12_100000_create_password_reset_tokens_table.php',
                '2019_08_19_000000_create_failed_jobs_table.php',
                '2019_12_14_000001_create_personal_access_tokens_table.php',
                '2025_05_09_112506_create_settings_table.php',
                '2025_05_31_131928_update_settings_table_remove_dmca_add_messenger.php',
                '2025_06_03_195415_add_placeholder_image_to_settings_table.php',
                '2025_06_06_000028_create_notifications_table.php',
                '2025_06_06_122558_create_visitors_table.php',
                '2025_06_09_011044_create_setup_modules_table.php',
                '2025_06_09_105528_create_permission_tables.php',
            ];

            $migrationPath = database_path('migrations');
            $allMigrations = glob($migrationPath . '/*.php');

            foreach ($allMigrations as $migration) {
                $filename = basename($migration);
                if (!in_array($filename, $coreMigrations)) {
                    unlink($migration);
                    $deletedFiles[] = $filename;
                }
            }

            $results['migrations'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' migration files dư thừa',
                'files' => $deletedFiles
            ];

        } catch (Exception $e) {
            $results['migrations'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa migration files: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset Filament resources không cần thiết
     */
    private static function resetFilamentResources(&$results): void
    {
        try {
            // Sử dụng CodeGenerator để xóa generated files
            $cleanupResult = CodeGenerator::cleanupGeneratedFiles();

            if ($cleanupResult['success']) {
                $results['filament_resources'] = [
                    'status' => 'success',
                    'message' => $cleanupResult['message'],
                    'files' => $cleanupResult['files']
                ];
            } else {
                $results['filament_resources'] = [
                    'status' => 'error',
                    'message' => $cleanupResult['message']
                ];
            }

        } catch (Exception $e) {
            $results['filament_resources'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa Filament resources: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset Observer files dư thừa (giữ lại core observers)
     */
    private static function resetObserverFiles(&$results): void
    {
        try {
            $deletedFiles = [];

            // Core observers cần giữ lại
            $coreObservers = [
                'SettingObserver.php',
            ];

            $observerPath = app_path('Observers');
            if (is_dir($observerPath)) {
                $observers = glob($observerPath . '/*.php');
                foreach ($observers as $observer) {
                    $filename = basename($observer);
                    if (!in_array($filename, $coreObservers)) {
                        unlink($observer);
                        $deletedFiles[] = $filename;
                    }
                }
            }

            $results['observers'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' Observer files',
                'files' => $deletedFiles
            ];

        } catch (Exception $e) {
            $results['observers'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa Observer files: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset Livewire components
     */
    private static function resetLivewireComponents(&$results): void
    {
        try {
            $deletedFiles = [];

            $livewirePath = app_path('Livewire');
            if (is_dir($livewirePath)) {
                self::deleteDirectory($livewirePath, $deletedFiles);
            }

            // Xóa view files của Livewire components
            $livewireViewPath = resource_path('views/livewire');
            if (is_dir($livewireViewPath)) {
                self::deleteDirectory($livewireViewPath, $deletedFiles);
            }

            $results['livewire'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' Livewire components',
                'files' => $deletedFiles
            ];

        } catch (Exception $e) {
            $results['livewire'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa Livewire components: ' . $e->getMessage()
            ];
        }
    }



    /**
     * Reset ViewServiceProvider về trạng thái cơ bản
     */
    private static function resetViewServiceProvider(&$results): void
    {
        try {
            $viewServiceProviderPath = app_path('Providers/ViewServiceProvider.php');

            $basicContent = '<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share global data with all views
        View::composer(\'*\', function ($view) {
            $this->shareGlobalData($view);
        });
    }

    /**
     * Share global data với tất cả views
     */
    private function shareGlobalData($view): void
    {
        // Cache settings for 1 hour (with safe fallback)
        $settings = Cache::remember(\'global_settings\', 3600, function () {
            try {
                return Setting::first();
            } catch (\Exception $e) {
                // Return null if table doesn\'t exist (during setup)
                return null;
            }
        });

        $view->with([
            \'globalSettings\' => $settings,
            \'settings\' => $settings, // Keep for backward compatibility
        ]);
    }

    /**
     * Clear cache cho model cụ thể
     */
    public static function refreshCache(string $cacheKey = \'all\'): void
    {
        if ($cacheKey === \'all\') {
            Cache::forget(\'global_settings\');
        } else {
            Cache::forget(\'global_settings\');
        }
    }

    /**
     * Clear tất cả cache
     */
    public static function clearCache(): void
    {
        Cache::forget(\'global_settings\');
    }
}
';

            file_put_contents($viewServiceProviderPath, $basicContent);

            $results['view_service_provider'] = [
                'status' => 'success',
                'message' => 'Đã reset ViewServiceProvider về trạng thái cơ bản'
            ];

        } catch (Exception $e) {
            $results['view_service_provider'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset ViewServiceProvider: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset routes/web.php về trạng thái cơ bản
     */
    private static function resetWebRoutes(&$results): void
    {
        try {
            $webRoutesPath = base_path('routes/web.php');

            $basicRoutes = '<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SetupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Routes accessible to all users without authentication
*/
Route::get(\'/\', [HomeController::class, \'index\'])->name(\'storeFront\');

/*
|--------------------------------------------------------------------------
| Setup System Routes
|--------------------------------------------------------------------------
| Setup wizard for new projects - can be removed after setup is complete
*/
Route::prefix(\'setup\')->name(\'setup.\')->group(function () {
    Route::get(\'/\', [SetupController::class, \'index\'])->name(\'index\');
    Route::get(\'/step/{step}\', [SetupController::class, \'step\'])->name(\'step\');
    Route::post(\'/process/{step}\', [SetupController::class, \'process\'])->name(\'process\');
    Route::post(\'/complete\', [SetupController::class, \'complete\'])->name(\'complete\');
    Route::post(\'/reset\', [SetupController::class, \'reset\'])->name(\'reset\')->withoutMiddleware([\'csrf\']);
});

/*
|--------------------------------------------------------------------------
| Development Routes
|--------------------------------------------------------------------------
| Routes for development and testing - only available in local environment
*/
if (app()->environment(\'local\')) {
    Route::get(\'/dev/clear-cache\', function () {
        Artisan::call(\'cache:clear\');
        Artisan::call(\'config:clear\');
        Artisan::call(\'view:clear\');
        Artisan::call(\'route:clear\');

        return response()->json([
            \'message\' => \'Cache cleared successfully!\'
        ]);
    })->name(\'dev.clear-cache\');
}
';

            file_put_contents($webRoutesPath, $basicRoutes);

            $results['web_routes'] = [
                'status' => 'success',
                'message' => 'Đã reset routes/web.php về trạng thái cơ bản'
            ];

        } catch (Exception $e) {
            $results['web_routes'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset web routes: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset config files về trạng thái mặc định
     */
    private static function resetConfigFiles(&$results): void
    {
        try {
            $resetConfigs = [];

            // Reset app.php về tên mặc định
            $appConfigPath = config_path('app.php');
            if (file_exists($appConfigPath)) {
                $content = file_get_contents($appConfigPath);
                $content = preg_replace(
                    "/\'name\' => env\(\'APP_NAME\', \'[^\']*\'\)/",
                    "'name' => env('APP_NAME', 'Core Laravel Framework')",
                    $content
                );
                file_put_contents($appConfigPath, $content);
                $resetConfigs[] = 'app.php';
            }

            $results['config_files'] = [
                'status' => 'success',
                'message' => 'Đã reset ' . count($resetConfigs) . ' config files',
                'files' => $resetConfigs
            ];

        } catch (Exception $e) {
            $results['config_files'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset config files: ' . $e->getMessage()
            ];
        }
    }



    /**
     * Helper method để xóa thư mục và tất cả files bên trong
     */
    private static function deleteDirectory(string $dir, array &$deletedFiles): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                self::deleteDirectory($filePath, $deletedFiles);
            } else {
                unlink($filePath);
                $deletedFiles[] = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);
            }
        }

        rmdir($dir);
    }
}
