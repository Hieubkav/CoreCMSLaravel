<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Str;

/**
 * Create SEO Image Name Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Tạo tên file ảnh SEO-friendly
 * Thay thế SeoImageService::createSeoFriendlyImageName()
 */
class CreateSeoImageName
{
    use AsAction;

    /**
     * Tạo tên file ảnh SEO-friendly
     */
    public function handle(string $name, string $type = 'image', string $extension = 'webp'): string
    {
        // Loại bỏ dấu và tạo slug
        $slug = Str::slug($name, '-');
        
        // Thêm timestamp để tránh trùng lặp
        $timestamp = now()->format('YmdHis');
        
        // Tạo tên file SEO-friendly
        return $type . '-' . $slug . '-' . $timestamp . '.' . $extension;
    }

    /**
     * Tạo tên cho ảnh khóa học
     */
    public static function forCourse(string $courseName): string
    {
        return static::run($courseName, 'course');
    }

    /**
     * Tạo tên cho ảnh bài viết
     */
    public static function forPost(string $postTitle): string
    {
        return static::run($postTitle, 'post');
    }

    /**
     * Tạo tên cho avatar
     */
    public static function forAvatar(string $name): string
    {
        return static::run($name, 'avatar');
    }

    /**
     * Tạo tên cho slider
     */
    public static function forSlider(string $title): string
    {
        return static::run($title, 'slider');
    }

    /**
     * Tạo tên cho partner logo
     */
    public static function forPartner(string $partnerName): string
    {
        return static::run($partnerName, 'partner');
    }
}
