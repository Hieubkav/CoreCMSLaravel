<?php

namespace App\Actions\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadFaviconAction
{
    /**
     * Upload favicon và sync với public/favicon.ico
     */
    public static function run(UploadedFile $file): array
    {
        try {
            // Validate file
            if (!in_array($file->getClientOriginalExtension(), ['ico', 'png', 'jpg', 'jpeg'])) {
                return [
                    'success' => false,
                    'message' => 'File phải có định dạng .ico, .png, .jpg hoặc .jpeg'
                ];
            }

            // Tạo tên file unique
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $directory = 'system/favicons';
            
            // Upload file vào storage
            $path = $file->storeAs($directory, $filename, 'public');
            
            if (!$path) {
                return [
                    'success' => false,
                    'message' => 'Không thể upload file'
                ];
            }

            // Copy file to public/favicon.ico để browser có thể tự động detect
            $storagePath = storage_path('app/public/' . $path);
            $publicFaviconPath = public_path('favicon.ico');
            
            // Nếu file không phải .ico, convert hoặc copy với tên .ico
            if ($file->getClientOriginalExtension() !== 'ico') {
                // Với PNG/JPG, chỉ copy và đổi tên
                File::copy($storagePath, $publicFaviconPath);
            } else {
                // File .ico thì copy trực tiếp
                File::copy($storagePath, $publicFaviconPath);
            }

            return [
                'success' => true,
                'message' => 'Upload favicon thành công!',
                'path' => $path,
                'url' => Storage::url($path),
                'public_favicon_updated' => true
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi upload favicon: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa favicon cũ và cập nhật public/favicon.ico
     */
    public static function deleteFavicon(string $path): bool
    {
        try {
            // Xóa file trong storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Xóa public/favicon.ico (hoặc reset về default)
            $publicFaviconPath = public_path('favicon.ico');
            if (File::exists($publicFaviconPath)) {
                File::delete($publicFaviconPath);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Tạo favicon mặc định từ logo hoặc text
     */
    public static function createDefaultFavicon(string $text = 'CF'): array
    {
        try {
            // Tạo favicon đơn giản từ text (sử dụng GD library nếu có)
            if (!extension_loaded('gd')) {
                return [
                    'success' => false,
                    'message' => 'GD library không được cài đặt'
                ];
            }

            // Tạo image 32x32 với text
            $image = imagecreate(32, 32);
            $backgroundColor = imagecolorallocate($image, 220, 38, 38); // red-600
            $textColor = imagecolorallocate($image, 255, 255, 255); // white
            
            // Thêm text vào center
            $fontSize = 3;
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $textHeight = imagefontheight($fontSize);
            $x = (32 - $textWidth) / 2;
            $y = (32 - $textHeight) / 2;
            
            imagestring($image, $fontSize, $x, $y, $text, $textColor);
            
            // Lưu vào storage
            $directory = 'system/favicons';
            $filename = 'default_favicon_' . time() . '.png';
            $storagePath = storage_path('app/public/' . $directory . '/' . $filename);
            
            // Tạo thư mục nếu chưa có
            if (!File::exists(dirname($storagePath))) {
                File::makeDirectory(dirname($storagePath), 0755, true);
            }
            
            imagepng($image, $storagePath);
            imagedestroy($image);
            
            // Copy to public/favicon.ico
            $publicFaviconPath = public_path('favicon.ico');
            File::copy($storagePath, $publicFaviconPath);
            
            $path = $directory . '/' . $filename;
            
            return [
                'success' => true,
                'message' => 'Tạo favicon mặc định thành công!',
                'path' => $path,
                'url' => Storage::url($path)
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo favicon: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy URL favicon hiện tại
     */
    public static function getCurrentFaviconUrl(): string
    {
        // Kiểm tra public/favicon.ico trước
        if (File::exists(public_path('favicon.ico'))) {
            return asset('favicon.ico');
        }

        // Fallback về default Laravel favicon
        return asset('favicon.ico');
    }
}
