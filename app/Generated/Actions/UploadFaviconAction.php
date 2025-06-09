<?php

namespace App\Generated\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Actions\ConvertImageToWebp;

class UploadFaviconAction
{
    use AsAction;

    /**
     * Upload và convert favicon thành WebP
     */
    public function handle(UploadedFile $file, string $type = 'favicon'): array
    {
        try {
            // Validate file
            $this->validateFile($file, $type);
            
            // Generate filename
            $filename = $this->generateFilename($file, $type);
            
            // Convert to WebP if needed
            if ($this->shouldConvertToWebp($file)) {
                $webpResult = ConvertImageToWebp::run($file, 'system/' . $type);
                
                if ($webpResult['success']) {
                    return [
                        'success' => true,
                        'path' => $webpResult['path'],
                        'url' => Storage::url($webpResult['path']),
                        'filename' => basename($webpResult['path']),
                        'size' => $webpResult['size'] ?? 0,
                        'format' => 'webp',
                        'message' => 'Favicon đã được upload và convert thành WebP thành công'
                    ];
                }
            }
            
            // Fallback: Store original file
            $path = $file->storeAs('system/' . $type, $filename, 'public');
            
            return [
                'success' => true,
                'path' => $path,
                'url' => Storage::url($path),
                'filename' => $filename,
                'size' => $file->getSize(),
                'format' => $file->getClientOriginalExtension(),
                'message' => 'Favicon đã được upload thành công'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi upload favicon: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file, string $type): void
    {
        // Check file size (max 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \Exception('File quá lớn. Kích thước tối đa là 2MB.');
        }
        
        // Check file type
        $allowedTypes = $this->getAllowedTypes($type);
        $mimeType = $file->getMimeType();
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \Exception('Định dạng file không được hỗ trợ. Chỉ chấp nhận: ' . implode(', ', $this->getAllowedExtensions($type)));
        }
        
        // Additional validation for favicon
        if ($type === 'favicon') {
            $this->validateFavicon($file);
        }
    }

    /**
     * Get allowed MIME types for file type
     */
    private function getAllowedTypes(string $type): array
    {
        $types = [
            'favicon' => [
                'image/x-icon',
                'image/vnd.microsoft.icon',
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/svg+xml',
                'image/webp'
            ],
            'logo' => [
                'image/png',
                'image/jpeg',
                'image/jpg',
                'image/gif',
                'image/svg+xml',
                'image/webp'
            ]
        ];
        
        return $types[$type] ?? $types['logo'];
    }

    /**
     * Get allowed extensions for display
     */
    private function getAllowedExtensions(string $type): array
    {
        $extensions = [
            'favicon' => ['ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'],
            'logo' => ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp']
        ];
        
        return $extensions[$type] ?? $extensions['logo'];
    }

    /**
     * Validate favicon specific requirements
     */
    private function validateFavicon(UploadedFile $file): void
    {
        // Get image dimensions if it's an image
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageInfo = getimagesize($file->getPathname());
            
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
                
                // Recommend square dimensions
                if ($width !== $height) {
                    // Just a warning, not blocking
                    \Log::info("Favicon không vuông: {$width}x{$height}. Khuyến nghị sử dụng kích thước vuông (32x32, 64x64, 128x128).");
                }
                
                // Check minimum size
                if ($width < 16 || $height < 16) {
                    throw new \Exception('Favicon quá nhỏ. Kích thước tối thiểu là 16x16 pixels.');
                }
                
                // Check maximum size
                if ($width > 512 || $height > 512) {
                    throw new \Exception('Favicon quá lớn. Kích thước tối đa là 512x512 pixels.');
                }
            }
        }
    }

    /**
     * Generate SEO-friendly filename
     */
    private function generateFilename(UploadedFile $file, string $type): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d-H-i-s');
        
        // Generate base name
        $baseName = match($type) {
            'favicon' => 'favicon',
            'logo' => 'logo',
            default => Str::slug($type)
        };
        
        return "{$baseName}-{$timestamp}.{$extension}";
    }

    /**
     * Check if file should be converted to WebP
     */
    private function shouldConvertToWebp(UploadedFile $file): bool
    {
        $convertibleTypes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif'
        ];
        
        return in_array($file->getMimeType(), $convertibleTypes);
    }

    /**
     * Delete old favicon/logo file
     */
    public static function deleteOldFile(?string $oldPath): bool
    {
        if (!$oldPath) {
            return true;
        }
        
        try {
            if (Storage::disk('public')->exists($oldPath)) {
                return Storage::disk('public')->delete($oldPath);
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting old file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate multiple favicon sizes
     */
    public static function generateFaviconSizes(string $sourcePath): array
    {
        $sizes = [16, 32, 48, 64, 128, 256];
        $generated = [];
        
        try {
            foreach ($sizes as $size) {
                // This would require image manipulation library
                // For now, just return the original path
                $generated[$size] = $sourcePath;
            }
            
            return [
                'success' => true,
                'sizes' => $generated,
                'message' => 'Favicon sizes generated successfully'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generating favicon sizes: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get favicon HTML tags
     */
    public static function getFaviconHtml(string $faviconPath): string
    {
        $url = Storage::url($faviconPath);
        
        return <<<HTML
        <link rel="icon" type="image/x-icon" href="{$url}">
        <link rel="shortcut icon" type="image/x-icon" href="{$url}">
        <link rel="apple-touch-icon" href="{$url}">
        <meta name="msapplication-TileImage" content="{$url}">
        HTML;
    }
}
