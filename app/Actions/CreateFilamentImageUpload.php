<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Filament\Forms\Components\FileUpload;

/**
 * Create Filament Image Upload Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Tạo FileUpload component với WebP conversion
 * Thay thế HasImageUpload trait phức tạp
 */
class CreateFilamentImageUpload
{
    use AsAction;

    /**
     * Tạo FileUpload component với WebP conversion tự động
     */
    public function handle(
        string $field,
        string $label,
        string $directory,
        int $maxWidth = 0,
        int $maxHeight = 0,
        int $maxSize = 5120,
        ?string $helperText = null,
        array $aspectRatios = ['16:9', '4:3', '1:1'],
        bool $required = false
    ): FileUpload {
        $upload = FileUpload::make($field)
            ->label($label)
            ->image()
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxSize)
            ->imageEditor()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']);

        // Helper text
        if ($helperText) {
            $upload->helperText($helperText);
        } else {
            $upload->helperText('Chọn ảnh định dạng JPG, PNG hoặc WebP. Kích thước tối đa: ' . ($maxSize/1024) . 'MB');
        }

        // Aspect ratios cho image editor
        if (!empty($aspectRatios)) {
            $upload->imageEditorAspectRatios($aspectRatios);
        }

        // Required
        if ($required) {
            $upload->required();
        }

        // WebP conversion với ConvertImageToWebp action
        $upload->saveUploadedFileUsing(function ($file, $get) use ($directory, $field, $maxWidth, $maxHeight) {
            // Lấy title để tạo SEO filename
            $title = $get('title') ?? $get('name') ?? $get('label') ?? 'image';
            
            // Tạo SEO filename
            $seoFileName = CreateSeoImageName::run($title, $field);
            
            // Convert sang WebP
            return ConvertImageToWebp::run(
                $file,
                $directory,
                $seoFileName,
                $maxWidth > 0 ? $maxWidth : null,
                $maxHeight > 0 ? $maxHeight : null
            );
        });

        return $upload;
    }

    /**
     * Tạo upload cho course thumbnail
     */
    public static function forCourse(bool $required = false): FileUpload
    {
        return static::run(
            field: 'thumbnail_link',
            label: 'Ảnh đại diện khóa học',
            directory: GetImageDirectory::forCourse(),
            maxWidth: 1200,
            maxHeight: 630,
            maxSize: 5120,
            helperText: 'Ảnh đại diện khóa học, tỷ lệ khuyến nghị 16:9. Tối đa 5MB.',
            aspectRatios: ['16:9'],
            required: $required
        );
    }

    /**
     * Tạo upload cho post thumbnail
     */
    public static function forPost(bool $required = false): FileUpload
    {
        return static::run(
            field: 'thumbnail_link',
            label: 'Ảnh đại diện bài viết',
            directory: GetImageDirectory::forPost(),
            maxWidth: 1200,
            maxHeight: 630,
            maxSize: 5120,
            helperText: 'Ảnh đại diện bài viết, tỷ lệ khuyến nghị 16:9. Tối đa 5MB.',
            aspectRatios: ['16:9'],
            required: $required
        );
    }

    /**
     * Tạo upload cho avatar
     */
    public static function forAvatar(string $field = 'avatar_link', bool $required = false): FileUpload
    {
        return static::run(
            field: $field,
            label: 'Ảnh đại diện',
            directory: GetImageDirectory::forAvatar(),
            maxWidth: 400,
            maxHeight: 400,
            maxSize: 2048,
            helperText: 'Ảnh đại diện, tỷ lệ khuyến nghị 1:1. Tối đa 2MB.',
            aspectRatios: ['1:1'],
            required: $required
        );
    }

    /**
     * Tạo upload cho slider
     */
    public static function forSlider(bool $required = false): FileUpload
    {
        return static::run(
            field: 'image_link',
            label: 'Ảnh slider',
            directory: GetImageDirectory::forSlider(),
            maxWidth: 1920,
            maxHeight: 800,
            maxSize: 8192,
            helperText: 'Ảnh slider trang chủ, tỷ lệ khuyến nghị 16:9. Tối đa 8MB.',
            aspectRatios: ['16:9', '21:9'],
            required: $required
        );
    }

    /**
     * Tạo upload cho partner logo
     */
    public static function forPartner(bool $required = false): FileUpload
    {
        return static::run(
            field: 'logo_link',
            label: 'Logo đối tác',
            directory: GetImageDirectory::forPartner(),
            maxWidth: 300,
            maxHeight: 200,
            maxSize: 1024,
            helperText: 'Logo đối tác, nền trong suốt khuyến nghị. Tối đa 1MB.',
            aspectRatios: ['3:2', '4:3', '1:1'],
            required: $required
        );
    }

    /**
     * Tạo upload cho instructor avatar
     */
    public static function forInstructor(bool $required = false): FileUpload
    {
        return static::run(
            field: 'avatar_link',
            label: 'Ảnh giảng viên',
            directory: GetImageDirectory::forInstructor(),
            maxWidth: 400,
            maxHeight: 400,
            maxSize: 2048,
            helperText: 'Ảnh đại diện giảng viên, tỷ lệ 1:1. Tối đa 2MB.',
            aspectRatios: ['1:1'],
            required: $required
        );
    }

    /**
     * Tạo upload tùy chỉnh
     */
    public static function custom(array $config): FileUpload
    {
        return static::run(
            field: $config['field'],
            label: $config['label'],
            directory: $config['directory'],
            maxWidth: $config['maxWidth'] ?? 0,
            maxHeight: $config['maxHeight'] ?? 0,
            maxSize: $config['maxSize'] ?? 5120,
            helperText: $config['helperText'] ?? null,
            aspectRatios: $config['aspectRatios'] ?? ['16:9', '4:3', '1:1'],
            required: $config['required'] ?? false
        );
    }
}
