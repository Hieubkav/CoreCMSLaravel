<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConfiguration;
use App\Actions\SaveSystemConfiguration;

class SystemConfigController extends Controller
{
    /**
     * Hiển thị trang cấu hình hệ thống (chỉ trong local environment)
     */
    public function index()
    {
        // Chỉ cho phép truy cập trong môi trường local
        if (!app()->environment('local')) {
            abort(404);
        }

        $config = SystemConfiguration::current() ?? SystemConfiguration::getOrCreateDefault();

        return view('system-config.index', compact('config'));
    }

    /**
     * Lưu cấu hình hệ thống
     */
    public function store(Request $request)
    {
        // Chỉ cho phép truy cập trong môi trường local
        if (!app()->environment('local')) {
            abort(404);
        }

        $result = SaveSystemConfiguration::run($request->all());

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->withErrors($result['errors'] ?? [])
                ->with('error', $result['message']);
        }
    }

    /**
     * Reset về cấu hình mặc định
     */
    public function reset()
    {
        // Chỉ cho phép truy cập trong môi trường local
        if (!app()->environment('local')) {
            abort(404);
        }

        $result = SaveSystemConfiguration::resetToDefault();

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * API endpoint để lấy CSS variables
     */
    public function getCssVariables()
    {
        $config = SystemConfiguration::current();

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa có cấu hình hệ thống'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'css_variables' => $config->getCssVariables(),
            'tailwind_config' => $config->getTailwindConfig()
        ]);
    }
}
