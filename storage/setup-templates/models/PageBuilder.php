<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class PageBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'page_type',
        'template_name',
        'route_name',
        'url_path',
        'page_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'page_blocks',
        'sidebar_blocks',
        'header_blocks',
        'footer_blocks',
        'layout_template',
        'use_sidebar',
        'sidebar_position',
        'content_width',
        'sidebar_width',
        'page_class',
        'custom_css_vars',
        'custom_css',
        'custom_js',
        'allowed_block_types',
        'block_settings',
        'mobile_blocks',
        'tablet_blocks',
        'mobile_optimized',
        'content_type',
        'dynamic_sources',
        'content_filters',
        'cache_enabled',
        'cache_duration',
        'cache_tags',
        'visibility',
        'password',
        'allowed_roles',
        'allowed_users',
        'is_published',
        'published_at',
        'expires_at',
        'author_id',
        'last_editor_id',
        'version',
        'parent_page_id',
        'language',
        'track_analytics',
        'tracking_codes',
        'view_count',
        'conversion_goals',
        'ab_testing_enabled',
        'ab_variants',
        'ab_traffic_split',
        'comments_enabled',
        'comment_moderation',
        'feedback_enabled',
        'status',
        'order',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'page_blocks' => 'array',
        'sidebar_blocks' => 'array',
        'header_blocks' => 'array',
        'footer_blocks' => 'array',
        'use_sidebar' => 'boolean',
        'content_width' => 'integer',
        'sidebar_width' => 'integer',
        'custom_css_vars' => 'array',
        'allowed_block_types' => 'array',
        'block_settings' => 'array',
        'mobile_blocks' => 'array',
        'tablet_blocks' => 'array',
        'mobile_optimized' => 'boolean',
        'dynamic_sources' => 'array',
        'content_filters' => 'array',
        'cache_enabled' => 'boolean',
        'cache_duration' => 'integer',
        'cache_tags' => 'array',
        'allowed_roles' => 'array',
        'allowed_users' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'author_id' => 'integer',
        'last_editor_id' => 'integer',
        'parent_page_id' => 'integer',
        'track_analytics' => 'boolean',
        'tracking_codes' => 'array',
        'view_count' => 'integer',
        'conversion_goals' => 'array',
        'ab_testing_enabled' => 'boolean',
        'ab_variants' => 'array',
        'ab_traffic_split' => 'decimal:2',
        'comments_enabled' => 'boolean',
        'feedback_enabled' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Quan hệ với author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Quan hệ với last editor
     */
    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_editor_id');
    }

    /**
     * Quan hệ với parent page
     */
    public function parentPage()
    {
        return $this->belongsTo(PageBuilder::class, 'parent_page_id');
    }

    /**
     * Quan hệ với child pages
     */
    public function childPages()
    {
        return $this->hasMany(PageBuilder::class, 'parent_page_id');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (!$page->author_id) {
                $page->author_id = auth()->id();
            }
        });

        static::updating(function ($page) {
            $page->last_editor_id = auth()->id();
        });

        static::saved(function ($page) {
            if ($page->cache_enabled) {
                Cache::forget("page_content_{$page->slug}");
                Cache::forget("page_blocks_{$page->id}");
            }
        });
    }

    /**
     * Scope cho pages published
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope theo page type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('page_type', $type);
    }

    /**
     * Scope theo language
     */
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Lấy danh sách page types
     */
    public static function getPageTypes(): array
    {
        return [
            'homepage' => 'Trang chủ',
            'about' => 'Giới thiệu',
            'contact' => 'Liên hệ',
            'services' => 'Dịch vụ',
            'products' => 'Sản phẩm',
            'blog' => 'Blog',
            'portfolio' => 'Portfolio',
            'landing' => 'Landing Page',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Lấy page type label
     */
    public function getPageTypeLabelAttribute(): string
    {
        return static::getPageTypes()[$this->page_type] ?? $this->page_type;
    }

    /**
     * Kiểm tra user có thể xem page không
     */
    public function canView(?int $userId = null): bool
    {
        // Check if page is published
        if (!$this->is_published || $this->status !== 'published') {
            return false;
        }

        // Check publish date
        if ($this->published_at && $this->published_at > now()) {
            return false;
        }

        // Check expiry date
        if ($this->expires_at && $this->expires_at <= now()) {
            return false;
        }

        // Check visibility
        switch ($this->visibility) {
            case 'public':
                return true;

            case 'private':
                return $userId && ($this->author_id === $userId || auth()->user()?->hasRole('admin'));

            case 'password':
                return session("page_password_{$this->id}") === true;

            case 'members':
                return $userId !== null;

            default:
                return true;
        }
    }

    /**
     * Render page content
     */
    public function renderContent(array $data = []): string
    {
        if ($this->cache_enabled) {
            $cacheKey = "page_content_{$this->slug}_" . md5(serialize($data));
            return Cache::remember($cacheKey, $this->cache_duration, function () use ($data) {
                return $this->buildContent($data);
            });
        }

        return $this->buildContent($data);
    }

    /**
     * Build page content from blocks
     */
    private function buildContent(array $data = []): string
    {
        $content = '';
        $blocks = $this->getActiveBlocks();

        foreach ($blocks as $block) {
            $content .= $this->renderBlock($block, $data);
        }

        return $content;
    }

    /**
     * Get active blocks based on device
     */
    private function getActiveBlocks(): array
    {
        $device = $this->detectDevice();
        
        switch ($device) {
            case 'mobile':
                return $this->mobile_blocks ?: $this->page_blocks;
            case 'tablet':
                return $this->tablet_blocks ?: $this->page_blocks;
            default:
                return $this->page_blocks;
        }
    }

    /**
     * Detect device type
     */
    private function detectDevice(): string
    {
        $userAgent = request()->header('User-Agent', '');
        
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        
        return 'desktop';
    }

    /**
     * Render individual block
     */
    private function renderBlock(array $block, array $data = []): string
    {
        $blockType = $block['type'] ?? 'text';
        $blockData = array_merge($block['data'] ?? [], $data);

        try {
            switch ($blockType) {
                case 'text':
                    return $this->renderTextBlock($blockData);
                case 'html':
                    return $this->renderHtmlBlock($blockData);
                case 'image':
                    return $this->renderImageBlock($blockData);
                case 'gallery':
                    return $this->renderGalleryBlock($blockData);
                case 'video':
                    return $this->renderVideoBlock($blockData);
                case 'posts':
                    return $this->renderPostsBlock($blockData);
                case 'products':
                    return $this->renderProductsBlock($blockData);
                case 'form':
                    return $this->renderFormBlock($blockData);
                case 'custom':
                    return $this->renderCustomBlock($blockData);
                default:
                    return $this->renderDefaultBlock($blockData);
            }
        } catch (\Exception $e) {
            \Log::error("Error rendering block: " . $e->getMessage());
            return "<!-- Block render error -->";
        }
    }

    /**
     * Render text block
     */
    private function renderTextBlock(array $data): string
    {
        $content = $data['content'] ?? '';
        $class = $data['css_class'] ?? '';
        
        return "<div class=\"text-block {$class}\">{$content}</div>";
    }

    /**
     * Render HTML block
     */
    private function renderHtmlBlock(array $data): string
    {
        return $data['html'] ?? '';
    }

    /**
     * Render image block
     */
    private function renderImageBlock(array $data): string
    {
        $src = $data['src'] ?? '';
        $alt = $data['alt'] ?? '';
        $class = $data['css_class'] ?? '';
        
        if (!$src) return '';
        
        return "<img src=\"{$src}\" alt=\"{$alt}\" class=\"{$class}\" loading=\"lazy\">";
    }

    /**
     * Render gallery block
     */
    private function renderGalleryBlock(array $data): string
    {
        $images = $data['images'] ?? [];
        $class = $data['css_class'] ?? 'gallery-grid';
        
        if (empty($images)) return '';
        
        $html = "<div class=\"{$class}\">";
        foreach ($images as $image) {
            $html .= "<img src=\"{$image['src']}\" alt=\"{$image['alt']}\" loading=\"lazy\">";
        }
        $html .= "</div>";
        
        return $html;
    }

    /**
     * Render video block
     */
    private function renderVideoBlock(array $data): string
    {
        $url = $data['url'] ?? '';
        $type = $data['type'] ?? 'youtube'; // youtube, vimeo, direct
        
        if (!$url) return '';
        
        switch ($type) {
            case 'youtube':
                $videoId = $this->extractYouTubeId($url);
                return "<iframe src=\"https://www.youtube.com/embed/{$videoId}\" frameborder=\"0\" allowfullscreen></iframe>";
            case 'vimeo':
                $videoId = $this->extractVimeoId($url);
                return "<iframe src=\"https://player.vimeo.com/video/{$videoId}\" frameborder=\"0\" allowfullscreen></iframe>";
            default:
                return "<video controls><source src=\"{$url}\"></video>";
        }
    }

    /**
     * Render posts block
     */
    private function renderPostsBlock(array $data): string
    {
        $limit = $data['limit'] ?? 5;
        $category = $data['category'] ?? null;
        
        $posts = \App\Models\Post::published()
                                ->when($category, function ($q) use ($category) {
                                    $q->where('cat_post_id', $category);
                                })
                                ->limit($limit)
                                ->get();
        
        return View::make('components.blocks.posts', compact('posts'))->render();
    }

    /**
     * Render products block
     */
    private function renderProductsBlock(array $data): string
    {
        $limit = $data['limit'] ?? 8;
        $category = $data['category'] ?? null;
        $featured = $data['featured'] ?? false;
        
        $products = \App\Models\Product::active()
                                     ->when($category, function ($q) use ($category) {
                                         $q->where('category_id', $category);
                                     })
                                     ->when($featured, function ($q) {
                                         $q->where('is_featured', true);
                                     })
                                     ->limit($limit)
                                     ->get();
        
        return View::make('components.blocks.products', compact('products'))->render();
    }

    /**
     * Render form block
     */
    private function renderFormBlock(array $data): string
    {
        $formType = $data['form_type'] ?? 'contact';
        $fields = $data['fields'] ?? [];
        
        return View::make('components.blocks.form', compact('formType', 'fields'))->render();
    }

    /**
     * Render custom block
     */
    private function renderCustomBlock(array $data): string
    {
        $template = $data['template'] ?? '';
        
        if ($template && View::exists($template)) {
            return View::make($template, $data)->render();
        }
        
        return $data['content'] ?? '';
    }

    /**
     * Render default block
     */
    private function renderDefaultBlock(array $data): string
    {
        return "<div class=\"block\">" . ($data['content'] ?? '') . "</div>";
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYouTubeId(string $url): string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? '';
    }

    /**
     * Extract Vimeo video ID
     */
    private function extractVimeoId(string $url): string
    {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return $matches[1] ?? '';
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Route key name
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
