<?php

namespace App\Actions\Setup\Controller;

use App\Models\Setting;
use Exception;

class SetupUtilities
{
    /**
     * Kiểm tra xem đã setup chưa
     */
    public static function isSetupCompleted(): bool
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
     * Lấy danh sách các bước setup (chỉ 5 bước chính)
     */
    public static function getSetupSteps(): array
    {
        return [
            // Core Setup Steps (5 bước chính)
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
            'frontend-config' => [
                'title' => 'Cấu hình Frontend',
                'description' => 'Theme, màu sắc, font chữ cho giao diện người dùng',
                'group' => 'core',
                'step' => 4
            ],
            'admin-config' => [
                'title' => 'Cấu hình Admin Dashboard',
                'description' => 'Tùy chỉnh giao diện và tính năng admin panel',
                'group' => 'core',
                'step' => 5
            ],
            'blog' => [
                'title' => 'Cấu hình Blog/Bài viết',
                'description' => 'Thiết lập hệ thống blog, bài viết và danh mục (tùy chọn)',
                'group' => 'optional',
                'step' => 6
            ]
        ];
    }

    /**
     * Tính toán step numbers và grouping
     */
    public static function calculateStepData(string $currentStep): array
    {
        $steps = self::getSetupSteps();
        
        if (!isset($steps[$currentStep])) {
            return [];
        }

        // Calculate step numbers and grouping
        $currentStepNumber = $steps[$currentStep]['step'];
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

        return [
            'step' => $currentStep,
            'stepData' => $steps[$currentStep],
            'allSteps' => $steps,
            'currentStepKey' => $currentStep,
            'currentStepNumber' => $currentStepNumber,
            'totalSteps' => $totalSteps,
            'groupedSteps' => $groupedSteps
        ];
    }
}
