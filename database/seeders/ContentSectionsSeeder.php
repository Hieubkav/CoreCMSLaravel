<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\Brand;
use App\Models\FAQ;
use App\Models\Statistic;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\Feature;
use App\Models\Partner;
use App\Models\Schedule;
use App\Models\Timeline;

class ContentSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu mẫu cho Content Sections...');

        $this->createGalleries();
        $this->createBrands();
        $this->createFAQs();
        $this->createStatistics();
        $this->createTestimonials();
        $this->createServices();
        $this->createFeatures();
        $this->createPartners();
        $this->createSchedules();
        $this->createTimelines();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu Content Sections!');
    }

    /**
     * Tạo galleries mẫu
     */
    private function createGalleries()
    {
        $galleries = [
            [
                'name' => 'Sự kiện công ty 2024',
                'slug' => 'su-kien-cong-ty-2024',
                'images' => [
                    'galleries/event-1.jpg',
                    'galleries/event-2.jpg',
                    'galleries/event-3.jpg',
                    'galleries/event-4.jpg'
                ],
                'description' => 'Bộ sưu tập ảnh từ các sự kiện quan trọng của công ty trong năm 2024',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Văn phòng làm việc',
                'slug' => 'van-phong-lam-viec',
                'images' => [
                    'galleries/office-1.jpg',
                    'galleries/office-2.jpg',
                    'galleries/office-3.jpg'
                ],
                'description' => 'Không gian làm việc hiện đại và chuyên nghiệp',
                'status' => 'active',
                'order' => 2
            ]
        ];

        foreach ($galleries as $galleryData) {
            Gallery::firstOrCreate(
                ['slug' => $galleryData['slug']],
                $galleryData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($galleries) . ' galleries');
    }

    /**
     * Tạo brands mẫu
     */
    private function createBrands()
    {
        $brands = [
            [
                'name' => 'Microsoft',
                'slug' => 'microsoft',
                'logo' => 'brands/microsoft-logo.png',
                'website_url' => 'https://microsoft.com',
                'description' => 'Công ty công nghệ hàng đầu thế giới',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Google',
                'slug' => 'google',
                'logo' => 'brands/google-logo.png',
                'website_url' => 'https://google.com',
                'description' => 'Công cụ tìm kiếm và dịch vụ internet',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'logo' => 'brands/apple-logo.png',
                'website_url' => 'https://apple.com',
                'description' => 'Thiết bị điện tử và phần mềm cao cấp',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate(
                ['slug' => $brandData['slug']],
                $brandData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($brands) . ' brands');
    }

    /**
     * Tạo FAQs mẫu
     */
    private function createFAQs()
    {
        $faqs = [
            [
                'question' => 'Làm thế nào để đăng ký tài khoản?',
                'answer' => 'Bạn có thể đăng ký tài khoản bằng cách click vào nút "Đăng ký" ở góc phải màn hình, sau đó điền đầy đủ thông tin cá nhân và xác nhận email.',
                'category' => 'Tài khoản',
                'status' => 'active',
                'order' => 1
            ],
            [
                'question' => 'Tôi quên mật khẩu, phải làm sao?',
                'answer' => 'Bạn có thể reset mật khẩu bằng cách click "Quên mật khẩu" ở trang đăng nhập, nhập email đã đăng ký và làm theo hướng dẫn trong email.',
                'category' => 'Tài khoản',
                'status' => 'active',
                'order' => 2
            ],
            [
                'question' => 'Hệ thống có hỗ trợ thanh toán online không?',
                'answer' => 'Có, chúng tôi hỗ trợ nhiều phương thức thanh toán online như thẻ tín dụng, ví điện tử, chuyển khoản ngân hàng.',
                'category' => 'Thanh toán',
                'status' => 'active',
                'order' => 3
            ],
            [
                'question' => 'Làm sao để liên hệ hỗ trợ kỹ thuật?',
                'answer' => 'Bạn có thể liên hệ qua hotline, email hoặc chat trực tuyến. Đội ngũ hỗ trợ làm việc 24/7.',
                'category' => 'Hỗ trợ',
                'status' => 'active',
                'order' => 4
            ]
        ];

        foreach ($faqs as $faqData) {
            FAQ::firstOrCreate(
                ['question' => $faqData['question']],
                $faqData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($faqs) . ' FAQs');
    }

    /**
     * Tạo statistics mẫu
     */
    private function createStatistics()
    {
        $statistics = [
            [
                'label' => 'Khách hàng hài lòng',
                'value' => '1500',
                'icon' => 'fas fa-users',
                'description' => 'Số lượng khách hàng tin tưởng và sử dụng dịch vụ',
                'animation_enabled' => true,
                'status' => 'active',
                'order' => 1
            ],
            [
                'label' => 'Dự án hoàn thành',
                'value' => '250',
                'icon' => 'fas fa-trophy',
                'description' => 'Các dự án đã triển khai thành công',
                'animation_enabled' => true,
                'status' => 'active',
                'order' => 2
            ],
            [
                'label' => 'Năm kinh nghiệm',
                'value' => '10',
                'icon' => 'fas fa-clock',
                'description' => 'Kinh nghiệm trong lĩnh vực công nghệ',
                'animation_enabled' => true,
                'status' => 'active',
                'order' => 3
            ],
            [
                'label' => 'Đánh giá 5 sao',
                'value' => '98%',
                'icon' => 'fas fa-star',
                'description' => 'Tỷ lệ khách hàng đánh giá 5 sao',
                'animation_enabled' => false,
                'status' => 'active',
                'order' => 4
            ]
        ];

        foreach ($statistics as $statisticData) {
            Statistic::firstOrCreate(
                ['label' => $statisticData['label']],
                $statisticData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($statistics) . ' statistics');
    }

    /**
     * Tạo testimonials mẫu
     */
    private function createTestimonials()
    {
        $testimonials = [
            [
                'customer_name' => 'Nguyễn Văn An',
                'review_text' => 'Dịch vụ rất chuyên nghiệp và hiệu quả. Đội ngũ hỗ trợ nhiệt tình, giải quyết vấn đề nhanh chóng. Tôi rất hài lòng với chất lượng sản phẩm.',
                'rating' => 5,
                'company' => 'Công ty ABC',
                'position' => 'Giám đốc IT',
                'status' => 'active',
                'order' => 1
            ],
            [
                'customer_name' => 'Trần Thị Bình',
                'review_text' => 'Sản phẩm vượt ngoài mong đợi của tôi. Giao diện thân thiện, dễ sử dụng và tính năng đầy đủ. Chắc chắn sẽ giới thiệu cho bạn bè.',
                'rating' => 5,
                'company' => 'Startup XYZ',
                'position' => 'CEO',
                'status' => 'active',
                'order' => 2
            ],
            [
                'customer_name' => 'Lê Minh Cường',
                'review_text' => 'Hỗ trợ khách hàng tuyệt vời, phản hồi nhanh và giải quyết triệt để. Giá cả hợp lý so với chất lượng nhận được.',
                'rating' => 4,
                'company' => 'Doanh nghiệp DEF',
                'position' => 'Trưởng phòng Marketing',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::firstOrCreate(
                ['customer_name' => $testimonialData['customer_name']],
                $testimonialData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($testimonials) . ' testimonials');
    }

    /**
     * Tạo services mẫu
     */
    private function createServices()
    {
        $services = [
            [
                'name' => 'Phát triển Website',
                'slug' => 'phat-trien-website',
                'icon' => 'fas fa-globe',
                'description' => 'Thiết kế và phát triển website chuyên nghiệp, responsive trên mọi thiết bị với công nghệ hiện đại.',
                'link' => '/dich-vu/phat-trien-website',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Ứng dụng Mobile',
                'slug' => 'ung-dung-mobile',
                'icon' => 'fas fa-mobile-alt',
                'description' => 'Phát triển ứng dụng di động iOS và Android với trải nghiệm người dùng tối ưu.',
                'link' => '/dich-vu/ung-dung-mobile',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Tư vấn Công nghệ',
                'slug' => 'tu-van-cong-nghe',
                'icon' => 'fas fa-users',
                'description' => 'Tư vấn giải pháp công nghệ phù hợp với nhu cầu và ngân sách của doanh nghiệp.',
                'link' => '/dich-vu/tu-van-cong-nghe',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($services) . ' services');
    }

    /**
     * Tạo features mẫu
     */
    private function createFeatures()
    {
        $features = [
            [
                'name' => 'Bảo mật cao',
                'slug' => 'bao-mat-cao',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Hệ thống bảo mật đa lớp với mã hóa SSL và xác thực 2 yếu tố.',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Hiệu suất tối ưu',
                'slug' => 'hieu-suat-toi-uu',
                'icon' => 'fas fa-rocket',
                'description' => 'Tối ưu hóa tốc độ tải trang và hiệu suất hệ thống.',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Responsive Design',
                'slug' => 'responsive-design',
                'icon' => 'fas fa-mobile-alt',
                'description' => 'Giao diện tự động điều chỉnh trên mọi thiết bị và kích thước màn hình.',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($features as $featureData) {
            Feature::firstOrCreate(
                ['slug' => $featureData['slug']],
                $featureData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($features) . ' features');
    }

    /**
     * Tạo partners mẫu
     */
    private function createPartners()
    {
        $partners = [
            [
                'name' => 'Vietcombank',
                'slug' => 'vietcombank',
                'logo' => 'partners/vietcombank-logo.png',
                'description' => 'Ngân hàng thương mại cổ phần Ngoại thương Việt Nam',
                'website_url' => 'https://vietcombank.com.vn',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'FPT Software',
                'slug' => 'fpt-software',
                'logo' => 'partners/fpt-logo.png',
                'description' => 'Công ty phần mềm hàng đầu Việt Nam',
                'website_url' => 'https://fpt-software.com',
                'status' => 'active',
                'order' => 2
            ]
        ];

        foreach ($partners as $partnerData) {
            Partner::firstOrCreate(
                ['slug' => $partnerData['slug']],
                $partnerData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($partners) . ' partners');
    }

    /**
     * Tạo schedules mẫu
     */
    private function createSchedules()
    {
        $schedules = [
            [
                'name' => 'Lịch làm việc mùa hè 2024',
                'slug' => 'lich-lam-viec-mua-he-2024',
                'image' => 'schedules/summer-schedule.jpg',
                'description' => 'Lịch làm việc điều chỉnh cho mùa hè với giờ giấc linh hoạt hơn.',
                'is_active' => true,
                'status' => 'active',
                'order' => 1
            ]
        ];

        foreach ($schedules as $scheduleData) {
            Schedule::firstOrCreate(
                ['slug' => $scheduleData['slug']],
                $scheduleData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($schedules) . ' schedules');
    }

    /**
     * Tạo timelines mẫu
     */
    private function createTimelines()
    {
        $timelines = [
            [
                'title' => 'Thành lập công ty',
                'start_date' => '2014-01-01',
                'end_date' => '2014-01-31',
                'image' => 'timelines/founding.jpg',
                'description' => 'Công ty được thành lập với đội ngũ 5 người, bắt đầu hành trình phát triển.',
                'status' => 'active',
                'order' => 1
            ],
            [
                'title' => 'Mở rộng thị trường',
                'start_date' => '2018-06-01',
                'end_date' => '2018-12-31',
                'image' => 'timelines/expansion.jpg',
                'description' => 'Mở rộng hoạt động ra các tỉnh thành, tăng quy mô đội ngũ lên 50 người.',
                'status' => 'active',
                'order' => 2
            ],
            [
                'title' => 'Chuyển đổi số',
                'start_date' => '2020-01-01',
                'end_date' => null,
                'image' => 'timelines/digital-transformation.jpg',
                'description' => 'Bắt đầu quá trình chuyển đổi số toàn diện, áp dụng công nghệ AI và Cloud.',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($timelines as $timelineData) {
            Timeline::firstOrCreate(
                ['title' => $timelineData['title']],
                $timelineData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($timelines) . ' timelines');
    }
}
