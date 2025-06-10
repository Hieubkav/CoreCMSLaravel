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
                // Theme Configuration
                'theme_mode' => 'string|in:light,dark,auto',
                'design_style' => 'string|in:minimalist,modern,classic',
                'icon_system' => 'string|in:fontawesome,heroicons,custom',
                
                // Color Scheme
                'primary_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
                'secondary_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
                'accent_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
                'background_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
                'text_color' => 'string|regex:/^#[0-9A-Fa-f]{6}$/',
                
                // Typography
                'font_family' => 'string|max:100',
                'font_size' => 'string|in:sm,base,lg',
                'font_weight' => 'string|in:light,normal,medium,semibold',
                
                // Layout Settings
                'container_width' => 'string|in:max-w-6xl,max-w-7xl,max-w-full',
                'enable_breadcrumbs' => 'boolean',
                'enable_back_to_top' => 'boolean',
                'enable_loading_spinner' => 'boolean',
                
                // Navigation
                'sticky_navbar' => 'boolean',
                'show_search_bar' => 'boolean',
                'show_language_switcher' => 'boolean',
                'menu_style' => 'string|in:horizontal,vertical,mega',
                
                // Footer
                'show_footer_social' => 'boolean',
                'show_footer_newsletter' => 'boolean',
                'footer_copyright' => 'nullable|string|max:255',
                
                // Performance & SEO
                'enable_lazy_loading' => 'boolean',
                'enable_image_optimization' => 'boolean',
                'enable_minification' => 'boolean',
                'enable_caching' => 'boolean',
                
                // Error Pages
                'error_pages' => 'nullable|array',
                'error_pages.*' => 'string|in:404,500,503',
                
                // Custom CSS/JS
                'custom_css' => 'nullable|string',
                'custom_js' => 'nullable|string',
                'custom_head_tags' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ];
            }

            // Tạo hoặc cập nhật cấu hình
            $config = FrontendConfiguration::updateOrCreateConfig($validator->validated());

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
}
