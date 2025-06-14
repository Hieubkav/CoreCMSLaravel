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
     * Lấy danh sách các bước setup theo đúng thứ tự flow
     */
    public static function getSetupSteps(): array
    {
        return [
            // Core Setup Steps (Bước 1-5: Bắt buộc)
            'database' => [
                'title' => 'Bước 1: Cấu hình Database',
                'description' => 'Kiểm tra kết nối và tạo bảng database',
                'group' => 'core',
                'step' => 1,
                'next_step' => 'admin'
            ],
            'admin' => [
                'title' => 'Bước 2: Tạo tài khoản Admin',
                'description' => 'Tạo tài khoản quản trị viên đầu tiên',
                'group' => 'core',
                'step' => 2,
                'next_step' => 'website'
            ],
            'website' => [
                'title' => 'Bước 3: Cấu hình Website',
                'description' => 'Thiết lập thông tin cơ bản của website',
                'group' => 'core',
                'step' => 3,
                'next_step' => 'frontend-config'
            ],
            'frontend-config' => [
                'title' => 'Bước 4: Cấu hình Frontend',
                'description' => 'Theme, màu sắc, font chữ cho giao diện người dùng',
                'group' => 'core',
                'step' => 4,
                'next_step' => 'admin-config'
            ],
            'admin-config' => [
                'title' => 'Bước 5: Cấu hình Admin Dashboard',
                'description' => 'Tùy chỉnh giao diện và tính năng admin panel',
                'group' => 'core',
                'step' => 5,
                'next_step' => 'blog'
            ],

            // Optional Modules (Bước 6-8: Tùy chọn)
            'blog' => [
                'title' => 'Bước 6: Cấu hình Blog/Bài viết',
                'description' => 'Thiết lập hệ thống blog, bài viết và danh mục (tùy chọn)',
                'group' => 'modules',
                'step' => 6,
                'next_step' => 'staff'
            ],
            'staff' => [
                'title' => 'Bước 7: Cấu hình Đội ngũ nhân viên',
                'description' => 'Thiết lập hệ thống quản lý và hiển thị nhân viên (tùy chọn)',
                'group' => 'modules',
                'step' => 7,
                'next_step' => 'service'
            ],
            'service' => [
                'title' => 'Bước 8: Cấu hình Dịch vụ',
                'description' => 'Thiết lập hệ thống quản lý dịch vụ và portfolio (tùy chọn)',
                'group' => 'modules',
                'step' => 8,
                'next_step' => 'complete'
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
            'groupedSteps' => $groupedSteps,
            'nextStep' => self::getNextStep($currentStep),
            'previousStep' => self::getPreviousStep($currentStep)
        ];
    }

    /**
     * Lấy bước tiếp theo theo đúng flow
     */
    public static function getNextStep(string $currentStep): ?string
    {
        $steps = self::getSetupSteps();

        if (!isset($steps[$currentStep])) {
            return null;
        }

        return $steps[$currentStep]['next_step'] ?? null;
    }

    /**
     * Lấy bước trước đó theo đúng flow
     */
    public static function getPreviousStep(string $currentStep): ?string
    {
        $steps = self::getSetupSteps();
        $currentStepNumber = $steps[$currentStep]['step'] ?? 0;

        if ($currentStepNumber <= 1) {
            return null;
        }

        // Tìm step có số thứ tự nhỏ hơn 1
        foreach ($steps as $stepKey => $stepData) {
            if ($stepData['step'] === $currentStepNumber - 1) {
                return $stepKey;
            }
        }

        return null;
    }

    /**
     * Kiểm tra xem có phải bước cuối cùng không
     */
    public static function isLastStep(string $currentStep): bool
    {
        return self::getNextStep($currentStep) === 'complete' || self::getNextStep($currentStep) === null;
    }

    /**
     * Lấy progress percentage
     */
    public static function getProgressPercentage(string $currentStep): int
    {
        $steps = self::getSetupSteps();
        $currentStepNumber = $steps[$currentStep]['step'] ?? 0;
        $totalSteps = count($steps);

        if ($totalSteps === 0) {
            return 0;
        }

        return (int) round(($currentStepNumber / $totalSteps) * 100);
    }
}
