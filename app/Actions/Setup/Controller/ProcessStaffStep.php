<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use App\Actions\Setup\CreateStaffModule;
use App\Models\SetupModule;
use Illuminate\Support\Facades\Log;

class ProcessStaffStep
{
    /**
     * Xử lý bước setup staff
     */
    public static function handle(Request $request): array
    {
        try {
            // Kiểm tra nếu user chọn bỏ qua
            if ($request->has('skip_staff') && $request->boolean('skip_staff')) {
                return [
                    'success' => true,
                    'message' => 'Đã bỏ qua cấu hình staff.',
                    'next_step' => 'service'
                ];
            }

            // Validate input
            $validated = $request->validate([
                'enable_staff' => 'required|boolean',
                'create_sample_data' => 'boolean',
                'staff_title' => 'required_if:enable_staff,true|string|max:255',
                'staff_description' => 'nullable|string|max:500',
                'enable_positions' => 'boolean',
                'enable_contact_info' => 'boolean',
                'enable_social_links' => 'boolean',
                'staff_per_page' => 'integer|min:1|max:50',
            ]);

            if (!$validated['enable_staff']) {
                self::saveConfig([
                    'enable_staff' => false,
                    'staff_title' => $validated['staff_title'] ?? '',
                    'staff_description' => $validated['staff_description'] ?? '',
                ]);

                return [
                    'success' => true,
                    'message' => 'Đã bỏ qua module nhân viên',
                    'next_step' => 'service'
                ];
            }

            // Tạo staff module
            $result = CreateStaffModule::handle($validated);

            if (!$result['success']) {
                return [
                    'success' => false,
                    'message' => $result['message'],
                    'error' => $result['error'] ?? 'Unknown error'
                ];
            }

            // Lưu config
            self::saveConfig([
                'enable_staff' => true,
                'create_sample_data' => $validated['create_sample_data'] ?? false,
                'staff_title' => $validated['staff_title'] ?? 'Đội ngũ chuyên gia',
                'staff_description' => $validated['staff_description'] ?? 'Gặp gỡ những chuyên gia tài năng của chúng tôi',
                'enable_positions' => $validated['enable_positions'] ?? true,
                'enable_contact_info' => $validated['enable_contact_info'] ?? true,
                'enable_social_links' => $validated['enable_social_links'] ?? true,
                'staff_per_page' => $validated['staff_per_page'] ?? 12,
                'setup_completed_at' => now(),
                'files_created' => $result['data']['results'] ?? [],
            ]);

            // Cập nhật settings nếu có title/description
            if (!empty($validated['staff_title']) || !empty($validated['staff_description'])) {
                self::updateGlobalSettings($validated);
            }

            return [
                'success' => true,
                'message' => 'Tạo module nhân viên thành công!',
                'data' => [
                    'config' => $validated,
                    'results' => $result['data']['results'] ?? [],
                    'sample_data_created' => $validated['create_sample_data'] ?? false,
                ],
                'next_step' => 'service'
            ];

        } catch (\Illuminate\Validation\ValidationException $e) {
            return [
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            Log::error('Staff setup error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo module nhân viên: ' . $e->getMessage(),
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
            ['module_name' => 'staff'],
            [
                'module_title' => 'Nhân viên',
                'module_description' => 'Quản lý thông tin nhân viên và đội ngũ',
                'is_enabled' => $config['enable_staff'],
                'config' => $config,
                'completed_at' => $config['enable_staff'] ? now() : null,
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
                if (!empty($validated['staff_title'])) {
                    $settings->staff_title = $validated['staff_title'];
                }
                if (!empty($validated['staff_description'])) {
                    $settings->staff_description = $validated['staff_description'];
                }
                $settings->save();
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update global settings for staff: ' . $e->getMessage());
        }
    }

    /**
     * Kiểm tra xem staff module đã được setup chưa
     */
    public static function isCompleted(): bool
    {
        $module = SetupModule::where('module_name', 'staff')->first();
        return $module && $module->completed_at;
    }

    /**
     * Lấy config đã lưu
     */
    public static function getConfig(): array
    {
        $module = SetupModule::where('module_name', 'staff')->first();
        return $module ? $module->config : [
            'enable_staff' => false,
            'create_sample_data' => false,
            'staff_title' => 'Đội ngũ chuyên gia',
            'staff_description' => 'Gặp gỡ những chuyên gia tài năng của chúng tôi',
            'enable_positions' => true,
            'enable_contact_info' => true,
            'enable_social_links' => true,
            'staff_per_page' => 12,
        ];
    }
}
