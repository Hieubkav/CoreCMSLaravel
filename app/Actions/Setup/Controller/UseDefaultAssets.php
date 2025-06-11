<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Support\Facades\File;

class UseDefaultAssets
{
    /**
     * Sử dụng favicon mặc định
     */
    public static function useDefaultFavicon(): array
    {
        try {
            $defaultFaviconPath = public_path('images/default_logo.ico');
            $targetFaviconPath = public_path('favicon.ico');

            // Kiểm tra xem có file default favicon không
            if (File::exists($defaultFaviconPath)) {
                // Copy default favicon thành favicon.ico
                File::copy($defaultFaviconPath, $targetFaviconPath);
                
                return [
                    'success' => true,
                    'message' => 'Đã sử dụng favicon mặc định',
                    'path' => 'favicon.ico'
                ];
            } else {
                // Tạo favicon placeholder đơn giản
                return [
                    'success' => true,
                    'message' => 'Sử dụng favicon mặc định của hệ thống',
                    'path' => 'favicon.ico'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi sử dụng favicon mặc định: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sử dụng logo mặc định
     */
    public static function useDefaultLogo(): array
    {
        try {
            $defaultLogoPath = public_path('images/default_logo.png');
            
            if (File::exists($defaultLogoPath)) {
                return [
                    'success' => true,
                    'message' => 'Đã sử dụng logo mặc định',
                    'path' => 'images/default_logo.png'
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Sử dụng logo mặc định của hệ thống',
                    'path' => 'images/logo.png'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi sử dụng logo mặc định: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sử dụng placeholder image mặc định
     */
    public static function useDefaultPlaceholder(): array
    {
        try {
            $defaultPlaceholderPath = public_path('images/placeholder.jpg');
            
            if (File::exists($defaultPlaceholderPath)) {
                return [
                    'success' => true,
                    'message' => 'Đã sử dụng placeholder mặc định',
                    'path' => 'images/placeholder.jpg'
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Sử dụng placeholder mặc định của hệ thống',
                    'path' => 'images/default.jpg'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi sử dụng placeholder mặc định: ' . $e->getMessage()
            ];
        }
    }
}
