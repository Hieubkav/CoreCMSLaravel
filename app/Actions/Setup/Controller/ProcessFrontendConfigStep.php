<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\SaveFrontendConfiguration;
use App\Actions\Setup\Controller\UseDefaultAssets;
use Illuminate\Http\Request;

class ProcessFrontendConfigStep
{
    /**
     * Xử lý bước cấu hình frontend
     */
    public static function handle(Request $request): array
    {
        $data = $request->all();

        try {
            // Bảng frontend_configurations đã được tạo sẵn trong bước database
            // Không cần tạo bảng nữa, chỉ cần lưu dữ liệu

            // Bước 1: Tự động sử dụng default favicon (không cần upload trong setup)
            $defaultFaviconResult = UseDefaultAssets::useDefaultFavicon();
            if ($defaultFaviconResult['success']) {
                $data['favicon_path'] = $defaultFaviconResult['path'];
            }

            // Bước 2: Xử lý error_pages nếu là JSON string
            if (isset($data['error_pages']) && is_string($data['error_pages'])) {
                $data['error_pages'] = json_decode($data['error_pages'], true);
            }

            // Bước 3: Lưu cấu hình vào bảng frontend_configurations
            $result = SaveFrontendConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['frontend_config' => $data]);

                // Sinh code cho Filament page để quản lý frontend configuration
                $generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('frontend-config');

                $message = 'Đã lưu cấu hình frontend vào bảng frontend_configurations thành công!';
                if (isset($defaultFaviconResult) && $defaultFaviconResult['success']) {
                    $message .= ' Favicon mặc định đã được sử dụng.';
                }

                if ($generateResult['success']) {
                    $message .= ' Đã tạo Filament Frontend Configuration page để quản lý.';
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'config_id' => $result['config']->id ?? null,
                    'table_created' => true,
                    'favicon_uploaded' => isset($defaultFaviconResult) && $defaultFaviconResult['success'],
                    'generate_result' => $generateResult
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình frontend'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }
}
