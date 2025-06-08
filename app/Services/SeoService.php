<?php

namespace App\Services;


use App\Models\Post;
use App\Models\Setting;
use App\Helpers\PlaceholderHelper;
use Illuminate\Support\Facades\Cache;

class SeoService
{
    /**
     * Lấy OG image cho khóa học
     *
     * @param \App\Models\Course $course
     * @return string
     */
    public static function getCourseOgImage($course): string
    {
        // Ưu tiên og_image_link của khóa học
        if ($course->og_image_link) {
            return asset('storage/' . $course->og_image_link);
        }

        // Fallback về thumbnail của khóa học
        if ($course->thumbnail) {
            return asset('storage/' . $course->thumbnail);
        }

        // Fallback về settings og_image
        return self::getDefaultOgImage();
    }

    /**
     * Lấy OG image cho bài viết
     *
     * @param Post $post
     * @return string
     */
    public static function getPostOgImage(Post $post): string
    {
        // Ưu tiên thumbnail
        if ($post->thumbnail) {
            return asset('storage/' . $post->thumbnail);
        }

        // Ưu tiên ảnh đầu tiên từ relationship images
        if ($post->images && $post->images->count() > 0) {
            $firstImage = $post->images->first();
            if ($firstImage && $firstImage->image_link) {
                return asset('storage/' . $firstImage->image_link);
            }
        }

        // Fallback về og_image_link của bài viết
        if ($post->og_image_link) {
            return asset('storage/' . $post->og_image_link);
        }

        // Fallback về settings og_image
        return self::getDefaultOgImage();
    }

    /**
     * Lấy OG image mặc định từ settings
     *
     * @return string
     */
    public static function getDefaultOgImage(): string
    {
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::first();
        });

        if ($settings && $settings->og_image_link) {
            return asset('storage/' . $settings->og_image_link);
        }

        if ($settings && $settings->logo_link) {
            return asset('storage/' . $settings->logo_link);
        }

        return PlaceholderHelper::getLogo();
    }

    /**
     * Tạo structured data cho khóa học
     *
     * @param \App\Models\Course $course
     * @return array
     */
    public static function getCourseStructuredData($course): array
    {
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::first();
        });

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course->seo_title ?: $course->title,
            'description' => $course->seo_description ?: $course->description,
            'image' => self::getCourseOgImage($course),
            'url' => route('courses.show', $course->slug),
            'provider' => [
                '@type' => 'Organization',
                'name' => $settings->site_name ?? 'VBA Vũ Phúc'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $course->price,
                'priceCurrency' => 'VND',
                'availability' => 'https://schema.org/InStock',
                'url' => route('courses.show', $course->slug)
            ],
            'instructor' => $course->instructor ? [
                '@type' => 'Person',
                'name' => $course->instructor->name
            ] : null
        ];
    }

    /**
     * Tạo structured data cho bài viết
     *
     * @param Post $post
     * @return array
     */
    public static function getPostStructuredData(Post $post): array
    {
        $settings = Cache::remember('global_settings', 3600, function () {
            return Setting::first();
        });

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->seo_title ?: $post->title,
            'description' => $post->seo_description ?: ($post->content ? strip_tags(substr($post->content, 0, 160)) : ''),
            'image' => self::getPostOgImage($post),
            'url' => route('posts.show', $post->slug),
            'datePublished' => $post->created_at->toISOString(),
            'dateModified' => $post->updated_at->toISOString(),
            'author' => [
                '@type' => 'Organization',
                'name' => $settings->site_name ?? 'Vũ Phúc Baking'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $settings->site_name ?? 'Vũ Phúc Baking',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => self::getDefaultOgImage()
                ]
            ]
        ];
    }

    /**
     * Tạo breadcrumb structured data
     *
     * @param array $breadcrumbs
     * @return array
     */
    public static function getBreadcrumbStructuredData(array $breadcrumbs): array
    {
        $itemListElement = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];
    }

    /**
     * Tạo meta tags cho SEO
     *
     * @param string $title
     * @param string $description
     * @param string $ogImage
     * @param string $url
     * @param string $type
     * @return array
     */
    public static function getMetaTags(string $title, string $description, string $ogImage, string $url, string $type = 'website'): array
    {
        return [
            'title' => $title,
            'description' => $description,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $ogImage,
            'og:url' => $url,
            'og:type' => $type,
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $ogImage,
        ];
    }
}
