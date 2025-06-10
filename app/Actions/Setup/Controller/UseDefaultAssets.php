<?php

namespace App\Actions\Setup\Controller;

class UseDefaultAssets
{
    /**
     * Sử dụng default favicon từ public/images/default_logo.ico
     */
    public static function useDefaultFavicon(): array
    {
        try {
            $defaultFaviconPath = public_path('images/default_logo.ico');

            // Kiểm tra file default có tồn tại không
            if (!file_exists($defaultFaviconPath)) {
                return [
                    'success' => false,
                    'message' => 'File default_logo.ico không tồn tại trong public/images/'
                ];
            }

            // Tạo thư mục storage nếu chưa có
            $storagePath = storage_path('app/public/system/favicons');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Copy default favicon vào storage với tên unique
            $filename = 'default_favicon_' . time() . '.ico';
            $storageFilePath = $storagePath . '/' . $filename;
            copy($defaultFaviconPath, $storageFilePath);

            // Copy vào public/favicon.ico để browser detect
            $publicFaviconPath = public_path('favicon.ico');
            copy($defaultFaviconPath, $publicFaviconPath);

            return [
                'success' => true,
                'path' => 'system/favicons/' . $filename,
                'message' => 'Đã sử dụng default favicon và copy vào public/favicon.ico'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng default favicon: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sử dụng default logo từ public/images/default_logo.png
     */
    public static function useDefaultLogo(): array
    {
        try {
            $defaultLogoPath = public_path('images/default_logo.png');

            // Kiểm tra file default có tồn tại không
            if (!file_exists($defaultLogoPath)) {
                return [
                    'success' => false,
                    'message' => 'File default_logo.png không tồn tại trong public/images/'
                ];
            }

            // Tạo thư mục storage nếu chưa có
            $storagePath = storage_path('app/public/system/logos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Copy default logo vào storage với tên unique
            $filename = 'default_logo_' . time() . '.png';
            $storageFilePath = $storagePath . '/' . $filename;
            copy($defaultLogoPath, $storageFilePath);

            return [
                'success' => true,
                'path' => 'system/logos/' . $filename,
                'message' => 'Đã sử dụng default logo'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi sử dụng default logo: ' . $e->getMessage()
            ];
        }
    }
}
