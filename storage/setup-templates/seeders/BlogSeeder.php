<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostCategory;
use App\Models\Post;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Post Categories
        $categories = $this->createCategories();
        
        // Create Posts
        $this->createPosts($categories);
    }

    /**
     * Create post categories
     */
    private function createCategories(): array
    {
        $categoriesData = [
            [
                'name' => 'Tin tức công nghệ',
                'slug' => 'tin-tuc-cong-nghe',
                'description' => 'Cập nhật những tin tức mới nhất về công nghệ, lập trình và phát triển phần mềm.',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Dịch vụ web',
                'slug' => 'dich-vu-web',
                'description' => 'Thông tin về các dịch vụ thiết kế website, phát triển ứng dụng web.',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Hướng dẫn',
                'slug' => 'huong-dan',
                'description' => 'Các bài hướng dẫn chi tiết về lập trình, sử dụng công cụ và kỹ thuật.',
                'order' => 3,
                'status' => 'active',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $categoryData) {
            $categories[] = PostCategory::create($categoryData);
        }

        return $categories;
    }

    /**
     * Create sample posts
     */
    private function createPosts(array $categories): void
    {
        $postsData = [
            [
                'title' => 'Laravel 11 - Những tính năng mới đáng chú ý',
                'slug' => 'laravel-11-tinh-nang-moi',
                'content' => $this->getLaravelContent(),
                'post_type' => 'tin_tuc',
                'category_id' => $categories[0]->id, // Tin tức công nghệ
                'is_hot' => true,
                'published_at' => Carbon::now()->subDays(1),
                'view_count' => 2,
            ],
            [
                'title' => 'Thiết kế website responsive với Tailwind CSS',
                'slug' => 'thiet-ke-website-responsive-tailwind',
                'content' => $this->getTailwindContent(),
                'post_type' => 'dich_vu',
                'category_id' => $categories[1]->id, // Dịch vụ web
                'is_hot' => false,
                'published_at' => Carbon::now()->subDays(2),
                'view_count' => 0,
            ],
            [
                'title' => 'Hướng dẫn tối ưu SEO cho website Laravel',
                'slug' => 'huong-dan-toi-uu-seo-laravel',
                'content' => $this->getSeoContent(),
                'post_type' => 'tin_tuc',
                'category_id' => $categories[0]->id, // Tin tức công nghệ
                'is_hot' => false,
                'published_at' => Carbon::now()->subDays(3),
                'view_count' => 0,
            ],
            [
                'title' => 'Xây dựng API RESTful với Laravel Sanctum',
                'slug' => 'xay-dung-api-restful-laravel-sanctum',
                'content' => $this->getApiContent(),
                'post_type' => 'tin_tuc',
                'category_id' => $categories[2]->id, // Hướng dẫn
                'is_hot' => false,
                'published_at' => Carbon::now()->subDays(4),
                'view_count' => 1,
            ],
            [
                'title' => 'Dịch vụ phát triển ứng dụng web chuyên nghiệp',
                'slug' => 'dich-vu-phat-trien-ung-dung-web',
                'content' => $this->getServiceContent(),
                'post_type' => 'dich_vu',
                'category_id' => $categories[1]->id, // Dịch vụ web
                'is_hot' => false,
                'published_at' => Carbon::now()->subDays(5),
                'view_count' => 3,
            ],
            [
                'title' => 'Livewire 3.0 - Tương lai của Laravel Frontend',
                'slug' => 'livewire-3-tuong-lai-laravel-frontend',
                'content' => $this->getLivewireContent(),
                'post_type' => 'tin_tuc',
                'category_id' => $categories[2]->id, // Hướng dẫn
                'is_hot' => true,
                'published_at' => Carbon::now()->subDays(6),
                'view_count' => 5,
            ],
        ];

        foreach ($postsData as $postData) {
            Post::create([
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'content' => $postData['content'],
                'post_type' => $postData['post_type'],
                'post_category_id' => $postData['category_id'],
                'is_hot' => $postData['is_hot'],
                'status' => 'active',
                'published_at' => $postData['published_at'],
                'view_count' => $postData['view_count'],
                'order' => 0,
                'seo_title' => $postData['title'],
                'seo_description' => $this->generateSeoDescription($postData['content']),
            ]);
        }
    }

    /**
     * Generate SEO description from content
     */
    private function generateSeoDescription(string $content): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($content), 160);
    }

    /**
     * Laravel content
     */
    private function getLaravelContent(): string
    {
        return '<p>Laravel 11 đã ra mắt với nhiều tính năng mới thú vị. Trong bài viết này, chúng ta sẽ khám phá những cải tiến đáng chú ý nhất.</p>

<h2>Những tính năng nổi bật</h2>

<h3>1. Cải thiện Performance</h3>
<p>Laravel 11 đã được tối ưu hóa đáng kể về mặt hiệu suất, giúp ứng dụng chạy nhanh hơn và tiêu thụ ít tài nguyên hơn.</p>

<h3>2. Cú pháp mới cho Route</h3>
<p>Hệ thống routing đã được cải tiến với cú pháp ngắn gọn và dễ hiểu hơn, giúp developer viết code nhanh chóng và hiệu quả.</p>

<h3>3. Eloquent ORM nâng cấp</h3>
<p>Eloquent ORM đã được bổ sung nhiều tính năng mới, giúp thao tác với database trở nên linh hoạt và mạnh mẽ hơn.</p>

<p>Laravel 11 thực sự là một bước tiến lớn trong hệ sinh thái PHP framework. Hãy cùng khám phá và áp dụng những tính năng mới này vào dự án của bạn!</p>';
    }

    /**
     * Tailwind content
     */
    private function getTailwindContent(): string
    {
        return '<p>Tailwind CSS là framework CSS utility-first giúp bạn xây dựng giao diện nhanh chóng và hiệu quả.</p>

<h2>Ưu điểm của Tailwind</h2>

<h3>Utility-First Approach</h3>
<p>Thay vì viết CSS custom, bạn sử dụng các class có sẵn để styling trực tiếp trong HTML.</p>

<h3>Responsive Design</h3>
<p>Tailwind cung cấp hệ thống responsive breakpoints hoàn chỉnh, giúp website hiển thị tốt trên mọi thiết bị.</p>

<h3>Customization</h3>
<p>Dễ dàng customize theme, colors, spacing theo nhu cầu dự án cụ thể.</p>

<p>Với Tailwind CSS, việc thiết kế website responsive trở nên đơn giản và nhanh chóng hơn bao giờ hết.</p>';
    }

    /**
     * SEO content
     */
    private function getSeoContent(): string
    {
        return '<p>SEO là yếu tố quan trọng giúp website của bạn được tìm thấy trên Google. Bài viết này sẽ hướng dẫn cách tối ưu SEO cho ứng dụng Laravel.</p>

<h2>Các bước tối ưu SEO</h2>

<h3>1. Meta Tags</h3>
<p>Thiết lập title, description và meta tags phù hợp cho từng trang.</p>

<h3>2. URL Structure</h3>
<p>Sử dụng URL thân thiện và có ý nghĩa, tránh các tham số phức tạp.</p>

<h3>3. Sitemap</h3>
<p>Tạo sitemap XML để giúp search engine index website hiệu quả hơn.</p>

<h3>4. Page Speed</h3>
<p>Tối ưu tốc độ tải trang bằng cách compress images, minify CSS/JS.</p>

<p>Áp dụng đúng các kỹ thuật SEO sẽ giúp website của bạn đạt ranking cao hơn trên search engine.</p>';
    }

    /**
     * API content
     */
    private function getApiContent(): string
    {
        return '<p>Laravel Sanctum là giải pháp authentication đơn giản và mạnh mẽ cho API. Hướng dẫn này sẽ giúp bạn xây dựng API RESTful hoàn chỉnh.</p>

<h2>Cài đặt Laravel Sanctum</h2>
<pre><code>composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate</code></pre>

<h2>Tạo API Routes</h2>
<p>Định nghĩa các route API trong file routes/api.php với middleware sanctum.</p>

<h2>Authentication</h2>
<p>Sử dụng token-based authentication để bảo mật API endpoints.</p>

<p>Với Laravel Sanctum, việc xây dựng API bảo mật trở nên đơn giản và hiệu quả.</p>';
    }

    /**
     * Service content
     */
    private function getServiceContent(): string
    {
        return '<p>Chúng tôi cung cấp dịch vụ phát triển ứng dụng web chuyên nghiệp với công nghệ hiện đại và đội ngũ developer giàu kinh nghiệm.</p>

<h2>Dịch vụ của chúng tôi</h2>

<h3>Thiết kế Website</h3>
<p>Thiết kế website responsive, thân thiện với người dùng và tối ưu SEO.</p>

<h3>Phát triển Web App</h3>
<p>Xây dựng ứng dụng web với Laravel, Vue.js và các công nghệ tiên tiến.</p>

<h3>API Development</h3>
<p>Phát triển API RESTful bảo mật và hiệu suất cao.</p>

<h3>Maintenance & Support</h3>
<p>Hỗ trợ bảo trì và nâng cấp website 24/7.</p>

<p>Liên hệ với chúng tôi để được tư vấn giải pháp phù hợp nhất cho dự án của bạn!</p>';
    }

    /**
     * Livewire content
     */
    private function getLivewireContent(): string
    {
        return '<p>Livewire 3.0 mang đến những cải tiến vượt trội, giúp phát triển ứng dụng Laravel với trải nghiệm frontend hiện đại mà không cần JavaScript phức tạp.</p>

<h2>Tính năng mới trong Livewire 3.0</h2>

<h3>Alpine.js Integration</h3>
<p>Tích hợp sâu với Alpine.js để tạo ra những tương tác frontend mượt mà.</p>

<h3>Improved Performance</h3>
<p>Tối ưu hóa hiệu suất với lazy loading và caching thông minh.</p>

<h3>Better Developer Experience</h3>
<p>Cải thiện trải nghiệm developer với debugging tools và error handling tốt hơn.</p>

<p>Livewire 3.0 thực sự là tương lai của Laravel frontend development!</p>';
    }
}
