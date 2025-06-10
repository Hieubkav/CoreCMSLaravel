<?php

namespace App\Actions;

use App\Models\AdminConfiguration;
use Illuminate\Support\Facades\Validator;

class SaveAdminConfiguration
{
    /**
     * Save admin configuration with full validation
     */
    public static function run(array $data): array
    {
        try {
            // Validate input data
            $validator = Validator::make($data, [
                'admin_primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'admin_secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                'visitor_analytics_enabled' => 'boolean',
                'query_cache' => 'boolean',
                'eager_loading' => 'boolean',
                'asset_optimization' => 'boolean',
                'cache_duration' => 'required|integer|min:60|max:3600',
                'pagination_size' => 'required|integer|in:10,25,50,100',
                'webp_quality' => 'required|integer|min:50|max:100',
                'max_width' => 'required|integer|min:800|max:4000',
                'max_height' => 'required|integer|min:600|max:3000',
                'seo_auto_generate' => 'boolean',
                'default_description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ];
            }

            // Create or update configuration
            $config = AdminConfiguration::updateOrCreateConfig($validator->validated());

            return [
                'success' => true,
                'message' => 'Cấu hình admin dashboard đã được lưu thành công!',
                'config' => $config
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu cấu hình: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Save admin configuration for setup wizard (simplified validation)
     */
    public static function saveForSetup(array $data): array
    {
        try {
            // Default values for setup
            $defaultConfig = [
                'admin_primary_color' => '#1f2937',
                'admin_secondary_color' => '#374151',
                'visitor_analytics_enabled' => false,
                'query_cache' => true,
                'eager_loading' => true,
                'asset_optimization' => true,
                'cache_duration' => 300,
                'pagination_size' => 25,
                'webp_quality' => 95,
                'max_width' => 1920,
                'max_height' => 1080,
                'seo_auto_generate' => true,
                'default_description' => 'Powered by Core Framework'
            ];

            // Merge with provided data
            $configData = array_merge($defaultConfig, $data);

            // Simple validation for setup
            if (isset($configData['admin_primary_color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $configData['admin_primary_color'])) {
                $configData['admin_primary_color'] = $defaultConfig['admin_primary_color'];
            }

            if (isset($configData['admin_secondary_color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $configData['admin_secondary_color'])) {
                $configData['admin_secondary_color'] = $defaultConfig['admin_secondary_color'];
            }

            // Ensure boolean values
            $booleanFields = ['visitor_analytics_enabled', 'query_cache', 'eager_loading', 'asset_optimization', 'seo_auto_generate'];
            foreach ($booleanFields as $field) {
                if (isset($configData[$field])) {
                    $configData[$field] = filter_var($configData[$field], FILTER_VALIDATE_BOOLEAN);
                }
            }

            // Ensure integer values with bounds
            $integerFields = [
                'cache_duration' => [60, 3600],
                'pagination_size' => [10, 100],
                'webp_quality' => [50, 100],
                'max_width' => [800, 4000],
                'max_height' => [600, 3000]
            ];

            foreach ($integerFields as $field => $bounds) {
                if (isset($configData[$field])) {
                    $value = (int) $configData[$field];
                    $configData[$field] = max($bounds[0], min($bounds[1], $value));
                }
            }

            // Create or update configuration
            $config = AdminConfiguration::updateOrCreateConfig($configData);

            return [
                'success' => true,
                'message' => 'Cấu hình admin dashboard đã được lưu thành công!',
                'config' => $config
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu cấu hình: ' . $e->getMessage()
            ];
        }
    }
}
