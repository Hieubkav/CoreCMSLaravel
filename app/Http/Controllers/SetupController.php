<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Setup\Controller\ProcessDatabaseStep;
use App\Actions\Setup\Controller\ProcessAdminStep;
use App\Actions\Setup\Controller\ProcessWebsiteStep;
use App\Actions\Setup\Controller\ProcessConfigurationSteps;
use App\Actions\Setup\Controller\ProcessSampleDataStep;
use App\Actions\Setup\Controller\ProcessModuleSteps;
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
                // Core steps
                case 'database':
                    return $this->handleActionResponse(ProcessDatabaseStep::handle($request));
                case 'admin':
                    return $this->handleActionResponse(ProcessAdminStep::handle($request));
                case 'website':
                    return $this->handleActionResponse(ProcessWebsiteStep::handle($request));
                case 'sample-data':
                    return $this->handleActionResponse(ProcessSampleDataStep::handle($request));

                // System configuration steps
                case 'frontend-config':
                    return $this->handleActionResponse(ProcessConfigurationSteps::processFrontendConfigStep($request));
                case 'admin-config':
                    return $this->handleActionResponse(ProcessConfigurationSteps::processAdminConfigStep($request));

                // Module steps - Generate code ngay khi module được enable
                case 'module-user-roles':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'user_roles'));
                case 'module-blog':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'blog'));
                case 'module-staff':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'staff'));
                case 'module-content':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'content_sections'));
                case 'module-ecommerce':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'ecommerce'));
                case 'module-layout':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'layout_components'));
                case 'module-settings':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'settings_expansion'));
                case 'module-webdesign':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'web_design_management'));
                case 'module-advanced':
                    return $this->handleActionResponse(ProcessModuleSteps::processModuleStepWithGeneration($request, 'advanced_features'));

                // Final steps
                case 'modules-summary':
                    return $this->handleActionResponse(ProcessModuleSteps::processModulesSummaryStep($request));
                case 'installation':
                    return $this->handleActionResponse(ProcessModuleSteps::processInstallationStep($request));

                // Legacy steps (for backward compatibility)
                case 'configuration':
                    return $this->handleActionResponse(ProcessConfigurationSteps::processConfigurationStep($request));

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
