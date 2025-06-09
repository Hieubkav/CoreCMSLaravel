<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Actions\SetupDatabase;
use App\Actions\CreateAdminUser;
use App\Actions\SaveWebsiteSettings;
use App\Actions\SaveAdvancedConfiguration;
use App\Actions\ImportSampleData;
use App\Services\ModuleVisibilityService;
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

        return view("setup.steps.{$step}", [
            'step' => $step,
            'stepData' => $steps[$step],
            'allSteps' => $steps
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

                // Module steps
                case 'module-user-roles':
                    return $this->processModuleStep($request, 'user_roles_permissions');
                case 'module-blog':
                    return $this->processModuleStep($request, 'blog_posts');
                case 'module-staff':
                    return $this->processModuleStep($request, 'staff');
                case 'module-content':
                    return $this->processModuleStep($request, 'content_sections');
                case 'module-ecommerce':
                    return $this->processModuleStep($request, 'ecommerce');
                case 'module-layout':
                    return $this->processModuleStep($request, 'layout_components');
                case 'module-settings':
                    return $this->processModuleStep($request, 'settings_expansion');
                case 'module-webdesign':
                    return $this->processModuleStep($request, 'web_design_management');
                case 'module-advanced':
                    return $this->processModuleStep($request, 'advanced_features');

                // Final steps
                case 'modules-summary':
                    return $this->processModulesSummaryStep($request);
                case 'installation':
                    return $this->processInstallationStep($request);

                // Legacy steps (for backward compatibility)
                case 'configuration':
                    return $this->processConfigurationStep($request);
                case 'module-selection':
                    return $this->processModuleSelectionStep($request);

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
            'site_name', 'site_description', 'site_keywords',
            'contact_email', 'contact_phone', 'contact_address'
        ]);

        $result = SaveWebsiteSettings::saveWithValidation($data);

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 422);
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

            // 1. Xóa tất cả dữ liệu trong database (trừ migrations)
            $this->resetDatabase($resetResults);

            // 2. Xóa tất cả files trong storage
            $this->resetStorage($resetResults);

            // 3. Clear tất cả cache
            $this->clearAllCache($resetResults);

            // 4. Reset session
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
     * Reset database - xóa tất cả dữ liệu trừ migrations
     */
    private function resetDatabase(&$results)
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $databaseName = DB::getDatabaseName();
            $tableKey = "Tables_in_{$databaseName}";

            $deletedTables = [];
            $skippedTables = ['migrations'];

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                if (in_array($tableName, $skippedTables)) {
                    continue;
                }

                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    DB::table($tableName)->truncate();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    $deletedTables[] = $tableName;
                } catch (Exception $e) {
                    // Nếu không truncate được thì thử drop
                    try {
                        DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                        $deletedTables[] = $tableName . ' (dropped)';
                    } catch (Exception $e2) {
                        // Bỏ qua nếu không xóa được
                    }
                }
            }

            $results['database'] = [
                'status' => 'success',
                'message' => 'Đã xóa ' . count($deletedTables) . ' bảng',
                'tables' => $deletedTables
            ];

        } catch (Exception $e) {
            $results['database'] = [
                'status' => 'error',
                'message' => 'Lỗi khi reset database: ' . $e->getMessage()
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
            'module-selection' => [
                'title' => 'Chọn Modules',
                'description' => 'Lựa chọn các modules cần cài đặt cho dự án',
                'group' => 'core',
                'step' => 4
            ],

            // System Configuration Steps (2 bước cấu hình hệ thống)
            'frontend-config' => [
                'title' => 'Cấu hình Frontend',
                'description' => 'Theme, màu sắc, font chữ cho giao diện người dùng',
                'group' => 'system',
                'step' => 5
            ],
            'admin-config' => [
                'title' => 'Cấu hình Admin Dashboard',
                'description' => 'Tùy chỉnh giao diện và tính năng admin panel',
                'group' => 'system',
                'step' => 6
            ],

            // Module Selection Steps (9 bước module)
            'module-system-config' => [
                'title' => 'System Configuration',
                'description' => 'Theme, colors, fonts, analytics',
                'group' => 'modules',
                'step' => 7,
                'optional' => false,
                'module_key' => 'system_configuration'
            ],
            'module-user-roles' => [
                'title' => 'User Roles & Permissions',
                'description' => 'Quản lý vai trò và quyền hạn người dùng',
                'group' => 'modules',
                'step' => 8,
                'optional' => true,
                'module_key' => 'user_roles_permissions'
            ],
            'module-blog' => [
                'title' => 'Blog & Posts',
                'description' => 'Hệ thống bài viết, tin tức và blog',
                'group' => 'modules',
                'step' => 9,
                'optional' => true,
                'module_key' => 'blog_posts'
            ],
            'module-staff' => [
                'title' => 'Staff Management',
                'description' => 'Quản lý nhân viên và thông tin liên hệ',
                'group' => 'modules',
                'step' => 10,
                'optional' => true,
                'module_key' => 'staff'
            ],
            'module-content' => [
                'title' => 'Content Sections',
                'description' => 'Slider, Gallery, FAQ, Testimonials, v.v.',
                'group' => 'modules',
                'step' => 11,
                'optional' => true,
                'module_key' => 'content_sections'
            ],
            'module-ecommerce' => [
                'title' => 'E-commerce',
                'description' => 'Sản phẩm, đơn hàng, thanh toán',
                'group' => 'modules',
                'step' => 12,
                'optional' => true,
                'module_key' => 'ecommerce'
            ],
            'module-layout' => [
                'title' => 'Layout Components',
                'description' => 'Header, Footer, Navigation, Sidebar',
                'group' => 'modules',
                'step' => 13,
                'optional' => false,
                'module_key' => 'layout_components'
            ],
            'module-settings' => [
                'title' => 'Settings Expansion',
                'description' => 'Website info, SEO, contact, social media',
                'group' => 'modules',
                'step' => 14,
                'optional' => false,
                'module_key' => 'settings_expansion'
            ],
            'module-webdesign' => [
                'title' => 'Web Design Management',
                'description' => 'Component visibility và ordering',
                'group' => 'modules',
                'step' => 15,
                'optional' => false,
                'module_key' => 'web_design_management'
            ],

            // Final Steps (4 bước cuối)
            'sample-data' => [
                'title' => 'Dữ liệu mẫu',
                'description' => 'Tạo dữ liệu mẫu cho modules đã chọn',
                'group' => 'final',
                'step' => 16
            ],
            'modules-summary' => [
                'title' => 'Tổng quan Modules',
                'description' => 'Xem lại các module đã chọn và cấu hình',
                'group' => 'final',
                'step' => 17
            ],
            'installation' => [
                'title' => 'Cài đặt & Cấu hình',
                'description' => 'Generate code và cài đặt modules',
                'group' => 'final',
                'step' => 18
            ],
            'complete' => [
                'title' => 'Hoàn thành',
                'description' => 'Setup hoàn tất, sẵn sàng sử dụng',
                'group' => 'final',
                'step' => 19
            ]
        ];
    }

    /**
     * Xử lý bước chọn modules
     */
    private function processModuleSelectionStep(Request $request)
    {
        $data = $request->only(['selected_modules', 'install_sample_data']);
        $result = \App\Actions\ProcessModuleSelection::run($data);

        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 422);
        }
    }

    /**
     * Xử lý bước cấu hình frontend
     */
    private function processFrontendConfigStep(Request $request)
    {
        $data = $request->all();

        // Lưu cấu hình frontend vào session hoặc database
        session(['frontend_config' => $data]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu cấu hình frontend thành công!'
        ]);
    }

    /**
     * Xử lý bước cấu hình admin
     */
    private function processAdminConfigStep(Request $request)
    {
        $data = $request->all();

        // Lưu cấu hình admin vào session hoặc database
        session(['admin_config' => $data]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu cấu hình admin dashboard thành công!'
        ]);
    }

    /**
     * Xử lý các bước module
     */
    private function processModuleStep(Request $request, $moduleKey)
    {
        $data = $request->all();
        $data['module_key'] = $moduleKey;

        // Lưu cấu hình module vào session
        $moduleConfigs = session('module_configs', []);
        $moduleConfigs[$moduleKey] = $data;
        session(['module_configs' => $moduleConfigs]);

        // Clear module visibility cache khi có thay đổi
        ModuleVisibilityService::clearCache();

        $message = $data['enable_module'] ?? false
            ? "Đã cấu hình module {$moduleKey} thành công!"
            : "Đã bỏ qua module {$moduleKey}";

        return response()->json([
            'success' => true,
            'message' => $message,
            'module_key' => $moduleKey,
            'enabled' => $data['enable_module'] ?? false
        ]);
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
