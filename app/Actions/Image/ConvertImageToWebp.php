<?php

namespace App\Actions\Image;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ConvertImageToWebp
{
    use AsAction;

    /**
     * Convert và lưu image thành WebP format
     */
    public function handle(UploadedFile $file, string $directory = 'images', ?string $customName = null): string
    {
        try {
            // Tạo tên file SEO-friendly
            $fileName = $customName ?: $this->generateSeoFriendlyName($file->getClientOriginalName());
            $webpFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
            
            // Đường dẫn đầy đủ
            $filePath = $directory . '/' . $webpFileName;
            
            // Convert image sang WebP
            $image = Image::make($file);
            
            // Optimize image
            $image->encode('webp', 85); // 85% quality for good balance
            
            // Lưu vào storage
            Storage::disk('public')->put($filePath, $image->stream());
            
            return $filePath;

        } catch (\Exception $e) {
            // Fallback: lưu file gốc nếu convert lỗi
            return $file->store($directory, 'public');
        }
    }

    /**
     * Tạo tên file SEO-friendly
     */
    private function generateSeoFriendlyName(string $originalName): string
    {
        // Loại bỏ extension
        $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Convert sang slug
        $slug = $this->createSlug($nameWithoutExt);
        
        // Thêm timestamp để tránh trùng lặp
        $timestamp = now()->format('YmdHis');
        
        return $slug . '_' . $timestamp;
    }

    /**
     * Tạo slug từ string
     */
    private function createSlug(string $string): string
    {
        // Convert Vietnamese characters
        $string = $this->removeVietnameseAccents($string);
        
        // Convert to lowercase and replace spaces/special chars with hyphens
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);
        $string = trim($string, '-');
        
        return $string;
    }

    /**
     * Loại bỏ dấu tiếng Việt
     */
    private function removeVietnameseAccents(string $string): string
    {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        ];

        $replacements = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y', 'Y', 'Y', 'Y',
            'D'
        ];

        return str_replace($accents, $replacements, $string);
    }

    /**
     * Convert multiple files
     */
    public static function convertMultiple(array $files, string $directory = 'images'): array
    {
        $results = [];
        
        foreach ($files as $key => $file) {
            if ($file instanceof UploadedFile) {
                $results[$key] = self::run($file, $directory);
            }
        }
        
        return $results;
    }

    /**
     * Convert với custom options
     */
    public static function convertWithOptions(UploadedFile $file, array $options = []): string
    {
        $directory = $options['directory'] ?? 'images';
        $customName = $options['name'] ?? null;
        $quality = $options['quality'] ?? 85;
        $maxWidth = $options['max_width'] ?? null;
        $maxHeight = $options['max_height'] ?? null;

        try {
            $fileName = $customName ?: (new self())->generateSeoFriendlyName($file->getClientOriginalName());
            $webpFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
            $filePath = $directory . '/' . $webpFileName;

            $image = Image::make($file);

            // Resize if needed
            if ($maxWidth || $maxHeight) {
                $image->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $image->encode('webp', $quality);
            Storage::disk('public')->put($filePath, $image->stream());

            return $filePath;

        } catch (\Exception $e) {
            return $file->store($directory, 'public');
        }
    }
}
