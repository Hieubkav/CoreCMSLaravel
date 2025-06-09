<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\CatPost;

class BlogModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu mẫu cho Blog Module...');

        // Tạo categories nếu chưa có
        $this->createCategories();

        // Tạo posts mẫu
        $this->createSamplePosts();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu Blog Module!');
    }

    /**
     * Tạo categories mẫu
     */
    private function createCategories()
    {
        $categories = [
            [
                'name' => 'Tin tức',
                'slug' => 'tin-tuc',
                'description' => 'Tin tức và cập nhật mới nhất',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Hướng dẫn',
                'slug' => 'huong-dan',
                'description' => 'Các bài hướng dẫn chi tiết',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Kinh nghiệm',
                'slug' => 'kinh-nghiem',
                'description' => 'Chia sẻ kinh nghiệm thực tế',
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Chính sách',
                'slug' => 'chinh-sach',
                'description' => 'Các chính sách và quy định',
                'status' => 'active',
                'order' => 4
            ]
        ];

        foreach ($categories as $categoryData) {
            CatPost::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($categories) . ' categories');
    }

    /**
     * Tạo posts mẫu
     */
    private function createSamplePosts()
    {
        $categories = CatPost::all();

        if ($categories->isEmpty()) {
            $this->command->warn('⚠️ Không có categories để tạo posts');
            return;
        }

        $posts = [
            // Tin tức
            [
                'title' => 'Cập nhật tính năng mới trong hệ thống',
                'slug' => 'cap-nhat-tinh-nang-moi-trong-he-thong',
                'content' => $this->getNewsContent(),
                'excerpt' => 'Hệ thống vừa được cập nhật với nhiều tính năng mới giúp cải thiện trải nghiệm người dùng.',
                'post_type' => 'news',
                'author_name' => 'Admin',
                'tags' => ['cập nhật', 'tính năng', 'hệ thống'],
                'is_featured' => true,
                'status' => 'active',
                'published_at' => now()->subDays(1),
                'view_count' => rand(50, 200)
            ],
            [
                'title' => 'Thông báo bảo trì hệ thống định kỳ',
                'slug' => 'thong-bao-bao-tri-he-thong-dinh-ky',
                'content' => $this->getMaintenanceContent(),
                'excerpt' => 'Hệ thống sẽ được bảo trì định kỳ vào cuối tuần để đảm bảo hiệu suất tối ưu.',
                'post_type' => 'news',
                'author_name' => 'Kỹ thuật',
                'tags' => ['bảo trì', 'thông báo', 'hệ thống'],
                'is_featured' => false,
                'status' => 'active',
                'published_at' => now()->subDays(3),
                'view_count' => rand(30, 100)
            ],
            // Hướng dẫn
            [
                'title' => 'Hướng dẫn sử dụng tính năng tìm kiếm nâng cao',
                'slug' => 'huong-dan-su-dung-tinh-nang-tim-kiem-nang-cao',
                'content' => $this->getGuideContent(),
                'excerpt' => 'Tìm hiểu cách sử dụng tính năng tìm kiếm nâng cao để tìm thông tin nhanh chóng và chính xác.',
                'post_type' => 'blog',
                'author_name' => 'Hỗ trợ',
                'tags' => ['hướng dẫn', 'tìm kiếm', 'tips'],
                'is_featured' => true,
                'status' => 'active',
                'published_at' => now()->subDays(5),
                'view_count' => rand(100, 300)
            ],
            [
                'title' => 'Cách tối ưu hóa hiệu suất làm việc',
                'slug' => 'cach-toi-uu-hoa-hieu-suat-lam-viec',
                'content' => $this->getProductivityContent(),
                'excerpt' => 'Những mẹo và thủ thuật giúp bạn làm việc hiệu quả hơn với hệ thống.',
                'post_type' => 'blog',
                'author_name' => 'Chuyên gia',
                'tags' => ['hiệu suất', 'tối ưu', 'làm việc'],
                'is_featured' => false,
                'status' => 'active',
                'published_at' => now()->subWeek(),
                'view_count' => rand(80, 250)
            ],
            // Chính sách
            [
                'title' => 'Chính sách bảo mật thông tin',
                'slug' => 'chinh-sach-bao-mat-thong-tin',
                'content' => $this->getPolicyContent(),
                'excerpt' => 'Quy định về bảo mật và xử lý thông tin cá nhân trong hệ thống.',
                'post_type' => 'policy',
                'author_name' => 'Pháp chế',
                'tags' => ['chính sách', 'bảo mật', 'quy định'],
                'is_featured' => false,
                'status' => 'active',
                'published_at' => now()->subWeeks(2),
                'view_count' => rand(20, 80)
            ]
        ];

        foreach ($posts as $postData) {
            // Gán category ngẫu nhiên phù hợp
            $category = $this->getCategoryForPostType($postData['post_type'], $categories);
            $postData['category_id'] = $category->id;

            Post::firstOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($posts) . ' posts mẫu');
    }

    /**
     * Lấy category phù hợp cho post type
     */
    private function getCategoryForPostType(string $postType, $categories)
    {
        switch ($postType) {
            case 'news':
                return $categories->where('slug', 'tin-tuc')->first() ?? $categories->first();
            case 'policy':
                return $categories->where('slug', 'chinh-sach')->first() ?? $categories->first();
            case 'blog':
            default:
                return $categories->where('slug', 'huong-dan')->first() ?? $categories->first();
        }
    }

    /**
     * Content cho tin tức
     */
    private function getNewsContent(): string
    {
        return '<h2>Tính năng mới được cập nhật</h2>
        <p>Chúng tôi vui mừng thông báo về việc cập nhật hệ thống với nhiều tính năng mới nhằm cải thiện trải nghiệm người dùng.</p>

        <h3>Các tính năng chính:</h3>
        <ul>
            <li><strong>Giao diện người dùng được cải thiện:</strong> Thiết kế mới hiện đại và thân thiện hơn</li>
            <li><strong>Tốc độ tải trang nhanh hơn:</strong> Tối ưu hóa hiệu suất lên đến 40%</li>
            <li><strong>Tính năng tìm kiếm nâng cao:</strong> Kết quả chính xác và nhanh chóng hơn</li>
            <li><strong>Hỗ trợ đa thiết bị:</strong> Tương thích hoàn hảo trên mobile và tablet</li>
        </ul>

        <p>Cập nhật này sẽ có hiệu lực ngay lập tức và không ảnh hưởng đến dữ liệu hiện có của bạn.</p>';
    }

    /**
     * Content cho bảo trì
     */
    private function getMaintenanceContent(): string
    {
        return '<h2>Lịch bảo trì hệ thống</h2>
        <p>Để đảm bảo hệ thống hoạt động ổn định và hiệu quả, chúng tôi sẽ thực hiện bảo trì định kỳ.</p>

        <h3>Thời gian bảo trì:</h3>
        <ul>
            <li><strong>Ngày:</strong> Chủ nhật hàng tuần</li>
            <li><strong>Thời gian:</strong> 02:00 - 04:00 sáng</li>
            <li><strong>Thời lượng:</strong> Khoảng 2 giờ</li>
        </ul>

        <h3>Những gì sẽ được thực hiện:</h3>
        <ul>
            <li>Cập nhật bảo mật hệ thống</li>
            <li>Tối ưu hóa cơ sở dữ liệu</li>
            <li>Kiểm tra và sửa lỗi</li>
            <li>Backup dữ liệu</li>
        </ul>

        <p>Trong thời gian bảo trì, hệ thống có thể tạm thời không truy cập được. Chúng tôi xin lỗi vì sự bất tiện này.</p>';
    }

    /**
     * Content cho hướng dẫn
     */
    private function getGuideContent(): string
    {
        return '<h2>Hướng dẫn sử dụng tìm kiếm nâng cao</h2>
        <p>Tính năng tìm kiếm nâng cao giúp bạn tìm thông tin chính xác và nhanh chóng hơn.</p>

        <h3>Cách sử dụng:</h3>
        <ol>
            <li><strong>Tìm kiếm cơ bản:</strong> Nhập từ khóa vào ô tìm kiếm</li>
            <li><strong>Tìm kiếm theo cụm từ:</strong> Đặt cụm từ trong dấu ngoặc kép "cụm từ"</li>
            <li><strong>Loại trừ từ khóa:</strong> Sử dụng dấu trừ -từ_khóa</li>
            <li><strong>Tìm kiếm theo danh mục:</strong> Chọn danh mục cụ thể</li>
        </ol>

        <h3>Mẹo tìm kiếm hiệu quả:</h3>
        <ul>
            <li>Sử dụng từ khóa cụ thể thay vì chung chung</li>
            <li>Kết hợp nhiều từ khóa liên quan</li>
            <li>Sử dụng bộ lọc để thu hẹp kết quả</li>
            <li>Kiểm tra chính tả trước khi tìm kiếm</li>
        </ul>';
    }

    /**
     * Content cho productivity
     */
    private function getProductivityContent(): string
    {
        return '<h2>Tối ưu hóa hiệu suất làm việc</h2>
        <p>Những mẹo và thủ thuật giúp bạn làm việc hiệu quả hơn với hệ thống.</p>

        <h3>Các mẹo hữu ích:</h3>
        <ul>
            <li><strong>Sử dụng phím tắt:</strong> Học các phím tắt để thao tác nhanh hơn</li>
            <li><strong>Tổ chức workspace:</strong> Sắp xếp không gian làm việc khoa học</li>
            <li><strong>Lập kế hoạch:</strong> Lên kế hoạch công việc hàng ngày</li>
            <li><strong>Tận dụng automation:</strong> Tự động hóa các tác vụ lặp lại</li>
        </ul>

        <h3>Công cụ hỗ trợ:</h3>
        <ul>
            <li>Dashboard cá nhân để theo dõi tiến độ</li>
            <li>Notification để nhắc nhở công việc</li>
            <li>Report tự động để đánh giá hiệu suất</li>
            <li>Integration với các tool khác</li>
        </ul>';
    }

    /**
     * Content cho policy
     */
    private function getPolicyContent(): string
    {
        return '<h2>Chính sách bảo mật thông tin</h2>
        <p>Quy định về bảo mật và xử lý thông tin cá nhân trong hệ thống.</p>

        <h3>Nguyên tắc bảo mật:</h3>
        <ul>
            <li><strong>Bảo mật dữ liệu:</strong> Mọi thông tin được mã hóa và bảo vệ</li>
            <li><strong>Quyền truy cập:</strong> Phân quyền rõ ràng theo vai trò</li>
            <li><strong>Audit log:</strong> Ghi lại mọi hoạt động trong hệ thống</li>
            <li><strong>Backup định kỳ:</strong> Sao lưu dữ liệu thường xuyên</li>
        </ul>

        <h3>Trách nhiệm người dùng:</h3>
        <ul>
            <li>Bảo mật thông tin đăng nhập</li>
            <li>Không chia sẻ tài khoản</li>
            <li>Báo cáo ngay khi phát hiện bất thường</li>
            <li>Tuân thủ quy định sử dụng</li>
        </ul>

        <p>Mọi vi phạm về bảo mật sẽ được xử lý nghiêm túc theo quy định.</p>';
    }
}
