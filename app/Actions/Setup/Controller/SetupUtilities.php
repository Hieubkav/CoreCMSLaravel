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
     * Lấy danh sách các bước setup
     */
    public static function getSetupSteps(): array
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
