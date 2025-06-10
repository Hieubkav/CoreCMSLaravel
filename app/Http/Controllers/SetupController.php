<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Actions\SetupDatabase;
use App\Actions\CreateAdminUser;
use App\Actions\SaveWebsiteSettings;
use App\Actions\SaveAdvancedConfiguration;
use App\Actions\ImportSampleData;
use App\Actions\CheckModuleVisibility;
use Exception;

class SetupController extends Controller
{
    /**
     * Trang chủ setup - kiểm tra trạng thái cài đặt
     */
    public function index(Request $request)
    {
        $isSetupCompleted = $this->isSetupCompleted();

        // Trong môi trường local, luôn cho phép truy cập setup wizard
        if (app()->environment('local')) {
            return view('setup.index', [
                'isSetupCompleted' => $isSetupCompleted
            ]);
        }

        // Trong production, chỉ cho phép khi chưa setup
        if ($isSetupCompleted) {
            return redirect()->route('storeFront')->with('info', 'Hệ thống đã được cài đặt.');
        }

        return view('setup.index', [
            'isSetupCompleted' => $isSetupCompleted
        ]);
    }

    /**
     * Hiển thị từng bước setup
     */
    public function step($step)
    {
        // Trong production, kiểm tra xem đã setup chưa
        if (!app()->environment('local') && $this->isSetupCompleted()) {
            return redirect()->route('storeFront');
        }

        $steps = $this->getSetupSteps();

        if (!isset($steps[$step])) {
            return redirect()->route('setup.index');
        }

        // Calculate step numbers and grouping
        $currentStepNumber = $steps[$step]['step'];
        $totalSteps = count($steps);

        // Group steps by category
        $groupedSteps = [];
        foreach ($steps as $stepKey => $stepData) {
            $group = $stepData['group'];
            if (!isset($groupedSteps[$group])) {
                $groupedSteps[$group] = [];
            }
            $groupedSteps[$group][$stepKey] = $stepData;
        }

        return view("setup.steps.{$step}", [
            'step' => $step,
            'stepData' => $steps[$step],
            'allSteps' => $steps,
            'currentStepKey' => $step,
            'currentStepNumber' => $currentStepNumber,
            'totalSteps' => $totalSteps,
            'groupedSteps' => $groupedSteps
        ]);
    }

