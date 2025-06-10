<?php

namespace App\Actions\File;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;

class CreateSeoImageName
{
    use AsAction;

    /**
     * Tạo tên file SEO-friendly cho ảnh
     */
    public function handle(string $title, string $field = 'image', string $suffix = ''): string
    {
        // Clean title
        $cleanTitle = $this->cleanTitle($title);
        
        // Create base name
        $baseName = Str::slug($cleanTitle, '-');
        
        // Add field prefix if not 'image'
        if ($field !== 'image') {
            $baseName = Str::slug($field, '-') . '-' . $baseName;
        }
        
        // Add suffix if provided
        if (!empty($suffix)) {
            $baseName .= '-' . Str::slug($suffix, '-');
        }
        
        // Add timestamp to ensure uniqueness
        $timestamp = now()->format('YmdHis');
        
        return $baseName . '-' . $timestamp;
    }

    /**
     * Clean title để tạo SEO name
     */
    private function cleanTitle(string $title): string
    {
        // Remove HTML tags
        $title = strip_tags($title);
        
        // Convert Vietnamese characters
        $title = $this->removeVietnameseAccents($title);
        
        // Remove special characters except spaces and hyphens
        $title = preg_replace('/[^a-zA-Z0-9\s\-]/', '', $title);
        
        // Limit length
        $title = Str::limit($title, 50, '');
        
        return trim($title);
    }

    /**
     * Remove Vietnamese accents
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
     * Tạo tên cho thumbnail
     */
    public static function forThumbnail(string $title): string
    {
        return static::run($title, 'thumbnail');
    }

    /**
     * Tạo tên cho avatar
     */
    public static function forAvatar(string $title): string
    {
        return static::run($title, 'avatar');
    }

    /**
     * Tạo tên cho slider
     */
    public static function forSlider(string $title): string
    {
        return static::run($title, 'slider');
    }

    /**
     * Tạo tên cho gallery
     */
    public static function forGallery(string $title): string
    {
        return static::run($title, 'gallery');
    }

    /**
     * Tạo tên cho logo
     */
    public static function forLogo(string $title): string
    {
        return static::run($title, 'logo');
    }
}
