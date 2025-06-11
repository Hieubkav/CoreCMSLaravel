<?php

namespace App\Actions\Setup;

use App\Models\FrontendConfiguration;
use Illuminate\Support\Facades\Validator;
use Exception;

class SaveFrontendConfiguration
{
    /**
     * Lưu cấu hình frontend cho setup wizard
     */
    public static function saveForSetup(array $data): array
    {
        try {
            // Validate dữ liệu
            $validator = Validator::make($data, [
                // Theme Configuration - Khớp với form frontend
                'theme_mode' => 'nullable|string|in:light_only,dark_only,both,none',
                'design_style' => 'nullable|string|in:minimalism,glassmorphism,modern,classic',
                'icon_system' => 'nullable|string|in:fontawesome,heroicons',

                // Color Scheme - Cho phép nullable
                'primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'accent_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'background_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',

                // Typography - Thêm các font fields từ form
                'primary_font' => 'nullable|string|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'secondary_font' => 'nullable|string|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'tertiary_font' => 'nullable|string|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'font_family' => 'nullable|string|max:100',
                'font_size' => 'nullable|string|in:sm,base,lg',
                'font_weight' => 'nullable|string|in:light,normal,medium,semibold',

                // Layout Settings
                'container_width' => 'nullable|string|in:max-w-4xl,max-w-5xl,max-w-6xl,max-w-7xl,max-w-9xl,max-w-full',
                'enable_breadcrumbs' => 'nullable|boolean',
                'enable_back_to_top' => 'nullable|boolean',
                'enable_loading_spinner' => 'nullable|boolean',

                // Navigation
                'sticky_navbar' => 'nullable|boolean',
                'show_search_bar' => 'nullable|boolean',
                'show_language_switcher' => 'nullable|boolean',
                'menu_style' => 'nullable|string|in:horizontal,vertical,mega',

                // Footer
                'show_footer_social' => 'nullable|boolean',
                'show_footer_newsletter' => 'nullable|boolean',
                'footer_copyright' => 'nullable|string|max:255',

                // Performance & SEO
                'enable_lazy_loading' => 'nullable|boolean',
                'enable_image_optimization' => 'nullable|boolean',
                'enable_minification' => 'nullable|boolean',
                'enable_caching' => 'nullable|boolean',

                // Error Pages - Khớp với form frontend
                'error_pages' => 'nullable|array',
                'error_pages.*' => 'string|in:404,500,503,maintenance,offline',

                // Custom CSS/JS
                'custom_css' => 'nullable|string',
                'custom_js' => 'nullable|string',
                'custom_head_tags' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray(),
                    'debug_data' => $data // Thêm debug data để xem dữ liệu gửi lên
                ];
            }

            // Chuyển đổi dữ liệu từ form sang format database
            $validatedData = $validator->validated();
            $processedData = self::processFormData($validatedData);

            // Tạo hoặc cập nhật cấu hình
            $config = FrontendConfiguration::updateOrCreateConfig($processedData);

            return [
                'success' => true,
                'message' => 'Cấu hình frontend đã được lưu thành công!',
                'config' => $config
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lưu cấu hình frontend từ admin panel
     */
    public static function saveFromAdmin(array $data, ?string $userId = null): array
    {
        $data['updated_by'] = $userId;
        return self::saveForSetup($data);
    }

    /**
     * Lấy cấu hình frontend hiện tại
     */
    public static function getCurrentConfig(): ?FrontendConfiguration
    {
        return FrontendConfiguration::getActiveConfig();
    }

    /**
     * Reset về cấu hình mặc định
     */
    public static function resetToDefault(): array
    {
        try {
            $defaultData = (array) FrontendConfiguration::getDefaultConfig();
            return self::saveForSetup($defaultData);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi reset cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Chuyển đổi dữ liệu từ form sang format database
     */
    private static function processFormData(array $data): array
    {
        // Mapping theme_mode từ form sang database
        $themeModeMapping = [
            'light_only' => 'light',
            'dark_only' => 'dark',
            'both' => 'auto',
            'none' => 'light' // fallback
        ];

        // Mapping design_style từ form sang database
        $designStyleMapping = [
            'minimalism' => 'minimalist',
            'glassmorphism' => 'modern', // map glassmorphism to modern
            'modern' => 'modern',
            'classic' => 'classic'
        ];

        // Chuyển đổi theme_mode
        if (isset($data['theme_mode']) && isset($themeModeMapping[$data['theme_mode']])) {
            $data['theme_mode'] = $themeModeMapping[$data['theme_mode']];
        }

        // Chuyển đổi design_style
        if (isset($data['design_style']) && isset($designStyleMapping[$data['design_style']])) {
            $data['design_style'] = $designStyleMapping[$data['design_style']];
        }

        // Xử lý font_family từ primary_font
        if (isset($data['primary_font']) && !isset($data['font_family'])) {
            $data['font_family'] = $data['primary_font'];
        }

        // Set default values nếu không có
        $defaults = [
            'theme_mode' => 'light',
            'design_style' => 'minimalist',
            'icon_system' => 'fontawesome',
            'primary_color' => '#dc2626',
            'secondary_color' => '#f97316',
            'accent_color' => '#059669',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'font_family' => 'Inter',
            'font_size' => 'base',
            'font_weight' => 'normal',
            'container_width' => 'max-w-7xl',
            'enable_breadcrumbs' => true,
            'enable_back_to_top' => true,
            'enable_loading_spinner' => true,
            'sticky_navbar' => true,
            'show_search_bar' => true,
            'show_language_switcher' => false,
            'menu_style' => 'horizontal',
            'show_footer_social' => false,
            'show_footer_newsletter' => false,
            'enable_lazy_loading' => true,
            'enable_image_optimization' => true,
            'enable_minification' => true,
            'enable_caching' => true,
        ];

        // Merge với defaults
        $data = array_merge($defaults, array_filter($data, function($value) {
            return $value !== null && $value !== '';
        }));

        return $data;
    }
}
