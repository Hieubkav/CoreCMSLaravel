<?php

namespace App\Actions\File;

use Lorisleiva\Actions\Concerns\AsAction;

class GetImageDirectory
{
    use AsAction;

    /**
     * Lấy thư mục lưu ảnh theo type
     */
    public function handle(string $type): string
    {
        return match ($type) {
            'course' => 'images/courses',
            'post' => 'images/posts',
            'avatar' => 'images/avatars',
            'slider' => 'images/sliders',
            'gallery' => 'images/galleries',
            'logo' => 'images/logos',
            'favicon' => 'images/favicons',
            'placeholder' => 'images/placeholders',
            'staff' => 'images/staff',
            'testimonial' => 'images/testimonials',
            'partner' => 'images/partners',
            'service' => 'images/services',
            'product' => 'images/products',
            'category' => 'images/categories',
            'brand' => 'images/brands',
            default => 'images/general'
        };
    }

    /**
     * Lấy thư mục cho course thumbnails
     */
    public static function forCourse(): string
    {
        return static::run('course');
    }

    /**
     * Lấy thư mục cho post thumbnails
     */
    public static function forPost(): string
    {
        return static::run('post');
    }

    /**
     * Lấy thư mục cho avatars
     */
    public static function forAvatar(): string
    {
        return static::run('avatar');
    }

    /**
     * Lấy thư mục cho sliders
     */
    public static function forSlider(): string
    {
        return static::run('slider');
    }

    /**
     * Lấy thư mục cho galleries
     */
    public static function forGallery(): string
    {
        return static::run('gallery');
    }

    /**
     * Lấy thư mục cho logos
     */
    public static function forLogo(): string
    {
        return static::run('logo');
    }

    /**
     * Lấy thư mục cho favicons
     */
    public static function forFavicon(): string
    {
        return static::run('favicon');
    }

    /**
     * Lấy thư mục cho placeholder images
     */
    public static function forPlaceholder(): string
    {
        return static::run('placeholder');
    }

    /**
     * Lấy thư mục cho staff images
     */
    public static function forStaff(): string
    {
        return static::run('staff');
    }

    /**
     * Lấy thư mục cho testimonial images
     */
    public static function forTestimonial(): string
    {
        return static::run('testimonial');
    }

    /**
     * Lấy thư mục cho partner images
     */
    public static function forPartner(): string
    {
        return static::run('partner');
    }

    /**
     * Lấy thư mục cho service images
     */
    public static function forService(): string
    {
        return static::run('service');
    }

    /**
     * Lấy thư mục cho product images
     */
    public static function forProduct(): string
    {
        return static::run('product');
    }

    /**
     * Lấy thư mục cho category images
     */
    public static function forCategory(): string
    {
        return static::run('category');
    }

    /**
     * Lấy thư mục cho brand images
     */
    public static function forBrand(): string
    {
        return static::run('brand');
    }

    /**
     * Lấy thư mục general
     */
    public static function forGeneral(): string
    {
        return static::run('general');
    }
}
