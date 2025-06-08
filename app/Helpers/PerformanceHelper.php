<?php

if (!function_exists('optimizedImageUrl')) {
    /**
     * Tạo URL hình ảnh tối ưu với lazy loading và WebP support
     */
    function optimizedImageUrl($imagePath, $width = null, $height = null, $quality = 85)
    {
        if (empty($imagePath)) {
            return asset('images/placeholder.webp');
        }

        // Nếu là URL đầy đủ thì return luôn
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // Xử lý path
        $imagePath = ltrim($imagePath, '/');
        
        // Kiểm tra file có tồn tại không
        if (!file_exists(public_path($imagePath))) {
            return asset('images/placeholder.webp');
        }

        // Tạo WebP version nếu có thể
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        if (file_exists(public_path($webpPath))) {
            return asset($webpPath);
        }

        return asset($imagePath);
    }
}

if (!function_exists('generateImageSrcSet')) {
    /**
     * Tạo srcset cho responsive images
     */
    function generateImageSrcSet($imagePath, $sizes = [480, 768, 1024, 1200])
    {
        if (empty($imagePath)) {
            return '';
        }

        $srcSet = [];
        foreach ($sizes as $size) {
            $srcSet[] = optimizedImageUrl($imagePath, $size) . " {$size}w";
        }

        return implode(', ', $srcSet);
    }
}

if (!function_exists('lazyImageAttributes')) {
    /**
     * Tạo attributes cho lazy loading images (Enhanced version)
     */
    function lazyImageAttributes($imagePath, $altText = '', $class = '', $sizes = [480, 768, 1024], $options = [])
    {
        $priority = $options['priority'] ?? false;

        return [
            'src' => asset('storage/' . $imagePath),
            'alt' => $altText,
            'class' => $class,
            'loading' => $priority ? 'eager' : 'lazy',
            'decoding' => 'async'
        ];
    }
}

if (!function_exists('smartImageAttributes')) {
    /**
     * Tạo attributes cho smart image component
     */
    function smartImageAttributes($imagePath, $options = [])
    {
        $defaultOptions = [
            'alt' => '',
            'class' => '',
            'priority' => false,
        ];

        $options = array_merge($defaultOptions, $options);

        return [
            'src' => asset('storage/' . $imagePath),
            'alt' => $options['alt'],
            'class' => $options['class'],
            'loading' => $options['priority'] ? 'eager' : 'lazy',
            'decoding' => 'async'
        ];
    }
}

if (!function_exists('generateBlurPlaceholder')) {
    /**
     * Tạo blur placeholder cho image - Đơn giản hóa
     */
    function generateBlurPlaceholder($imagePath, $width = 20, $height = 20)
    {
        // Trả về placeholder SVG đơn giản
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#f3f4f6"/>
                <text x="50%" y="50%" text-anchor="middle" dy=".3em" fill="#9ca3af" font-size="12">Loading...</text>
            </svg>'
        );
    }
}

if (!function_exists('generateResponsiveImages')) {
    /**
     * Tạo responsive images - Đơn giản hóa
     */
    function generateResponsiveImages($imagePath, $sizes = null)
    {
        // Trả về array đơn giản
        return [
            'original' => asset('storage/' . $imagePath),
            'webp' => asset('storage/' . $imagePath)
        ];
    }
}

if (!function_exists('optimizeImageForWeb')) {
    /**
     * Tối ưu ảnh cho web - Đơn giản hóa
     */
    function optimizeImageForWeb($imagePath, $options = [])
    {
        // Chỉ trả về true vì không còn service phức tạp
        return true;
    }
}

if (!function_exists('cacheKey')) {
    /**
     * Tạo cache key với prefix và TTL
     */
    function cacheKey($key, $prefix = 'Core_vuphuc')
    {
        return $prefix . '_' . $key;
    }
}

if (!function_exists('optimizedQuery')) {
    /**
     * Tối ưu query với select fields cần thiết
     */
    function optimizedQuery($model, $fields = ['*'])
    {
        return $model::select($fields);
    }
}

if (!function_exists('minifyHtml')) {
    /**
     * Minify HTML output để giảm kích thước
     */
    function minifyHtml($html)
    {
        // Chỉ minify trong production
        if (app()->environment('production')) {
            $html = preg_replace('/\s+/', ' ', $html);
            $html = preg_replace('/>\s+</', '><', $html);
            return trim($html);
        }
        
        return $html;
    }
}

if (!function_exists('preloadCriticalResources')) {
    /**
     * Tạo preload links cho critical resources
     */
    function preloadCriticalResources()
    {
        $resources = [
            asset('build/assets/app.css') => 'style',
            asset('build/assets/app.js') => 'script',
        ];

        $preloadLinks = '';
        foreach ($resources as $url => $type) {
            $preloadLinks .= "<link rel=\"preload\" href=\"{$url}\" as=\"{$type}\">\n";
        }

        return $preloadLinks;
    }
}

