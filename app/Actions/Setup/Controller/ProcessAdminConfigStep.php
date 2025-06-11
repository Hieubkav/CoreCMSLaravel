<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\SaveAdminConfiguration;
use Illuminate\Http\Request;

class ProcessAdminConfigStep
{
    /**
     * Xử lý bước cấu hình admin
     */
    public static function handle(Request $request): array
    {
        $data = $request->all();

        try {
            // Bảng admin_configurations đã được tạo sẵn trong bước database
            // Chỉ cần lưu dữ liệu vào bảng

            // Lưu cấu hình vào database
            $result = SaveAdminConfiguration::saveForSetup($data);

            if ($result['success']) {
                // Vẫn lưu vào session để backup
                session(['admin_config' => $data]);

                // Sinh code cho Filament page và widgets để quản lý admin configuration
                $generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('admin-config');

                $message = 'Đã lưu cấu hình admin dashboard vào bảng admin_configurations thành công!';
                if ($generateResult['success']) {
                    $message .= ' Đã tạo Filament Admin Configuration page và Analytics Widget để quản lý.';
                }

                // Kích hoạt visitor tracking nếu analytics được bật
                $trackingResult = null;
                if (isset($data['visitor_analytics_enabled']) && $data['visitor_analytics_enabled']) {
                    if (class_exists('App\\Actions\\Setup\\EnableVisitorTracking')) {
                        $trackingResult = \App\Actions\Setup\EnableVisitorTracking::enable();
                        if ($trackingResult['success']) {
                            $message .= ' Visitor tracking realtime đã được kích hoạt.';
                        }
                    }
                }

                return [
                    'success' => true,
                    'message' => $message,
                    'config_id' => $result['config']->id ?? null,
                    'note' => 'Bảng admin_configurations đã được tạo sẵn trong bước database',
                    'generate_result' => $generateResult,
                    'tracking_result' => $trackingResult
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi lưu cấu hình admin'
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
