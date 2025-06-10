<?php

namespace App\Actions\File;

use Lorisleiva\Actions\Concerns\AsAction;
use Filament\Forms\Components\FileUpload;

class CreateFilamentImageUpload
{
    use AsAction;

    /**
     * Tạo Filament FileUpload component với WebP conversion
     */
    public function handle(
        string $field,
        string $label,
        string $directory = 'images',
        int $maxWidth = 1200,
        int $maxHeight = 800,
        int $maxSize = 5120, // KB
        bool $required = false,
        ?string $helperText = null,
        array $aspectRatios = []
    ): FileUpload {
        $upload = FileUpload::make($field)
            ->label($label)
            ->image()
            ->imageEditor()
            ->maxSize($maxSize)
            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])
            ->directory($directory)
            ->visibility('public')
            ->disk('public');

        // Helper text
        if ($helperText) {
            $upload->helperText($helperText);
        }

        // Image editor với max dimensions
        if ($maxWidth > 0 || $maxHeight > 0) {
            $upload->imageEditorViewportWidth($maxWidth)
                   ->imageEditorViewportHeight($maxHeight);
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
            $seoFileName = \App\Actions\File\CreateSeoImageName::run($title, $field);
            
            // Convert sang WebP
            return \App\Actions\Image\ConvertImageToWebp::run(
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
    public static function forCourse(string $field = 'thumbnail_link'): FileUpload
    {
        return static::run(
            field: 'thumbnail_link',
            label: 'Ảnh đại diện khóa học',
            directory: \App\Actions\File\GetImageDirectory::forCourse(),
            maxWidth: 1200,
            maxHeight: 630,
            maxSize: 5120,
            helperText: 'Ảnh đại diện khóa học, tỷ lệ 16:9 khuyến nghị. Tối đa 5MB.',
            aspectRatios: ['16:9', '4:3', '1:1']
        );
    }

    /**
     * Tạo upload cho post thumbnail
     */
    public static function forPost(string $field = 'thumbnail_link'): FileUpload
    {
        return static::run(
            field: 'thumbnail_link',
            label: 'Ảnh đại diện bài viết',
            directory: \App\Actions\File\GetImageDirectory::forPost(),
            maxWidth: 1200,
            maxHeight: 630,
            maxSize: 5120,
            helperText: 'Ảnh đại diện bài viết, tỷ lệ 16:9 khuyến nghị. Tối đa 5MB.',
            aspectRatios: ['16:9', '4:3', '1:1']
        );
    }

    /**
     * Tạo upload cho avatar
     */
    public static function forAvatar(string $field = 'avatar_link'): FileUpload
    {
        return static::run(
            field: $field,
            label: 'Ảnh đại diện',
            directory: \App\Actions\File\GetImageDirectory::forAvatar(),
            maxWidth: 400,
            maxHeight: 400,
            maxSize: 2048,
            helperText: 'Ảnh đại diện, tỷ lệ vuông khuyến nghị. Tối đa 2MB.',
            aspectRatios: ['1:1']
        );
    }

    /**
     * Tạo upload cho slider
     */
    public static function forSlider(string $field = 'image_link'): FileUpload
    {
        return static::run(
            field: 'image_link',
            label: 'Ảnh slider',
            directory: \App\Actions\File\GetImageDirectory::forSlider(),
            maxWidth: 1920,
            maxHeight: 800,
            maxSize: 8192,
            helperText: 'Ảnh slider, tỷ lệ 21:9 hoặc 16:9 khuyến nghị. Tối đa 8MB.',
            aspectRatios: ['21:9', '16:9', '4:3']
        );
    }

    /**
     * Tạo upload cho partner logo
     */
    public static function forPartner(string $field = 'logo_link'): FileUpload
    {
        return static::run(
            field: 'logo_link',
            label: 'Logo đối tác',
            directory: \App\Actions\File\GetImageDirectory::forPartner(),
            maxWidth: 300,
            maxHeight: 200,
            maxSize: 1024,
            helperText: 'Logo đối tác, nền trong suốt khuyến nghị. Tối đa 1MB.',
            aspectRatios: ['3:2', '4:3', '1:1']
        );
    }
}