if (!function_exists('criticalCss')) {
    /**
     * Inline critical CSS để tăng tốc First Contentful Paint
     */
    function criticalCss()
    {
        $criticalCss = '
        <style>
        body{font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif}
        .container{max-width:1200px;margin:0 auto;padding:0 1rem}
        .btn{display:inline-flex;align-items:center;padding:0.5rem 1rem;border-radius:0.375rem;font-weight:500;transition:all 0.2s}
        .btn-primary{background-color:#3b82f6;color:white}
        .btn-primary:hover{background-color:#2563eb}
        .skeleton{animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;background-color:#e5e7eb;border-radius:0.25rem}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
        </style>';

        return $criticalCss;
    }
}

if (!function_exists('deferNonCriticalCss')) {
    /**
     * Defer non-critical CSS loading
     */
    function deferNonCriticalCss($href)
    {
        return "<link rel=\"preload\" href=\"{$href}\" as=\"style\" onload=\"this.onload=null;this.rel='stylesheet'\">";
    }
}

if (!function_exists('generateMetaTags')) {
    /**
     * Tạo meta tags tối ưu cho SEO
     */
    function generateMetaTags($title, $description, $image = null, $url = null)
    {
        $url = $url ?: request()->url();
        $image = $image ?: asset('images/og-default.jpg');
        
        $metaTags = [
            '<meta name="description" content="' . e($description) . '">',
            '<meta property="og:title" content="' . e($title) . '">',
            '<meta property="og:description" content="' . e($description) . '">',
            '<meta property="og:image" content="' . $image . '">',
            '<meta property="og:url" content="' . $url . '">',
            '<meta property="og:type" content="website">',
            '<meta name="twitter:card" content="summary_large_image">',
            '<meta name="twitter:title" content="' . e($title) . '">',
            '<meta name="twitter:description" content="' . e($description) . '">',
            '<meta name="twitter:image" content="' . $image . '">',
        ];

        return implode("\n", $metaTags);
    }
}

if (!function_exists('structuredData')) {
    /**
     * Tạo structured data cho SEO
     */
    function structuredData($type, $data)
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => $type,
        ];

        $structuredData = array_merge($structuredData, $data);

        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}

if (!function_exists('simpleLazyImage')) {
    /**
     * Tạo ảnh lazy loading đơn giản với native loading="lazy"
     */
    function simpleLazyImage($src, $alt = '', $class = '', $options = [])
    {
        $priority = $options['priority'] ?? false;
        $fallbackType = $options['type'] ?? 'default';
        $width = $options['width'] ?? null;
        $height = $options['height'] ?? null;

        // Fallback icons theo type
        $fallbackIcons = [
            'course' => 'fas fa-graduation-cap',
            'news' => 'fas fa-newspaper',
            'partner' => 'fas fa-handshake',
            'album' => 'fas fa-images',
            'testimonial' => 'fas fa-quote-left',
            'default' => 'fas fa-image'
        ];

        $fallbackIcon = $fallbackIcons[$fallbackType] ?? $fallbackIcons['default'];

        // Xử lý đường dẫn ảnh
        $imagePath = $src;
        if (str_starts_with($src, asset('storage/'))) {
            $imagePath = str_replace(asset('storage/'), '', $src);
        } elseif (str_starts_with($src, 'storage/')) {
            $imagePath = str_replace('storage/', '', $src);
        }

        // Kiểm tra ảnh tồn tại
        $imageExists = !empty($imagePath) && \Illuminate\Support\Facades\Storage::exists('public/' . $imagePath);

        if (!$imageExists) {
            return '<div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center">
                        <i class="' . $fallbackIcon . ' text-2xl text-gray-400"></i>
                    </div>';
        }

        $imageUrl = asset('storage/' . $imagePath);
        $loading = $priority ? 'eager' : 'lazy';

        $widthAttr = $width ? 'width="' . $width . '"' : '';
        $heightAttr = $height ? 'height="' . $height . '"' : '';

        return '<div class="simple-lazy-image-container relative overflow-hidden">
                    <img src="' . $imageUrl . '"
                         alt="' . e($alt) . '"
                         class="' . $class . '"
                         ' . $widthAttr . '
                         ' . $heightAttr . '
                         loading="' . $loading . '"
                         decoding="async"
                         onerror="handleImageError(this)"
                         style="transition: opacity 0.3s ease;">
                    <div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center absolute inset-0" style="display: none;">
                        <i class="' . $fallbackIcon . ' text-2xl text-gray-400"></i>
                    </div>
                </div>';
    }
}
