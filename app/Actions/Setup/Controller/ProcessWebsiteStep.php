<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\SaveWebsiteSettings;
use Illuminate\Http\Request;

class ProcessWebsiteStep
{
    /**
     * Xử lý bước cấu hình website
     */
    public static function handle(Request $request): array
    {
        $data = $request->only([
            // Basic information
            'site_name', 'slogan', 'footer_description',

            // SEO information
            'seo_title', 'seo_description',

            // Contact information
            'email', 'hotline', 'address', 'working_hours',

            // Social media links
            'facebook_link', 'zalo_link', 'youtube_link', 'tiktok_link', 'messenger_link'
        ]);

        try {
            // Tự động sử dụng default images (không cần upload trong setup)
            $imageResults = [];

            // Sử dụng default logo
            $defaultLogoResult = UseDefaultAssets::useDefaultLogo();
            if ($defaultLogoResult['success']) {
                $data['logo_link'] = $defaultLogoResult['path'];
                $imageResults[] = 'Sử dụng default logo';
            }

            // Sử dụng default favicon
            $defaultFaviconResult = UseDefaultAssets::useDefaultFavicon();
            if ($defaultFaviconResult['success']) {
                $data['favicon_link'] = $defaultFaviconResult['path'];
                $imageResults[] = 'Sử dụng default favicon';
            }

            // Placeholder fallback về logo
            if (!empty($data['logo_link'])) {
                $data['placeholder_image'] = $data['logo_link'];
                $imageResults[] = 'Placeholder sử dụng logo';
            }

            $result = SaveWebsiteSettings::saveWithValidation($data);

            if ($result['success']) {
                $message = $result['message'];
                if (!empty($imageResults)) {
                    $message .= ' ' . implode(', ', $imageResults) . '.';
                }
                $result['message'] = $message;
                $result['images'] = $imageResults;

                return $result;
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }
}
