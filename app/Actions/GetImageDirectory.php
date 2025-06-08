<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Get Image Directory Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Trả về đường dẫn thư mục cho từng loại ảnh
 * Thay thế SeoImageService::getDirectoryPath()
 */
class GetImageDirectory
{
    use AsAction;

    private const DIRECTORIES = [
        'avatar' => 'testimonials/avatars',
        'course' => 'courses/thumbnails',
        'post' => 'posts/thumbnails',
        'slider' => 'sliders',
        'partner' => 'partners/logos',
        'album' => 'albums/images',
        'instructor' => 'instructors/avatars',
        'student' => 'students/avatars',
        'material' => 'courses/materials',
        'gallery' => 'courses/gallery',
        'favicon' => 'settings',
        'logo' => 'settings',
    ];

    /**
     * Lấy đường dẫn thư mục cho loại ảnh
     */
    public function handle(string $type): string
    {
        return self::DIRECTORIES[$type] ?? 'uploads';
    }

    /**
     * Lấy thư mục cho ảnh khóa học
     */
    public static function forCourse(): string
    {
        return static::run('course');
    }

    /**
     * Lấy thư mục cho ảnh bài viết
     */
    public static function forPost(): string
    {
        return static::run('post');
    }

    /**
     * Lấy thư mục cho avatar
     */
    public static function forAvatar(): string
    {
        return static::run('avatar');
    }

    /**
     * Lấy thư mục cho slider
     */
    public static function forSlider(): string
    {
        return static::run('slider');
    }

    /**
     * Lấy thư mục cho partner logo
     */
    public static function forPartner(): string
    {
        return static::run('partner');
    }

    /**
     * Lấy thư mục cho instructor avatar
     */
    public static function forInstructor(): string
    {
        return static::run('instructor');
    }

    /**
     * Lấy thư mục cho student avatar
     */
    public static function forStudent(): string
    {
        return static::run('student');
    }

    /**
     * Lấy thư mục cho course materials
     */
    public static function forMaterial(): string
    {
        return static::run('material');
    }

    /**
     * Lấy thư mục cho course gallery
     */
    public static function forGallery(): string
    {
        return static::run('gallery');
    }

    /**
     * Lấy tất cả directories có sẵn
     */
    public static function all(): array
    {
        return self::DIRECTORIES;
    }
}
