<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use App\Actions\Setup\CreateServiceModule;
use App\Models\SetupModule;
use Illuminate\Support\Facades\Log;

class ProcessServiceStep
{
    /**
     * Xử lý bước setup service
     */
    public static function handle(Request $request): array
    {
        try {
            // Validate input
            $validated = $request->validate([
                'enable_service' => 'required|boolean',
                'create_sample_data' => 'boolean',
                'service_title' => 'nullable|string|max:255',
                'service_description' => 'nullable|string|max:500',
            ]);

            // Nếu không enable service, chỉ lưu config và chuyển bước
            if (!$validated['enable_service']) {
                self::saveConfig([
                    'enable_service' => false,
                    'service_title' => $validated['service_title'] ?? '',
                    'service_description' => $validated['service_description'] ?? '',
                ]);

                return [
                    'success' => true,
                    'message' => 'Đã bỏ qua module dịch vụ',
                    'next_step' => self::getNextStep()
                ];
            }

            // Tạo service module
            $result = CreateServiceModule::handle([
                'create_sample_data' => $validated['create_sample_data'] ?? false,
                'service_title' => $validated['service_title'] ?? 'Dịch vụ của chúng tôi',
                'service_description' => $validated['service_description'] ?? 'Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao',
            ]);

            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? 'Unknown error'
                ];
            }

            // Lưu config
            self::saveConfig([
                'enable_service' => true,
                'create_sample_data' => $validated['create_sample_data'] ?? false,
                'service_title' => $validated['service_title'] ?? 'Dịch vụ của chúng tôi',
                'service_description' => $validated['service_description'] ?? 'Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao',
                'setup_completed_at' => now(),
                'files_created' => $result['data']['results'] ?? [],
            ]);

            // Cập nhật settings nếu có title/description
            if (!empty($validated['service_title']) || !empty($validated['service_description'])) {
                self::updateGlobalSettings($validated);
            }

            return [
                'success' => true,
                'message' => 'Tạo module dịch vụ thành công!',
                'data' => [
                    'config' => $validated,
                    'results' => $result['data']['results'] ?? [],
                    'sample_data_created' => $validated['create_sample_data'] ?? false,
                ],
                'next_step' => self::getNextStep()
            ];

        } catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            Log::error('Service setup error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo module dịch vụ: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lưu config vào database
     */
    private static function saveConfig(array $config): void
    {
        SetupModule::updateOrCreate(
            ['module_name' => 'service'],
            [
                'module_title' => 'Dịch vụ',
                'module_description' => 'Quản lý dịch vụ và portfolio',
                'is_enabled' => $config['enable_service'],
                'config' => $config,
                'completed_at' => $config['enable_service'] ? now() : null,
            ]
        );
    }

    /**
     * Cập nhật global settings
     */
    private static function updateGlobalSettings(array $validated): void
    {
        try {
            $settings = \App\Models\Setting::first();
            if ($settings) {
                if (!empty($validated['service_title'])) {
                    $settings->service_title = $validated['service_title'];
                }
                if (!empty($validated['service_description'])) {
                    $settings->service_description = $validated['service_description'];
                }
                $settings->save();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update global settings for service: ' . $e->getMessage());
        }
    }

    /**
     * Lấy bước tiếp theo
     */
    private static function getNextStep(): string
    {
        // Service là bước cuối cùng trong setup wizard hiện tại
        // Sau service sẽ chuyển đến complete
        return 'complete';
    }

    /**
     * Kiểm tra xem service module đã được setup chưa
     */
    public static function isCompleted(): bool
    {
        $module = SetupModule::where('module_name', 'service')->first();
        return $module && $module->completed_at;
    }

    /**
     * Lấy config đã lưu
     */
    public static function getConfig(): array
    {
        $module = SetupModule::where('module_name', 'service')->first();
        return $module ? $module->config : [
            'enable_service' => false,
            'create_sample_data' => false,
            'service_title' => 'Dịch vụ của chúng tôi',
            'service_description' => 'Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao',
        ];
    }
}
