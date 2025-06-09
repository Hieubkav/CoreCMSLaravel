<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThemeSetting;
use App\Models\PageBuilder;
use App\Models\WidgetSetting;
use App\Models\WebDesign;

class WebDesignManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎨 Bắt đầu tạo dữ liệu mẫu cho Web Design Management Module...');

        $this->createWebDesignSettings();
        $this->createThemeSettings();
        $this->createPageBuilders();
        $this->createWidgetSettings();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu Web Design Management!');
    }

    /**
     * Tạo theme settings
     */
    private function createThemeSettings()
    {
        $themes = [
            [
                'name' => 'Default Red Theme',
                'slug' => 'default-red-theme',
                'description' => 'Theme mặc định với màu đỏ chủ đạo, thiết kế minimalist',
                'theme_version' => '1.0',
                'author' => 'Core Laravel Team',
                'compatibility' => ['laravel' => '10.x', 'php' => '8.1+'],
                'primary_color' => '#dc2626',
                'secondary_color' => '#1f2937',
                'accent_color' => '#f59e0b',
                'success_color' => '#10b981',
                'warning_color' => '#f59e0b',
                'error_color' => '#ef4444',
                'info_color' => '#3b82f6',
                'bg_primary' => 'bg-white',
                'bg_secondary' => 'bg-gray-50',
                'bg_accent' => 'bg-red-50',
                'font_family_primary' => 'Inter, system-ui, sans-serif',
                'font_family_secondary' => 'Inter, system-ui, sans-serif',
                'font_family_heading' => 'Inter, system-ui, sans-serif',
                'font_size_base' => 16.0,
                'line_height_base' => 1.5,
                'layout_type' => 'full-width',
                'container_max_width' => 1200,
                'sidebar_position' => 'right',
                'sidebar_width' => 300,
                'header_style' => 'default',
                'header_sticky' => true,
                'header_bg_color' => 'bg-white',
                'header_height' => 80,
                'footer_style' => 'default',
                'footer_bg_color' => 'bg-gray-900',
                'footer_text_color' => 'text-white',
                'footer_show_social' => true,
                'footer_show_newsletter' => true,
                'nav_style' => 'horizontal',
                'nav_show_icons' => false,
                'nav_hover_effect' => 'underline',
                'nav_mobile_hamburger' => true,
                'button_style' => 'rounded',
                'button_size' => 'medium',
                'button_shadows' => true,
                'button_hover_effect' => 'scale',
                'card_style' => 'shadow',
                'border_radius' => 8,
                'use_shadows' => true,
                'shadow_intensity' => 'medium',
                'animations_enabled' => true,
                'animation_speed' => 'normal',
                'enabled_animations' => ['fade', 'slide', 'scale'],
                'breakpoints' => [
                    'sm' => '640px',
                    'md' => '768px',
                    'lg' => '1024px',
                    'xl' => '1280px'
                ],
                'mobile_first' => true,
                'mobile_nav_style' => 'overlay',
                'custom_fonts' => [
                    'google_fonts' => ['Inter:300,400,500,600,700']
                ],
                'dark_mode_enabled' => false,
                'rtl_support' => false,
                'print_styles' => true,
                'enabled_features' => ['responsive', 'animations', 'lazy_loading'],
                'css_minification' => true,
                'js_minification' => true,
                'lazy_loading' => true,
                'critical_css' => false,
                'meta_tags' => [
                    'viewport' => 'width=device-width, initial-scale=1',
                    'charset' => 'utf-8'
                ],
                'social_meta' => [
                    'og_type' => 'website',
                    'twitter_card' => 'summary_large_image'
                ],
                'is_active' => true,
                'is_default' => true,
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Minimal Blue Theme',
                'slug' => 'minimal-blue-theme',
                'description' => 'Theme tối giản với màu xanh dương, phù hợp cho business',
                'theme_version' => '1.0',
                'author' => 'Core Laravel Team',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#1e293b',
                'accent_color' => '#06b6d4',
                'layout_type' => 'boxed',
                'container_max_width' => 1140,
                'header_style' => 'minimal',
                'footer_style' => 'minimal',
                'button_style' => 'square',
                'card_style' => 'border',
                'use_shadows' => false,
                'animations_enabled' => false,
                'is_active' => false,
                'is_default' => false,
                'status' => 'active',
                'order' => 2
            ]
        ];

        foreach ($themes as $themeData) {
            $theme = ThemeSetting::firstOrCreate(
                ['slug' => $themeData['slug']],
                $themeData
            );

            // Generate CSS/JS files for active theme
            if ($theme->is_active) {
                $theme->generateCssFile();
                $theme->generateJsFile();
            }
        }

        $this->command->info('✅ Đã tạo ' . count($themes) . ' theme settings');
    }

    /**
     * Tạo page builders
     */
    private function createPageBuilders()
    {
        $pages = [
            [
                'name' => 'Trang chủ mẫu',
                'slug' => 'trang-chu-mau',
                'description' => 'Trang chủ được xây dựng bằng Page Builder',
                'page_type' => 'homepage',
                'template_name' => 'layouts.shop',
                'route_name' => 'home',
                'url_path' => '/',
                'page_title' => 'Trang chủ - Website của bạn',
                'meta_description' => 'Chào mừng đến với website của chúng tôi. Khám phá các sản phẩm và dịch vụ tuyệt vời.',
                'meta_keywords' => ['trang chủ', 'sản phẩm', 'dịch vụ'],
                'page_blocks' => [
                    [
                        'type' => 'hero',
                        'data' => [
                            'title' => 'Chào mừng đến với Website',
                            'subtitle' => 'Khám phá các sản phẩm tuyệt vời',
                            'background_image' => '/images/hero-bg.jpg',
                            'cta_text' => 'Xem sản phẩm',
                            'cta_url' => '/products'
                        ]
                    ],
                    [
                        'type' => 'products',
                        'data' => [
                            'title' => 'Sản phẩm nổi bật',
                            'limit' => 8,
                            'featured' => true,
                            'layout' => 'grid'
                        ]
                    ],
                    [
                        'type' => 'posts',
                        'data' => [
                            'title' => 'Tin tức mới nhất',
                            'limit' => 4,
                            'layout' => 'cards'
                        ]
                    ]
                ],
                'layout_template' => 'layouts.shop',
                'use_sidebar' => false,
                'content_width' => 12,
                'mobile_optimized' => true,
                'content_type' => 'mixed',
                'cache_enabled' => true,
                'cache_duration' => 3600,
                'visibility' => 'public',
                'is_published' => true,
                'published_at' => now(),
                'author_id' => 1,
                'language' => 'vi',
                'track_analytics' => true,
                'status' => 'published',
                'order' => 1
            ],
            [
                'name' => 'Trang giới thiệu',
                'slug' => 'gioi-thieu',
                'description' => 'Trang giới thiệu về công ty',
                'page_type' => 'about',
                'route_name' => 'about',
                'url_path' => '/gioi-thieu',
                'page_title' => 'Giới thiệu - Về chúng tôi',
                'meta_description' => 'Tìm hiểu về lịch sử, sứ mệnh và tầm nhìn của chúng tôi.',
                'page_blocks' => [
                    [
                        'type' => 'text',
                        'data' => [
                            'content' => '<h1>Về chúng tôi</h1><p>Chúng tôi là một công ty chuyên cung cấp các sản phẩm chất lượng cao...</p>'
                        ]
                    ],
                    [
                        'type' => 'image',
                        'data' => [
                            'src' => '/images/about-us.jpg',
                            'alt' => 'Về chúng tôi',
                            'css_class' => 'img-fluid rounded'
                        ]
                    ]
                ],
                'use_sidebar' => true,
                'sidebar_position' => 'right',
                'content_width' => 9,
                'sidebar_width' => 3,
                'is_published' => true,
                'published_at' => now(),
                'status' => 'published',
                'order' => 2
            ]
        ];

        foreach ($pages as $pageData) {
            PageBuilder::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($pages) . ' page builders');
    }

    /**
     * Tạo widget settings
     */
    private function createWidgetSettings()
    {
        $widgets = [
            [
                'name' => 'Tìm kiếm',
                'slug' => 'tim-kiem',
                'description' => 'Widget tìm kiếm sản phẩm và bài viết',
                'widget_type' => 'search',
                'widget_config' => [
                    'placeholder' => 'Tìm kiếm...',
                    'search_types' => ['products', 'posts'],
                    'show_suggestions' => true
                ],
                'position' => 'sidebar_right',
                'order_position' => 1,
                'show_on_mobile' => true,
                'show_on_tablet' => true,
                'show_on_desktop' => true,
                'show_for_guests' => true,
                'show_title' => true,
                'title_tag' => 'h3',
                'wrapper_tag' => 'div',
                'css_class' => 'widget widget-search',
                'cache_enabled' => false,
                'status' => 'active',
                'is_system_widget' => true,
                'order' => 1
            ],
            [
                'name' => 'Sản phẩm mới',
                'slug' => 'san-pham-moi',
                'description' => 'Hiển thị danh sách sản phẩm mới nhất',
                'widget_type' => 'recent_products',
                'widget_config' => [
                    'limit' => 5,
                    'show_price' => true,
                    'show_image' => true,
                    'image_size' => 'thumbnail'
                ],
                'position' => 'sidebar_right',
                'order_position' => 2,
                'show_on_mobile' => false,
                'show_on_tablet' => true,
                'show_on_desktop' => true,
                'show_for_guests' => true,
                'cache_enabled' => true,
                'cache_duration' => 1800,
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Bài viết mới',
                'slug' => 'bai-viet-moi',
                'description' => 'Hiển thị danh sách bài viết mới nhất',
                'widget_type' => 'recent_posts',
                'widget_config' => [
                    'limit' => 5,
                    'show_date' => true,
                    'show_excerpt' => false,
                    'show_thumbnail' => true
                ],
                'position' => 'sidebar_right',
                'order_position' => 3,
                'show_on_mobile' => false,
                'show_on_tablet' => true,
                'show_on_desktop' => true,
                'cache_enabled' => true,
                'cache_duration' => 1800,
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Đăng ký nhận tin',
                'slug' => 'dang-ky-nhan-tin',
                'description' => 'Form đăng ký nhận bản tin email',
                'widget_type' => 'newsletter',
                'widget_config' => [
                    'title' => 'Đăng ký nhận tin',
                    'description' => 'Nhận thông tin về sản phẩm mới và khuyến mãi',
                    'placeholder' => 'Nhập email của bạn',
                    'button_text' => 'Đăng ký'
                ],
                'position' => 'footer',
                'order_position' => 1,
                'show_on_mobile' => true,
                'show_on_tablet' => true,
                'show_on_desktop' => true,
                'show_for_guests' => true,
                'css_class' => 'widget widget-newsletter',
                'status' => 'active',
                'order' => 4
            ],
            [
                'name' => 'Thông tin liên hệ',
                'slug' => 'thong-tin-lien-he',
                'description' => 'Hiển thị thông tin liên hệ của công ty',
                'widget_type' => 'contact_info',
                'widget_config' => [
                    'show_address' => true,
                    'show_phone' => true,
                    'show_email' => true,
                    'show_icons' => true
                ],
                'content' => '<div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</p>
                    <p><i class="fas fa-phone"></i> 0123 456 789</p>
                    <p><i class="fas fa-envelope"></i> info@example.com</p>
                </div>',
                'position' => 'footer',
                'order_position' => 2,
                'show_on_mobile' => true,
                'show_on_tablet' => true,
                'show_on_desktop' => true,
                'css_class' => 'widget widget-contact-info',
                'status' => 'active',
                'order' => 5
            ]
        ];

        foreach ($widgets as $widgetData) {
            WidgetSetting::firstOrCreate(
                ['slug' => $widgetData['slug']],
                $widgetData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($widgets) . ' widget settings');
    }

    /**
     * Tạo web design settings
     */
    private function createWebDesignSettings()
    {
        $webDesignData = [
            // Hero Banner
            'hero_banner_enabled' => true,
            'hero_banner_order' => 1,
            'hero_banner_title' => 'Chào mừng đến với Core Laravel Framework',
            'hero_banner_description' => 'Framework mạnh mẽ và linh hoạt cho mọi dự án web',
            'hero_banner_bg_color' => 'bg-white',

            // Courses Overview
            'courses_overview_enabled' => true,
            'courses_overview_order' => 2,
            'courses_overview_title' => 'Sản phẩm nổi bật',
            'courses_overview_description' => 'Khám phá các sản phẩm chất lượng cao của chúng tôi',
            'courses_overview_bg_color' => 'bg-gray-50',

            // Album Timeline
            'album_timeline_enabled' => true,
            'album_timeline_order' => 3,
            'album_timeline_title' => 'Hình ảnh hoạt động',
            'album_timeline_description' => 'Những khoảnh khắc đáng nhớ trong hành trình phát triển',
            'album_timeline_bg_color' => 'bg-white',

            // Course Groups
            'course_groups_enabled' => true,
            'course_groups_order' => 4,
            'course_groups_title' => 'Danh mục sản phẩm',
            'course_groups_description' => 'Tìm hiểu các danh mục sản phẩm đa dạng',
            'course_groups_bg_color' => 'bg-gray-50',

            // Course Categories
            'course_categories_enabled' => true,
            'course_categories_order' => 5,
            'course_categories_title' => 'Thể loại nội dung',
            'course_categories_description' => 'Khám phá nội dung theo từng chủ đề',
            'course_categories_bg_color' => 'bg-white',

            // Testimonials
            'testimonials_enabled' => true,
            'testimonials_order' => 6,
            'testimonials_title' => 'Đánh giá khách hàng',
            'testimonials_description' => 'Những phản hồi tích cực từ khách hàng của chúng tôi',
            'testimonials_bg_color' => 'bg-red-50',

            // FAQ
            'faq_enabled' => true,
            'faq_order' => 7,
            'faq_title' => 'Câu hỏi thường gặp',
            'faq_description' => 'Tìm câu trả lời cho những thắc mắc phổ biến',
            'faq_bg_color' => 'bg-white',

            // Partners
            'partners_enabled' => true,
            'partners_order' => 8,
            'partners_title' => 'Đối tác tin cậy',
            'partners_description' => 'Những đối tác đồng hành cùng chúng tôi',
            'partners_bg_color' => 'bg-gray-50',

            // Blog Posts
            'blog_posts_enabled' => true,
            'blog_posts_order' => 9,
            'blog_posts_title' => 'Tin tức & Blog',
            'blog_posts_description' => 'Cập nhật những thông tin mới nhất từ chúng tôi',
            'blog_posts_bg_color' => 'bg-white',

            // Homepage CTA
            'homepage_cta_enabled' => true,
            'homepage_cta_order' => 10,
            'homepage_cta_title' => 'Bắt đầu ngay hôm nay',
            'homepage_cta_description' => 'Tham gia cùng hàng nghìn người dùng đã tin tưởng Core Laravel Framework',
            'homepage_cta_primary_button_text' => 'Khám phá ngay',
            'homepage_cta_primary_button_link' => '/products',
            'homepage_cta_secondary_button_text' => 'Liên hệ tư vấn',
            'homepage_cta_secondary_button_link' => '/contact',
            'homepage_cta_bg_color' => 'bg-red-600',

            // Global Settings
            'animations_enabled' => true,
            'animation_speed' => 'normal',
            'enabled_animations' => ['fade', 'slide'],
            'lazy_loading_enabled' => true,
            'cache_duration' => 3600,

            // Meta
            'last_updated_at' => now(),
            'updated_by' => 1, // Admin user
        ];

        $webDesign = WebDesign::firstOrCreate([], $webDesignData);

        $this->command->info('✅ Đã tạo cài đặt web design mặc định');
    }
}
