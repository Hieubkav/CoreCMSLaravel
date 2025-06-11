<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Setup\Controller\ProcessDatabaseStep;
use App\Actions\Setup\Controller\ProcessAdminStep;
use App\Actions\Setup\Controller\ProcessWebsiteStep;
use App\Actions\Setup\Controller\ProcessFrontendConfigStep;
use App\Actions\Setup\Controller\ProcessAdminConfigStep;
use App\Actions\Setup\Controller\ProcessBlogStep;
use App\Actions\Setup\Controller\ResetSystem;
use App\Actions\Setup\Controller\SetupUtilities;
use App\Actions\Setup\Controller\CompleteSetup;
use Exception;

class SetupController extends Controller
{
    /**
     * Trang chủ setup - kiểm tra trạng thái cài đặt
     */
    public function index(Request $request)
    {
        $isSetupCompleted = SetupUtilities::isSetupCompleted();

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
        if (!app()->environment('local') && SetupUtilities::isSetupCompleted()) {
            return redirect()->route('storeFront');
        }

        $steps = SetupUtilities::getSetupSteps();

        if (!isset($steps[$step])) {
            return redirect()->route('setup.index');
        }

        // Calculate step numbers and grouping
        $stepData = SetupUtilities::calculateStepData($step);

        return view("setup.steps.{$step}", $stepData);
    }

    /**
     * Xử lý từng bước setup
     */
    public function process(Request $request, $step)
    {
        try {
            switch ($step) {
                // Core steps (5 bước chính)
                case 'database':
                    return $this->handleActionResponse(ProcessDatabaseStep::handle($request));
                case 'admin':
                    return $this->handleActionResponse(ProcessAdminStep::handle($request));
                case 'website':
                    return $this->handleActionResponse(ProcessWebsiteStep::handle($request));
                case 'frontend-config':
                    return $this->handleActionResponse(ProcessFrontendConfigStep::handle($request));
                case 'admin-config':
                    return $this->handleActionResponse(ProcessAdminConfigStep::handle($request));
                case 'blog':
                    return $this->handleActionResponse(ProcessBlogStep::handle($request));

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
        return $this->handleActionResponse(CompleteSetup::handle());
    }

    /**
     * Xử lý response từ Actions
     */
    private function handleActionResponse(array $result)
    {
        if ($result['success']) {
            return response()->json($result);
        } else {
            $statusCode = isset($result['error']) ? 500 : 422;
            return response()->json($result, $statusCode);
        }
    }

    /**
     * Reset toàn bộ setup - chỉ cho phép trong local environment
     */
    public function reset(Request $request)
    {
        return $this->handleActionResponse(ResetSystem::handle($request));
    }
}
