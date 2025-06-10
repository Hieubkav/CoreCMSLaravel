<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\Post;
use App\Models\Setting;
use App\Helpers\PlaceholderHelper;
use Illuminate\Support\Facades\Cache;

/**
 * Handle SEO Action - KISS Principle
 * 
 * Thay thế SeoService phức tạp
 * Chỉ làm 1 việc: Xử lý SEO data cho models
 */
class HandleSeo
{
    use AsAction;

    /**
     * Xử lý SEO cho model
     */
    public function handle(string $action, $model = null, array $params = []): array|string
    {
        return match ($action) {
            'og_image' => $this->getOgImage($model),
            'meta_tags' => $this->getMetaTags($params),
            'structured_data' => $this->getStructuredData($model),
            'breadcrumb' => $this->getBreadcrumbData($params['breadcrumbs'] ?? []),
            default => []
        };
    }

    /**
     * Lấy OG image cho model
     */
    private function getOgImage($model): string
    {
        if (!$model) {
            return $this->getDefaultOgImage();
        }

        // Kiểm tra theo class của model
        return match (get_class($model)) {
            Post::class => $this->getPostOgImage($model),
            default => $this->getDefaultOgImage()
        };
    }

    /**
     * Lấy OG image cho bài viết
     */
    private function getPostOgImage(Post $post): string
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
        return $this->getDefaultOgImage();
    }

    /**
     * Lấy OG image mặc định từ settings
     */
    private function getDefaultOgImage(): string
    {
        try {
            $settings = Cache::remember('global_settings', 3600, function () {
                return Setting::first();
            });

            if ($settings && $settings->og_image_link) {
                return asset('storage/' . $settings->og_image_link);
            }

            if ($settings && $settings->logo_link) {
                return asset('storage/' . $settings->logo_link);
            }
        } catch (\Exception $e) {
            // Fallback nếu bảng settings không tồn tại
        }

        return PlaceholderHelper::getLogo();
    }

    /**
     * Tạo meta tags cho SEO
     */
    private function getMetaTags(array $params): array
    {
        $title = $params['title'] ?? '';
        $description = $params['description'] ?? '';
        $ogImage = $params['og_image'] ?? $this->getDefaultOgImage();
        $url = $params['url'] ?? '';
        $type = $params['type'] ?? 'website';

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

    /**
     * Tạo structured data cho model
     */
    private function getStructuredData($model): array
    {
        if (!$model) {
            return [];
        }

        return match (get_class($model)) {
            Post::class => $this->getPostStructuredData($model),
            default => []
        };
    }

    /**
     * Tạo structured data cho bài viết
     */
    private function getPostStructuredData(Post $post): array
    {
        try {
            $settings = Cache::remember('global_settings', 3600, function () {
                return Setting::first();
            });

            $siteName = $settings->site_name ?? 'Website';

            return [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $post->seo_title ?: $post->title,
                'description' => $post->seo_description ?: ($post->content ? strip_tags(substr($post->content, 0, 160)) : ''),
                'image' => $this->getPostOgImage($post),
                'url' => route('posts.show', $post->slug),
                'datePublished' => $post->created_at->toISOString(),
                'dateModified' => $post->updated_at->toISOString(),
                'author' => [
                    '@type' => 'Organization',
                    'name' => $siteName
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $this->getDefaultOgImage()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Tạo breadcrumb structured data
     */
    private function getBreadcrumbData(array $breadcrumbs): array
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
     * Static methods để dễ sử dụng
     */
    public static function getOgImageFor($model): string
    {
        return static::run('og_image', $model);
    }

    public static function getMetaTagsFor(array $params): array
    {
        return static::run('meta_tags', null, $params);
    }

    public static function getStructuredDataFor($model): array
    {
        return static::run('structured_data', $model);
    }

    public static function getBreadcrumbFor(array $breadcrumbs): array
    {
        return static::run('breadcrumb', null, ['breadcrumbs' => $breadcrumbs]);
    }
}
