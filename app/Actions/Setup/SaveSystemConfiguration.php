<?php

namespace App\Actions\Setup;

use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;

class SaveSystemConfiguration
{
    /**
     * Lưu cấu hình hệ thống với validation
     */
    public static function run(array $data): array
    {
        try {
            // Validation rules
            $validator = Validator::make($data, [
                'theme_mode' => 'required|in:light_only,dark_only,both,none',
                'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'accent_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'primary_font' => 'required|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'secondary_font' => 'required|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'tertiary_font' => 'required|in:Inter,Roboto,Open Sans,Poppins,Nunito',
                'design_style' => 'required|in:minimalism,glassmorphism,modern,classic',
                'icon_system' => 'required|in:heroicons,fontawesome',
                'admin_primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'admin_secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
                'visitor_analytics_enabled' => 'boolean',
                'error_pages' => 'array',
                'error_pages.*' => 'in:404,500,maintenance,offline',
                'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:2048'
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()->toArray()
                ];
            }

            // Lấy hoặc tạo cấu hình
            $config = SystemConfiguration::current();
            if (!$config) {
                $config = new SystemConfiguration();
            }

            // Xử lý upload favicon nếu có
            $faviconPath = $config->favicon_path;
            if (isset($data['favicon']) && $data['favicon'] instanceof UploadedFile) {
                $faviconResult = UploadFaviconAction::run($data['favicon']);
                if ($faviconResult['success']) {
                    $faviconPath = $faviconResult['path'];
                } else {
                    return [
                        'success' => false,
                        'message' => $faviconResult['message']
                    ];
                }
            }

            // Chuẩn bị dữ liệu để lưu
            $configData = [
                'theme_mode' => $data['theme_mode'],
                'primary_color' => $data['primary_color'],
                'secondary_color' => $data['secondary_color'],
                'accent_color' => $data['accent_color'],
                'primary_font' => $data['primary_font'],
                'secondary_font' => $data['secondary_font'],
                'tertiary_font' => $data['tertiary_font'],
                'design_style' => $data['design_style'],
                'icon_system' => $data['icon_system'],
                'admin_primary_color' => $data['admin_primary_color'],
                'admin_secondary_color' => $data['admin_secondary_color'],
                'visitor_analytics_enabled' => $data['visitor_analytics_enabled'] ?? false,
                'error_pages' => $data['error_pages'] ?? [],
                'favicon_path' => $faviconPath,
                'status' => 'active',
                'order' => 0
            ];

            // Lưu cấu hình
            if ($config->exists) {
                $config->update($configData);
            } else {
                $config = SystemConfiguration::create($configData);
            }

            return [
                'success' => true,
                'message' => 'Cấu hình hệ thống đã được lưu thành công!',
                'config' => $config,
                'css_variables' => $config->getCssVariables(),
                'tailwind_config' => $config->getTailwindConfig()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lưu cấu hình với validation đơn giản (cho setup wizard)
     */
    public static function saveForSetup(array $data): array
    {
        try {
            // Tạo cấu hình mặc định với một số tùy chỉnh từ setup
            $defaultConfig = [
                'theme_mode' => $data['theme_mode'] ?? 'light_only',
                'primary_color' => $data['primary_color'] ?? '#dc2626',
                'secondary_color' => '#ffffff',
                'accent_color' => '#f3f4f6',
                'primary_font' => $data['primary_font'] ?? 'Inter',
                'secondary_font' => 'Inter',
                'tertiary_font' => 'Inter',
                'design_style' => $data['design_style'] ?? 'minimalism',
                'icon_system' => 'fontawesome',
                'admin_primary_color' => $data['primary_color'] ?? '#dc2626',
                'admin_secondary_color' => '#374151',
                'visitor_analytics_enabled' => $data['visitor_analytics_enabled'] ?? false,
                'error_pages' => ['404', '500'],
                'status' => 'active',
                'order' => 0
            ];

            // Tạo favicon mặc định nếu chưa có
            if (!isset($data['favicon_path'])) {
                try {
                    // Ưu tiên sử dụng default_logo.ico từ public/images/
                    $defaultFaviconPath = public_path('images/default_logo.ico');
                    if (file_exists($defaultFaviconPath)) {
                        // Copy default favicon vào storage
                        $storagePath = storage_path('app/public/system/favicons');
                        if (!file_exists($storagePath)) {
                            mkdir($storagePath, 0755, true);
                        }

                        $filename = 'default_favicon_' . time() . '.ico';
                        $storageFilePath = $storagePath . '/' . $filename;
                        copy($defaultFaviconPath, $storageFilePath);

                        // Copy vào public/favicon.ico
                        copy($defaultFaviconPath, public_path('favicon.ico'));

                        $defaultConfig['favicon_path'] = 'system/favicons/' . $filename;
                    } else {
                        // Fallback: tạo favicon từ text nếu không có default file
                        $faviconResult = UploadFaviconAction::createDefaultFavicon('CF');
                        if ($faviconResult['success']) {
                            $defaultConfig['favicon_path'] = $faviconResult['path'];
                        }
                    }
                } catch (\Exception $e) {
                    // Bỏ qua lỗi favicon trong setup wizard
                    $defaultConfig['favicon_path'] = null;
                }
            }

            $config = SystemConfiguration::create($defaultConfig);

            return [
                'success' => true,
                'message' => 'Cấu hình hệ thống đã được khởi tạo!',
                'config' => $config
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi khởi tạo cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reset về cấu hình mặc định
     */
    public static function resetToDefault(): array
    {
        try {
            // Xóa tất cả cấu hình hiện tại
            SystemConfiguration::truncate();

            // Tạo cấu hình mặc định mới
            $result = static::saveForSetup([]);

            return [
                'success' => true,
                'message' => 'Đã reset về cấu hình mặc định!',
                'config' => $result['config'] ?? null
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi reset cấu hình: ' . $e->getMessage()
            ];
        }
    }
}