    /**
     * Xử lý từng bước setup
     */
    public function process(Request $request, $step)
    {
        try {
            switch ($step) {
                // Core steps
                case 'database':
                    return $this->processDatabaseStep($request);
                case 'admin':
                    return $this->processAdminStep($request);
                case 'website':
                    return $this->processWebsiteStep($request);
                case 'sample-data':
                    return $this->processSampleDataStep($request);

                // System configuration steps
                case 'frontend-config':
                    return $this->processFrontendConfigStep($request);
                case 'admin-config':
                    return $this->processAdminConfigStep($request);

                // Module steps - Generate code ngay khi module được enable
                case 'module-user-roles':
                    return $this->processModuleStepWithGeneration($request, 'user_roles');
                case 'module-blog':
                    return $this->processModuleStepWithGeneration($request, 'blog');
                case 'module-staff':
                    return $this->processModuleStepWithGeneration($request, 'staff');
                case 'module-content':
                    return $this->processModuleStepWithGeneration($request, 'content_sections');
                case 'module-ecommerce':
                    return $this->processModuleStepWithGeneration($request, 'ecommerce');
                case 'module-layout':
                    return $this->processModuleStepWithGeneration($request, 'layout_components');
                case 'module-settings':
                    return $this->processModuleStepWithGeneration($request, 'settings_expansion');
                case 'module-webdesign':
                    return $this->processModuleStepWithGeneration($request, 'web_design_management');
                case 'module-advanced':
                    return $this->processModuleStepWithGeneration($request, 'advanced_features');

                // Final steps
                case 'modules-summary':
                    return $this->processModulesSummaryStep($request);
                case 'installation':
                    return $this->processInstallationStep($request);

                // Legacy steps (for backward compatibility)
                case 'configuration':
                    return $this->processConfigurationStep($request);

                default:
                    return response()->json(['error' => 'Bước không hợp lệ'], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hoàn thành quá trình setup
     */
    public function complete(Request $request)
    {
        try {
            // Đánh dấu đã hoàn thành setup bằng cách tạo/cập nhật settings
            $settings = Setting::first();
            if ($settings) {
                // Có thể thêm một field setup_completed vào settings table
                // Hoặc đơn giản là đảm bảo có ít nhất 1 record settings
                $settings->update(['status' => 'active']);
            } else {
                Setting::create([
                    'site_name' => 'Core Framework',
                    'status' => 'active'
                ]);
            }

            // Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Cài đặt hoàn tất!',
                'redirect' => route('storeFront')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Không thể hoàn thành cài đặt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý bước cấu hình database
     */
    private function processDatabaseStep(Request $request)
    {
        $request->validate([
            'test_connection' => 'boolean'
        ]);

        $result = $request->test_connection
            ? SetupDatabase::testConnection()
            : SetupDatabase::runMigrations();

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json(['error' => $result['error']], 500);
        }
    }

    /**
     * Xử lý bước tạo admin
     */
    private function processAdminStep(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation']);
        $result = CreateAdminUser::createWithValidation($data);

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 422);
        }
    }

    /**
     * Xử lý bước cấu hình website
     */
    private function processWebsiteStep(Request $request)
    {
        $data = $request->only([
            // Basic information
            'site_name', 'slogan', 'footer_description',

            // SEO information
            'seo_title', 'seo_description',

            // Contact information
            'email', 'hotline', 'address', 'working_hours',

            // Social media links
            'facebook_link', 'zalo_link', 'youtube_link', 'tiktok_link', 'messenger_link'
        ]);

        try {
            // Tự động sử dụng default images (không cần upload trong setup)
            $imageResults = [];

            // Sử dụng default logo
            $defaultLogoResult = $this->useDefaultLogo();
            if ($defaultLogoResult['success']) {
                $data['logo_link'] = $defaultLogoResult['path'];
                $imageResults[] = 'Sử dụng default logo';
            }

            // Sử dụng default favicon
            $defaultFaviconResult = $this->useDefaultFavicon();
            if ($defaultFaviconResult['success']) {
                $data['favicon_link'] = $defaultFaviconResult['path'];
                $imageResults[] = 'Sử dụng default favicon';
            }

            // Placeholder fallback về logo
            if (!empty($data['logo_link'])) {
                $data['placeholder_image'] = $data['logo_link'];
                $imageResults[] = 'Placeholder sử dụng logo';
            }

            $result = SaveWebsiteSettings::saveWithValidation($data);

            if ($result['success']) {
                $message = $result['message'];
                if (!empty($imageResults)) {
                    $message .= ' ' . implode(', ', $imageResults) . '.';
                }
                $result['message'] = $message;
                $result['images'] = $imageResults;

                return response()->json($result);
            } else {
                return response()->json($result, 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Xử lý bước cấu hình nâng cao
     */
    private function processConfigurationStep(Request $request)
    {
        $data = $request->all();
        $result = SaveAdvancedConfiguration::saveWithValidation($data);

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 422);
        }
    }

    /**
     * Xử lý bước import dữ liệu mẫu
     */
    private function processSampleDataStep(Request $request)
    {
        $request->validate([
            'import_sample' => 'boolean'
        ]);

        $result = ImportSampleData::run($request->import_sample ?? false);

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json(['error' => $result['error']], 500);
        }
    }

    /**
     * Kiểm tra xem đã setup chưa
     */
    private function isSetupCompleted()
    {
        try {
            // Kiểm tra xem có settings record nào không
            // Nếu có ít nhất 1 record settings với site_name thì coi như đã setup
            return Setting::whereNotNull('site_name')->exists();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Reset toàn bộ setup - chỉ cho phép trong local environment
     */
    public function reset(Request $request)
    {
        // Chỉ cho phép trong local environment
        if (!app()->environment('local')) {
            abort(403, 'Reset chỉ được phép trong môi trường development');
        }

        try {
            // Xác nhận reset
            if (!$request->has('confirm') || $request->confirm !== 'yes') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng xác nhận reset'
                ], 400);
            }

            $resetResults = [];

            // 1. Xóa generated code files
            $this->resetGeneratedCode($resetResults);

            // 2. Xóa migration files dư thừa
            $this->resetMigrationFiles($resetResults);

            // 3. Xóa Filament resources không cần thiết
            $this->resetFilamentResources($resetResults);

            // 4. Xóa Observer files dư thừa
            $this->resetObserverFiles($resetResults);

            // 5. Xóa Livewire components
            $this->resetLivewireComponents($resetResults);

            // 6. Xóa Seeder files dư thừa
            $this->resetSeederFiles($resetResults);

            // 7. Reset ViewServiceProvider
            $this->resetViewServiceProvider($resetResults);

            // 8. Reset routes/web.php
            $this->resetWebRoutes($resetResults);

            // 9. Xóa tất cả dữ liệu trong database (trừ migrations)
            $this->resetDatabase($resetResults);

            // 10. Xóa tất cả files trong storage
            $this->resetStorage($resetResults);

            // 11. Reset config files về mặc định
            $this->resetConfigFiles($resetResults);

            // 12. Clear tất cả cache
            $this->clearAllCache($resetResults);

            // 13. Reset session
            session()->flush();

            return response()->json([
                'success' => true,
                'message' => 'Reset thành công! Hệ thống đã trở về trạng thái ban đầu.',
                'details' => $resetResults
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi reset: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset database - xóa hoàn toàn tất cả tables bao gồm cả migrations table
     */
    private function resetDatabase(&$results)
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
    private function resetStorage(&$results)
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
    private function clearAllCache(&$results)
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
    private function resetGeneratedCode(&$results)
    {
        try {
            $deletedFiles = [];
            $deletedDirs = [];

            // Xóa thư mục app/Generated
            $generatedPath = app_path('Generated');
            if (is_dir($generatedPath)) {
                $this->deleteDirectory($generatedPath, $deletedFiles);
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
    private function resetMigrationFiles(&$results)
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
    private function resetFilamentResources(&$results)
    {
        try {
            $deletedFiles = [];

            // Xóa tất cả resources trong app/Filament/Admin/Resources (trừ UserResource)
            $adminResourcesPath = app_path('Filament/Admin/Resources');
            if (is_dir($adminResourcesPath)) {
                $resources = glob($adminResourcesPath . '/*Resource.php');
                foreach ($resources as $resource) {
                    $filename = basename($resource);
                    if ($filename !== 'UserResource.php') {
                        unlink($resource);
                        $deletedFiles[] = 'Admin/Resources/' . $filename;
                    }
                }
            }

            // Xóa tất cả resources trong app/Filament/Resources
            $resourcesPath = app_path('Filament/Resources');
            if (is_dir($resourcesPath)) {
                $this->deleteDirectory($resourcesPath, $deletedFiles);
            }

            $results['filament_resources'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' Filament resources',
                'files' => $deletedFiles
            ];

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
    private function resetObserverFiles(&$results)
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
    private function resetLivewireComponents(&$results)
    {
        try {
            $deletedFiles = [];

            $livewirePath = app_path('Livewire');
            if (is_dir($livewirePath)) {
                $this->deleteDirectory($livewirePath, $deletedFiles);
            }

            // Xóa view files của Livewire components
            $livewireViewPath = resource_path('views/livewire');
            if (is_dir($livewireViewPath)) {
                $this->deleteDirectory($livewireViewPath, $deletedFiles);
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
     * Reset Seeder files dư thừa (giữ lại core seeders)
     */
    private function resetSeederFiles(&$results)
    {
        try {
            $deletedFiles = [];

            // Core seeders cần giữ lại
            $coreSeeders = [
                'DatabaseSeeder.php',
                'UserSeeder.php',
            ];

            $seederPath = database_path('seeders');
            if (is_dir($seederPath)) {
                $seeders = glob($seederPath . '/*.php');
                foreach ($seeders as $seeder) {
                    $filename = basename($seeder);
                    if (!in_array($filename, $coreSeeders)) {
                        unlink($seeder);
                        $deletedFiles[] = $filename;
                    }
                }
            }

            $results['seeders'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedFiles) . ' Seeder files',
                'files' => $deletedFiles
            ];

        } catch (Exception $e) {
            $results['seeders'] = [
                'status' => 'error',
                'message' => 'Lỗi khi xóa Seeder files: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset ViewServiceProvider về trạng thái cơ bản
     */
    private function resetViewServiceProvider(&$results)
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
    private function resetWebRoutes(&$results)
    {
        try {
            $webRoutesPath = base_path('routes/web.php');

            $basicRoutes = '<?php

use Illuminate\Support\Facades\Route;
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
    private function resetConfigFiles(&$results)
    {
        try {
            $resetConfigs = [];

            // Reset app.php về tên mặc định
            $appConfigPath = config_path('app.php');
            if (file_exists($appConfigPath)) {
                $content = file_get_contents($appConfigPath);
                $content = preg_replace(
                    "/\'name\' => env\(\'APP_NAME\', \'[^\']*\'\)/",
                    "\'name\' => env(\'APP_NAME\', \'Core Laravel Framework\')",
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
    private function deleteDirectory(string $dir, array &$deletedFiles): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath, $deletedFiles);
            } else {
                unlink($filePath);
                $deletedFiles[] = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath);
            }
        }

        rmdir($dir);
    }

    /**
     * Lấy danh sách các bước setup
     */
    private function getSetupSteps()
    {
        return [
            // Core Setup Steps (4 bước cơ bản)
            'database' => [
                'title' => 'Cấu hình Database',
                'description' => 'Kiểm tra kết nối và tạo bảng database',
                'group' => 'core',
                'step' => 1
            ],
            'admin' => [
                'title' => 'Tạo tài khoản Admin',
                'description' => 'Tạo tài khoản quản trị viên đầu tiên',
                'group' => 'core',
                'step' => 2
            ],
            'website' => [
                'title' => 'Cấu hình Website',
                'description' => 'Thiết lập thông tin cơ bản của website',
                'group' => 'core',
                'step' => 3
            ],

            // System Configuration Steps (2 bước cấu hình hệ thống)
            'frontend-config' => [
                'title' => 'Cấu hình Frontend',
                'description' => 'Theme, màu sắc, font chữ cho giao diện người dùng',
                'group' => 'system',
                'step' => 4
            ],
            'admin-config' => [
                'title' => 'Cấu hình Admin Dashboard',
                'description' => 'Tùy chỉnh giao diện và tính năng admin panel',
                'group' => 'system',
                'step' => 5
            ],

            // Module Selection Steps (9 bước module)
            'module-system-config' => [
                'title' => 'System Configuration',
                'description' => 'Theme, colors, fonts, analytics',
                'group' => 'modules',
                'step' => 6,
                'optional' => false,
                'module_key' => 'system_configuration'
            ],
            'module-user-roles' => [
                'title' => 'User Roles & Permissions',
                'description' => 'Quản lý vai trò và quyền hạn người dùng',
                'group' => 'modules',
                'step' => 7,
                'optional' => true,
                'module_key' => 'user_roles_permissions'
            ],
            'module-blog' => [
                'title' => 'Blog & Posts',
                'description' => 'Hệ thống bài viết, tin tức và blog',
                'group' => 'modules',
                'step' => 8,
                'optional' => true,
                'module_key' => 'blog_posts'
            ],
            'module-staff' => [
                'title' => 'Staff Management',
                'description' => 'Quản lý nhân viên và thông tin liên hệ',
                'group' => 'modules',
                'step' => 9,
                'optional' => true,
                'module_key' => 'staff'
            ],
            'module-content' => [
                'title' => 'Content Sections',
                'description' => 'Slider, Gallery, FAQ, Testimonials, v.v.',
                'group' => 'modules',
                'step' => 10,
                'optional' => true,
                'module_key' => 'content_sections'
            ],
            'module-ecommerce' => [
                'title' => 'E-commerce',
                'description' => 'Sản phẩm, đơn hàng, thanh toán',
                'group' => 'modules',
                'step' => 11,
                'optional' => true,
                'module_key' => 'ecommerce'
            ],
            'module-layout' => [
                'title' => 'Layout Components',
                'description' => 'Header, Footer, Navigation, Sidebar',
                'group' => 'modules',
                'step' => 12,
                'optional' => false,
                'module_key' => 'layout_components'
            ],
            'module-settings' => [
                'title' => 'Settings Expansion',
                'description' => 'Website info, SEO, contact, social media',
                'group' => 'modules',
                'step' => 13,
                'optional' => false,
                'module_key' => 'settings_expansion'
            ],
            'module-webdesign' => [
                'title' => 'Web Design Management',
                'description' => 'Component visibility và ordering',
                'group' => 'modules',
                'step' => 14,
                'optional' => false,
                'module_key' => 'web_design_management'
            ],

            // Final Steps (4 bước cuối)
            'sample-data' => [
                'title' => 'Dữ liệu mẫu',
                'description' => 'Tạo dữ liệu mẫu cho modules đã chọn',
                'group' => 'final',
                'step' => 15
            ],
            'modules-summary' => [
                'title' => 'Tổng quan Modules',
                'description' => 'Xem lại các module đã chọn và cấu hình',
                'group' => 'final',
                'step' => 16
            ],
            'installation' => [
                'title' => 'Cài đặt & Cấu hình',
                'description' => 'Generate code và cài đặt modules',
                'group' => 'final',
                'step' => 17
            ],
            'complete' => [
                'title' => 'Hoàn thành',
                'description' => 'Setup hoàn tất, sẵn sàng sử dụng',
                'group' => 'final',
                'step' => 18
            ]
        ];
    }



    /**
     * Xử lý bước cấu hình frontend
     */
    private function processFrontendConfigStep(Request $request)
    {
        $data = $request->all();

        try {
            // Bước 1: Tạo bảng system_configurations nếu chưa có
            $this->ensureSystemConfigurationsTableExists();

            // Bước 2: Tự động sử dụng default favicon (không cần upload trong setup)
            $defaultFaviconResult = $this->useDefaultFavicon();
            if ($defaultFaviconResult['success']) {
                $data['favicon_path'] = $defaultFaviconResult['path'];
            }

            // Bước 3: Xử lý error_pages nếu là JSON string
            if (isset($data['error_pages']) && is_string($data['error_pages'])) {
                $data['error_pages'] = json_decode($data['error_pages'], true);
            }

            // Bước 4: Lưu cấu hình vào database
            $result = \App\Actions\SaveSystemConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['frontend_config' => $data]);

                $message = 'Đã tạo bảng system_configurations và lưu cấu hình frontend thành công!';
                if (isset($faviconResult) && $faviconResult['success']) {
                    $message .= ' Favicon đã được upload và copy vào public/favicon.ico.';
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'config_id' => $result['config']->id ?? null,
                    'table_created' => true,
                    'favicon_uploaded' => isset($faviconResult) && $faviconResult['success']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình frontend'
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Xử lý bước cấu hình admin
     */
    private function processAdminConfigStep(Request $request)
    {
        $data = $request->all();

        try {
            // Bước 1: Tạo bảng admin_configurations nếu chưa có
            $this->ensureAdminConfigurationsTableExists();

            // Bước 2: Lưu cấu hình vào database
            $result = \App\Actions\SaveAdminConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['admin_config' => $data]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã tạo bảng admin_configurations và lưu cấu hình admin dashboard thành công!',
                    'config_id' => $result['config']->id ?? null,
                    'table_created' => true
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình admin'
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Xử lý các bước module với generation code ngay lập tức
     */
    private function processModuleStepWithGeneration(Request $request, $moduleKey)
    {
        $data = $request->all();
        $data['module_key'] = $moduleKey;

        // Lưu ngay vào database thay vì session
        $result = $this->saveModuleConfiguration($moduleKey, $data);

        if ($result['success']) {
            // Vẫn lưu vào session để backup
            $moduleConfigs = session('module_configs', []);
            $moduleConfigs[$moduleKey] = $data;
            session(['module_configs' => $moduleConfigs]);

            // Clear module visibility cache khi có thay đổi
            \App\Actions\Module\CheckModuleVisibility::clearModuleCache();

            $generationResults = [];

            // Nếu module được enable, generate code ngay lập tức
            if ($data['enable_module'] ?? false) {
                try {
                    // Generate code cho module
                    $generateResult = \App\Actions\Setup\GenerateModuleCode::handle($moduleKey, $data);

                    if ($generateResult['success']) {
                        $generationResults['generation'] = $generateResult['results'];

                        // Chạy migrations nếu có
                        if (!empty($generateResult['results']['migrations'])) {
                            Artisan::call('migrate', ['--force' => true]);
                            $generationResults['migration'] = 'Migrations executed successfully';
                        }

                        // Clear cache sau khi generate
                        Artisan::call('cache:clear');
                        Artisan::call('config:clear');

                    } else {
                        $generationResults['generation_error'] = $generateResult['error'];
                    }

                } catch (Exception $e) {
                    $generationResults['generation_error'] = $e->getMessage();
                }
            }

            $message = $data['enable_module'] ?? false
                ? "Đã lưu cấu hình module {$moduleKey} vào database và generate code thành công!"
                : "Đã lưu trạng thái bỏ qua module {$moduleKey} vào database!";

            return response()->json([
                'success' => true,
                'message' => $message,
                'module_key' => $moduleKey,
                'enabled' => $data['enable_module'] ?? false,
                'module_id' => $result['module_id'] ?? null,
                'generation_results' => $generationResults
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? "Có lỗi xảy ra khi lưu cấu hình module {$moduleKey}"
            ], 422);
        }
    }

    /**
     * Xử lý các bước module (legacy method)
     */
    private function processModuleStep(Request $request, $moduleKey)
    {
        $data = $request->all();
        $data['module_key'] = $moduleKey;

        // Lưu ngay vào database thay vì session
        $result = $this->saveModuleConfiguration($moduleKey, $data);

        if ($result['success']) {
            // Vẫn lưu vào session để backup
            $moduleConfigs = session('module_configs', []);
            $moduleConfigs[$moduleKey] = $data;
            session(['module_configs' => $moduleConfigs]);

            // Clear module visibility cache khi có thay đổi
            CheckModuleVisibility::clearModuleCache();

            $message = $data['enable_module'] ?? false
                ? "Đã lưu cấu hình module {$moduleKey} vào database thành công!"
                : "Đã lưu trạng thái bỏ qua module {$moduleKey} vào database!";

            return response()->json([
                'success' => true,
                'message' => $message,
                'module_key' => $moduleKey,
                'enabled' => $data['enable_module'] ?? false,
                'module_id' => $result['module_id'] ?? null
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? "Có lỗi xảy ra khi lưu cấu hình module {$moduleKey}"
            ], 422);
        }
    }

    /**
     * Sử dụng default favicon từ public/images/default_logo.ico
     */
    private function useDefaultFavicon(): array
    {
        try {
            $defaultFaviconPath = public_path('images/default_logo.ico');

            // Kiểm tra file default có tồn tại không
            if (!file_exists($defaultFaviconPath)) {
                return [
                    'success' => false,
                    'message' => 'File default_logo.ico không tồn tại trong public/images/'
                ];
            }

            // Tạo thư mục storage nếu chưa có
            $storagePath = storage_path('app/public/system/favicons');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Copy default favicon vào storage với tên unique
            $filename = 'default_favicon_' . time() . '.ico';
            $storageFilePath = $storagePath . '/' . $filename;
            copy($defaultFaviconPath, $storageFilePath);

            // Copy vào public/favicon.ico để browser detect
            $publicFaviconPath = public_path('favicon.ico');
            copy($defaultFaviconPath, $publicFaviconPath);

            return [
                'success' => true,
                'path' => 'system/favicons/' . $filename,
                'message' => 'Đã sử dụng default favicon và copy vào public/favicon.ico'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng default favicon: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sử dụng default logo từ public/images/default_logo.png
     */
    private function useDefaultLogo(): array
    {
        try {
            $defaultLogoPath = public_path('images/default_logo.png');

            // Kiểm tra file default có tồn tại không
            if (!file_exists($defaultLogoPath)) {
                return [
                    'success' => false,
                    'message' => 'File default_logo.png không tồn tại trong public/images/'
                ];
            }

            // Tạo thư mục storage nếu chưa có
            $storagePath = storage_path('app/public/system/logos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Copy default logo vào storage với tên unique
            $filename = 'default_logo_' . time() . '.png';
            $storageFilePath = $storagePath . '/' . $filename;
            copy($defaultLogoPath, $storageFilePath);

            return [
                'success' => true,
                'path' => 'system/logos/' . $filename,
                'message' => 'Đã sử dụng default logo'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng default logo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý upload ảnh thông thường (logo, placeholder)
     */
    private function handleImageUpload($file, string $directory, string $type): array
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                return ['success' => false, 'message' => "File {$type} không hợp lệ"];
            }

            // Check file size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                return ['success' => false, 'message' => "File {$type} quá lớn (tối đa 5MB)"];
            }

            // Check file type
            $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/svg+xml'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return ['success' => false, 'message' => "File {$type} phải là .png, .jpg, .jpeg, .webp hoặc .svg"];
            }

            // Create storage directory if not exists
            $storagePath = storage_path("app/public/system/{$directory}");
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Generate SEO-friendly filename
            $extension = $file->getClientOriginalExtension();
            $filename = $type . '_' . time() . '.' . $extension;
            $fullPath = $storagePath . '/' . $filename;

            // Save file
            $file->move($storagePath, $filename);

            return [
                'success' => true,
                'path' => "system/{$directory}/" . $filename,
                'message' => ucfirst($type) . ' đã được upload thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Có lỗi xảy ra khi upload {$type}: " . $e->getMessage()
            ];
        }
    }

    /**
     * Xử lý upload favicon
     */
    private function handleFaviconUpload($file): array
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                return ['success' => false, 'message' => 'File favicon không hợp lệ'];
            }

            // Check file size (max 2MB)
            if ($file->getSize() > 2 * 1024 * 1024) {
                return ['success' => false, 'message' => 'File favicon quá lớn (tối đa 2MB)'];
            }

            // Check file type
            $allowedTypes = ['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return ['success' => false, 'message' => 'File favicon phải là .ico, .png, .jpg hoặc .jpeg'];
            }

            // Create storage directory if not exists
            $storagePath = storage_path('app/public/system/favicons');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Generate unique filename
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $storageFaviconPath = $storagePath . '/' . $filename;

            // Save to storage
            $file->move($storagePath, $filename);

            // Copy to public/favicon.ico
            $publicFaviconPath = public_path('favicon.ico');
            copy($storageFaviconPath, $publicFaviconPath);

            return [
                'success' => true,
                'path' => 'system/favicons/' . $filename,
                'message' => 'Favicon đã được upload và copy vào public/favicon.ico thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi upload favicon: ' . $e->getMessage()
            ];
        }
    }



    /**
     * Đảm bảo bảng admin_configurations tồn tại
     */
    private function ensureAdminConfigurationsTableExists(): void
    {
        try {
            // Kiểm tra xem bảng đã tồn tại chưa
            DB::table('admin_configurations')->count();
        } catch (\Exception $e) {
            // Bảng chưa tồn tại, tạo mới
            Schema::create('admin_configurations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();

                // Admin Panel Colors
                $table->string('admin_primary_color')->default('#1f2937');
                $table->string('admin_secondary_color')->default('#374151');

                // Analytics & Tracking
                $table->boolean('visitor_analytics_enabled')->default(false);

                // Performance Settings
                $table->boolean('query_cache')->default(true);
                $table->boolean('eager_loading')->default(true);
                $table->boolean('asset_optimization')->default(true);
                $table->integer('cache_duration')->default(300);
                $table->integer('pagination_size')->default(25);

                // Image Processing
                $table->integer('webp_quality')->default(95);
                $table->integer('max_width')->default(1920);
                $table->integer('max_height')->default(1080);

                // SEO Configuration
                $table->boolean('seo_auto_generate')->default(true);
                $table->string('default_description')->default('Powered by Core Framework');

                // Meta fields
                $table->string('status')->default('active');
                $table->integer('order')->default(0);
                $table->timestamps();
            });

            \Illuminate\Support\Facades\Log::info('Created admin_configurations table during admin config setup');
        }
    }

    /**
     * Đảm bảo bảng system_configurations tồn tại
     */
    private function ensureSystemConfigurationsTableExists(): void
    {
        try {
            // Kiểm tra xem bảng đã tồn tại chưa
            DB::table('system_configurations')->count();
        } catch (\Exception $e) {
            // Bảng chưa tồn tại, tạo mới
            Schema::create('system_configurations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->id();
                $table->string('theme_mode')->default('light_only');
                $table->string('primary_color')->default('#dc2626');
                $table->string('secondary_color')->default('#ffffff');
                $table->string('accent_color')->default('#f3f4f6');
                $table->string('primary_font')->default('Inter');
                $table->string('secondary_font')->default('Inter');
                $table->string('tertiary_font')->default('Inter');
                $table->string('design_style')->default('minimalism');
                $table->json('error_pages')->nullable();
                $table->string('icon_system')->default('fontawesome');
                $table->string('admin_primary_color')->default('#dc2626');
                $table->string('admin_secondary_color')->default('#374151');
                $table->boolean('visitor_analytics_enabled')->default(false);
                $table->string('favicon_path')->nullable();
                $table->string('status')->default('active');
                $table->integer('order')->default(0);
                $table->timestamps();
            });

            \Illuminate\Support\Facades\Log::info('Created system_configurations table during frontend config setup');
        }
    }

    /**
     * Lưu cấu hình module vào database
     */
    private function saveModuleConfiguration(string $moduleKey, array $data): array
    {
        try {
            // Mapping module keys to titles and descriptions
            $moduleInfo = $this->getModuleInfo($moduleKey);

            // Lưu hoặc cập nhật module configuration
            $module = \App\Models\SetupModule::updateOrCreate(
                ['module_name' => $moduleKey],
                [
                    'module_title' => $moduleInfo['title'],
                    'module_description' => $moduleInfo['description'],
                    'is_required' => $moduleInfo['required'] ?? false,
                    'is_installed' => false, // Sẽ được cập nhật khi cài đặt thực tế
                    'configuration' => [
                        'enabled' => $data['enable_module'] ?? false,
                        'create_sample_data' => $data['create_sample_data'] ?? false,
                        'settings' => $data,
                        'configured_at' => now()->toISOString()
                    ]
                ]
            );

            return [
                'success' => true,
                'message' => "Đã lưu cấu hình module {$moduleKey} thành công",
                'module_id' => $module->id
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error saving module configuration for {$moduleKey}", [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => "Có lỗi xảy ra khi lưu cấu hình module: " . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy thông tin module theo key
     */
    private function getModuleInfo(string $moduleKey): array
    {
        $moduleMapping = [
            'user_roles_permissions' => [
                'title' => 'User Roles & Permissions',
                'description' => 'Quản lý vai trò và quyền hạn người dùng',
                'required' => false
            ],
            'blog_posts' => [
                'title' => 'Blog & Posts',
                'description' => 'Hệ thống bài viết, tin tức và blog',
                'required' => false
            ],
            'staff' => [
                'title' => 'Staff Management',
                'description' => 'Quản lý nhân viên và thông tin liên hệ',
                'required' => false
            ],
            'content_sections' => [
                'title' => 'Content Sections',
                'description' => 'Slider, Gallery, FAQ, Testimonials, v.v.',
                'required' => false
            ],
            'ecommerce' => [
                'title' => 'E-commerce',
                'description' => 'Sản phẩm, đơn hàng, thanh toán',
                'required' => false
            ],
            'layout_components' => [
                'title' => 'Layout Components',
                'description' => 'Header, Footer, Navigation, Sidebar',
                'required' => true
            ],
            'settings_expansion' => [
                'title' => 'Settings Expansion',
                'description' => 'Website info, SEO, contact, social media',
                'required' => true
            ],
            'web_design_management' => [
                'title' => 'Web Design Management',
                'description' => 'Component visibility và ordering',
                'required' => true
            ],
            'system_configuration' => [
                'title' => 'System Configuration',
                'description' => 'Theme, colors, fonts, analytics',
                'required' => true
            ]
        ];

        return $moduleMapping[$moduleKey] ?? [
            'title' => ucfirst(str_replace('_', ' ', $moduleKey)),
            'description' => "Module {$moduleKey}",
            'required' => false
        ];
    }

    /**
     * Xử lý bước tổng quan modules
     */
    private function processModulesSummaryStep(Request $request)
    {
        // Lấy tất cả cấu hình đã lưu
        $frontendConfig = session('frontend_config', []);
        $adminConfig = session('admin_config', []);
        $moduleConfigs = session('module_configs', []);

        // Tạo summary
        $summary = [
            'frontend_config' => $frontendConfig,
            'admin_config' => $adminConfig,
            'modules' => $moduleConfigs,
            'enabled_modules' => array_filter($moduleConfigs, function($config) {
                return $config['enable_module'] ?? false;
            })
        ];

        session(['setup_summary' => $summary]);

        return response()->json([
            'success' => true,
            'message' => 'Đã tạo tổng quan cài đặt thành công!',
            'summary' => $summary
        ]);
    }

    /**
     * Xử lý bước cài đặt
     */
    private function processInstallationStep(Request $request)
    {
        try {
            // Lấy tất cả cấu hình từ session
            $summary = session('setup_summary', []);

            // Thực hiện cài đặt thực tế
            $result = \App\Actions\InstallSelectedModules::run($summary);

            if ($result['success']) {
                // Clear session sau khi cài đặt thành công
                session()->forget(['frontend_config', 'admin_config', 'module_configs', 'setup_summary']);

                return response()->json([
                    'success' => true,
                    'message' => 'Cài đặt hoàn tất thành công!',
                    'installed_modules' => $result['installed_modules'] ?? [],
                    'redirect' => route('setup.step', 'complete')
                ]);
            } else {
                return response()->json($result, 422);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Lỗi trong quá trình cài đặt: ' . $e->getMessage()
            ], 500);
        }
    }
}
