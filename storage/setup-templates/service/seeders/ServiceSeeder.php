<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicesData = [
            [
                'name' => 'Thiết kế Website chuyên nghiệp',
                'slug' => 'thiet-ke-website-chuyen-nghiep',
                'category' => 'web-development',
                'price' => 15000000,
                'duration' => '2-3-months',
                'short_description' => 'Thiết kế website hiện đại, responsive và tối ưu SEO cho doanh nghiệp.',
                'description' => $this->getWebDesignDescription(),
                'features' => [
                    ['name' => 'Thiết kế responsive', 'description' => 'Tương thích mọi thiết bị', 'included' => true],
                    ['name' => 'Tối ưu SEO', 'description' => 'Chuẩn SEO Google', 'included' => true],
                    ['name' => 'Admin panel', 'description' => 'Quản trị nội dung dễ dàng', 'included' => true],
                    ['name' => 'SSL Certificate', 'description' => 'Bảo mật HTTPS', 'included' => true],
                    ['name' => 'Hosting 1 năm', 'description' => 'Miễn phí hosting năm đầu', 'included' => false],
                ],
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Phát triển ứng dụng Mobile',
                'slug' => 'phat-trien-ung-dung-mobile',
                'category' => 'mobile-app',
                'price' => 50000000,
                'duration' => '3-6-months',
                'short_description' => 'Phát triển ứng dụng mobile native và cross-platform cho iOS và Android.',
                'description' => $this->getMobileAppDescription(),
                'features' => [
                    ['name' => 'iOS & Android', 'description' => 'Hỗ trợ cả 2 nền tảng', 'included' => true],
                    ['name' => 'UI/UX Design', 'description' => 'Thiết kế giao diện hiện đại', 'included' => true],
                    ['name' => 'API Integration', 'description' => 'Tích hợp API backend', 'included' => true],
                    ['name' => 'Push Notification', 'description' => 'Thông báo đẩy', 'included' => true],
                    ['name' => 'App Store Deploy', 'description' => 'Hỗ trợ đăng ký store', 'included' => false],
                ],
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Hệ thống Thương mại điện tử',
                'slug' => 'he-thong-thuong-mai-dien-tu',
                'category' => 'ecommerce',
                'price' => 30000000,
                'duration' => '2-3-months',
                'short_description' => 'Xây dựng website bán hàng online với đầy đủ tính năng quản lý và thanh toán.',
                'description' => $this->getEcommerceDescription(),
                'features' => [
                    ['name' => 'Quản lý sản phẩm', 'description' => 'Thêm, sửa, xóa sản phẩm', 'included' => true],
                    ['name' => 'Giỏ hàng & Checkout', 'description' => 'Quy trình mua hàng hoàn chỉnh', 'included' => true],
                    ['name' => 'Thanh toán online', 'description' => 'Tích hợp VNPay, MoMo', 'included' => true],
                    ['name' => 'Quản lý đơn hàng', 'description' => 'Theo dõi trạng thái đơn hàng', 'included' => true],
                    ['name' => 'Marketing tools', 'description' => 'Mã giảm giá, khuyến mãi', 'included' => false],
                ],
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'Dịch vụ SEO & Digital Marketing',
                'slug' => 'dich-vu-seo-digital-marketing',
                'category' => 'seo-marketing',
                'price' => 8000000,
                'duration' => 'ongoing',
                'short_description' => 'Tối ưu website lên TOP Google và chiến lược marketing online hiệu quả.',
                'description' => $this->getSEODescription(),
                'features' => [
                    ['name' => 'Keyword Research', 'description' => 'Nghiên cứu từ khóa hiệu quả', 'included' => true],
                    ['name' => 'On-page SEO', 'description' => 'Tối ưu nội dung website', 'included' => true],
                    ['name' => 'Link Building', 'description' => 'Xây dựng backlink chất lượng', 'included' => true],
                    ['name' => 'Google Ads', 'description' => 'Quản lý quảng cáo Google', 'included' => true],
                    ['name' => 'Facebook Ads', 'description' => 'Quảng cáo Facebook hiệu quả', 'included' => false],
                ],
                'is_featured' => false,
                'order' => 4,
            ],
            [
                'name' => 'Thiết kế Logo & Bộ nhận diện thương hiệu',
                'slug' => 'thiet-ke-logo-bo-nhan-dien-thuong-hieu',
                'category' => 'design',
                'price' => 5000000,
                'duration' => '2-weeks',
                'short_description' => 'Thiết kế logo độc đáo và bộ nhận diện thương hiệu hoàn chỉnh cho doanh nghiệp.',
                'description' => $this->getBrandingDescription(),
                'features' => [
                    ['name' => 'Logo Design', 'description' => '3-5 concept logo', 'included' => true],
                    ['name' => 'Business Card', 'description' => 'Thiết kế name card', 'included' => true],
                    ['name' => 'Letterhead', 'description' => 'Thiết kế tiêu đề thư', 'included' => true],
                    ['name' => 'Brand Guidelines', 'description' => 'Hướng dẫn sử dụng thương hiệu', 'included' => true],
                    ['name' => 'Social Media Kit', 'description' => 'Template mạng xã hội', 'included' => false],
                ],
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'Tư vấn Chuyển đổi số',
                'slug' => 'tu-van-chuyen-doi-so',
                'category' => 'consulting',
                'price' => null, // Liên hệ
                'duration' => '1-month',
                'short_description' => 'Tư vấn chiến lược chuyển đổi số và tối ưu quy trình kinh doanh bằng công nghệ.',
                'description' => $this->getConsultingDescription(),
                'features' => [
                    ['name' => 'Phân tích hiện trạng', 'description' => 'Đánh giá quy trình hiện tại', 'included' => true],
                    ['name' => 'Lộ trình chuyển đổi', 'description' => 'Kế hoạch chi tiết từng bước', 'included' => true],
                    ['name' => 'Đào tạo nhân viên', 'description' => 'Training sử dụng công cụ mới', 'included' => true],
                    ['name' => 'Hỗ trợ triển khai', 'description' => 'Đồng hành trong quá trình', 'included' => true],
                    ['name' => 'Maintenance 6 tháng', 'description' => 'Bảo trì và hỗ trợ', 'included' => false],
                ],
                'is_featured' => false,
                'order' => 6,
            ],
            [
                'name' => 'Bảo trì & Hỗ trợ Website',
                'slug' => 'bao-tri-ho-tro-website',
                'category' => 'maintenance',
                'price' => 2000000,
                'duration' => 'ongoing',
                'short_description' => 'Dịch vụ bảo trì, cập nhật và hỗ trợ kỹ thuật website 24/7.',
                'description' => $this->getMaintenanceDescription(),
                'features' => [
                    ['name' => 'Backup hàng ngày', 'description' => 'Sao lưu dữ liệu tự động', 'included' => true],
                    ['name' => 'Security Update', 'description' => 'Cập nhật bảo mật định kỳ', 'included' => true],
                    ['name' => 'Performance Monitor', 'description' => 'Giám sát hiệu suất website', 'included' => true],
                    ['name' => 'Content Update', 'description' => 'Cập nhật nội dung theo yêu cầu', 'included' => true],
                    ['name' => 'Priority Support', 'description' => 'Hỗ trợ ưu tiên 24/7', 'included' => false],
                ],
                'is_featured' => false,
                'order' => 7,
            ],
            [
                'name' => 'Hệ thống CRM quản lý khách hàng',
                'slug' => 'he-thong-crm-quan-ly-khach-hang',
                'category' => 'web-development',
                'price' => 25000000,
                'duration' => '2-3-months',
                'short_description' => 'Phát triển hệ thống CRM tùy chỉnh để quản lý khách hàng và quy trình bán hàng.',
                'description' => $this->getCRMDescription(),
                'features' => [
                    ['name' => 'Quản lý Lead', 'description' => 'Theo dõi khách hàng tiềm năng', 'included' => true],
                    ['name' => 'Sales Pipeline', 'description' => 'Quy trình bán hàng rõ ràng', 'included' => true],
                    ['name' => 'Email Marketing', 'description' => 'Gửi email tự động', 'included' => true],
                    ['name' => 'Báo cáo Analytics', 'description' => 'Thống kê chi tiết', 'included' => true],
                    ['name' => 'Mobile App', 'description' => 'Ứng dụng di động', 'included' => false],
                ],
                'is_featured' => false,
                'order' => 8,
            ],
        ];

        foreach ($servicesData as $data) {
            Service::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'category' => $data['category'],
                'price' => $data['price'],
                'duration' => $data['duration'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'features' => $data['features'],
                'is_featured' => $data['is_featured'],
                'order' => $data['order'],
                'status' => 'active',
                'seo_title' => $data['name'] . ' - Dịch vụ chuyên nghiệp',
                'seo_description' => $this->generateSeoDescription($data['short_description']),
                'meta_keywords' => $this->generateKeywords($data['name'], $data['category']),
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
     * Generate meta keywords
     */
    private function generateKeywords(string $name, string $category): string
    {
        $baseKeywords = [
            'web-development' => 'thiết kế website, phát triển web, lập trình website',
            'mobile-app' => 'ứng dụng mobile, app ios, app android, phát triển app',
            'ecommerce' => 'website bán hàng, thương mại điện tử, shop online',
            'seo-marketing' => 'seo, digital marketing, quảng cáo online, google ads',
            'design' => 'thiết kế logo, branding, nhận diện thương hiệu',
            'consulting' => 'tư vấn, chuyển đổi số, digital transformation',
            'maintenance' => 'bảo trì website, hỗ trợ kỹ thuật, maintenance',
        ];

        $categoryKeywords = $baseKeywords[$category] ?? '';
        return $name . ', ' . $categoryKeywords . ', dịch vụ chuyên nghiệp, giá tốt';
    }

    // Description methods for each service...
    private function getWebDesignDescription(): string
    {
        return '<p>Dịch vụ thiết kế website chuyên nghiệp với giao diện hiện đại, responsive và tối ưu SEO. Chúng tôi tạo ra những website không chỉ đẹp mắt mà còn hiệu quả trong việc thu hút và chuyển đổi khách hàng.</p>

<h3>Quy trình thiết kế</h3>
<ul>
<li>Phân tích yêu cầu và mục tiêu kinh doanh</li>
<li>Thiết kế wireframe và mockup</li>
<li>Phát triển frontend responsive</li>
<li>Tích hợp CMS và tối ưu SEO</li>
<li>Testing và launch website</li>
</ul>

<h3>Công nghệ sử dụng</h3>
<ul>
<li>HTML5, CSS3, JavaScript ES6+</li>
<li>Laravel, PHP 8+</li>
<li>MySQL, Redis</li>
<li>Bootstrap, Tailwind CSS</li>
</ul>';
    }

    private function getMobileAppDescription(): string
    {
        return '<p>Phát triển ứng dụng mobile native và cross-platform với hiệu suất cao và trải nghiệm người dùng tuyệt vời. Hỗ trợ cả iOS và Android với codebase tối ưu.</p>

<h3>Tính năng chính</h3>
<ul>
<li>Giao diện native cho từng platform</li>
<li>Tích hợp API và database</li>
<li>Push notification và offline mode</li>
<li>Payment gateway integration</li>
<li>Analytics và crash reporting</li>
</ul>

<h3>Công nghệ</h3>
<ul>
<li>React Native / Flutter</li>
<li>Swift (iOS), Kotlin (Android)</li>
<li>Firebase, AWS</li>
<li>RESTful API, GraphQL</li>
</ul>';
    }

    private function getEcommerceDescription(): string
    {
        return '<p>Xây dựng hệ thống thương mại điện tử hoàn chỉnh với đầy đủ tính năng quản lý sản phẩm, đơn hàng, thanh toán và marketing.</p>

<h3>Tính năng nổi bật</h3>
<ul>
<li>Catalog sản phẩm với search và filter</li>
<li>Shopping cart và checkout process</li>
<li>Multiple payment methods</li>
<li>Order management system</li>
<li>Inventory management</li>
<li>Customer management</li>
<li>Promotion và discount system</li>
</ul>';
    }

    private function getSEODescription(): string
    {
        return '<p>Dịch vụ SEO và Digital Marketing toàn diện giúp website của bạn đạt thứ hạng cao trên Google và tăng traffic chất lượng.</p>

<h3>Chiến lược SEO</h3>
<ul>
<li>Technical SEO audit</li>
<li>Keyword research và content strategy</li>
<li>On-page và off-page optimization</li>
<li>Local SEO cho doanh nghiệp địa phương</li>
<li>Link building chất lượng cao</li>
</ul>';
    }

    private function getBrandingDescription(): string
    {
        return '<p>Thiết kế bộ nhận diện thương hiệu hoàn chỉnh từ logo đến các ấn phẩm marketing, tạo dựng hình ảnh chuyên nghiệp và nhất quán.</p>

<h3>Deliverables</h3>
<ul>
<li>Logo design với nhiều variations</li>
<li>Color palette và typography</li>
<li>Business stationery design</li>
<li>Brand guidelines document</li>
<li>Social media templates</li>
</ul>';
    }

    private function getConsultingDescription(): string
    {
        return '<p>Tư vấn chuyển đổi số toàn diện, giúp doanh nghiệp tối ưu hóa quy trình và nâng cao hiệu quả kinh doanh thông qua công nghệ.</p>

<h3>Phạm vi tư vấn</h3>
<ul>
<li>Digital strategy development</li>
<li>Process automation</li>
<li>Technology stack selection</li>
<li>Change management</li>
<li>ROI measurement</li>
</ul>';
    }

    private function getMaintenanceDescription(): string
    {
        return '<p>Dịch vụ bảo trì website chuyên nghiệp đảm bảo website luôn hoạt động ổn định, bảo mật và cập nhật.</p>

<h3>Dịch vụ bao gồm</h3>
<ul>
<li>Daily backup và monitoring</li>
<li>Security updates và patches</li>
<li>Performance optimization</li>
<li>Content updates theo yêu cầu</li>
<li>Technical support 24/7</li>
</ul>';
    }

    private function getCRMDescription(): string
    {
        return '<p>Hệ thống CRM tùy chỉnh giúp quản lý khách hàng hiệu quả, tự động hóa quy trình bán hàng và tăng doanh thu.</p>

<h3>Modules chính</h3>
<ul>
<li>Lead management và scoring</li>
<li>Contact và account management</li>
<li>Sales pipeline tracking</li>
<li>Email marketing automation</li>
<li>Reporting và analytics</li>
</ul>';
    }
}
