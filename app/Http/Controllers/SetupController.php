<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;
use App\Actions\SetupDatabase;
use App\Actions\CreateAdminUser;
use App\Actions\SaveWebsiteSettings;
use App\Actions\SaveAdvancedConfiguration;
use App\Actions\ImportSampleData;
use Exception;

class SetupController extends Controller
{
    /**
     * Trang chủ setup - kiểm tra trạng thái cài đặt
     */
    public function index(Request $request)
    {
        // Trong môi trường local, luôn cho phép truy cập setup wizard
        if (app()->environment('local')) {
            return view('setup.index');
        }

        // Trong production, chỉ cho phép khi chưa setup
        if ($this->isSetupCompleted()) {
            return redirect()->route('storeFront')->with('info', 'Hệ thống đã được cài đặt.');
        }

        return view('setup.index');
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
                case 'database':
                    return $this->processDatabaseStep($request);
                
                case 'admin':
                    return $this->processAdminStep($request);
                
                case 'website':
                    return $this->processWebsiteStep($request);

                case 'configuration':
                    return $this->processConfigurationStep($request);

                case 'sample-data':
                    return $this->processSampleDataStep($request);
                
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
     * Lấy danh sách các bước setup
     */
    private function getSetupSteps()
    {
        return [
            'database' => [
                'title' => 'Cấu hình Database',
                'description' => 'Kiểm tra kết nối và tạo bảng database'
            ],
            'admin' => [
                'title' => 'Tạo tài khoản Admin',
                'description' => 'Tạo tài khoản quản trị viên đầu tiên'
            ],
            'website' => [
                'title' => 'Cấu hình Website',
                'description' => 'Thiết lập thông tin cơ bản của website'
            ],
            'configuration' => [
                'title' => 'Cấu hình nâng cao',
                'description' => 'Tùy chỉnh hiệu suất và tính năng nâng cao'
            ],
            'sample-data' => [
                'title' => 'Dữ liệu mẫu',
                'description' => 'Import dữ liệu mẫu để bắt đầu nhanh'
            ]
        ];
    }
}
