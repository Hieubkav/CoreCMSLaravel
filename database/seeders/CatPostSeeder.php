<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatPost;

class CatPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📂 Tạo dữ liệu danh mục bài viết...');

        $categories = [
            [
                'name' => 'Tin tức',
                'slug' => 'tin-tuc',
                'seo_title' => 'Tin tức mới nhất về làm bánh và khóa học',
                'seo_description' => 'Cập nhật tin tức mới nhất về làm bánh, xu hướng bánh ngọt và các khóa học tại VBA Vũ Phúc',
                'description' => 'Tin tức và cập nhật mới nhất về làm bánh và các khóa học',
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Hướng dẫn',
                'slug' => 'huong-dan',
                'seo_title' => 'Hướng dẫn làm bánh từ cơ bản đến nâng cao',
                'seo_description' => 'Các bài hướng dẫn chi tiết về làm bánh từ cơ bản đến nâng cao',
                'description' => 'Các bài hướng dẫn chi tiết về kỹ thuật làm bánh',
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Kinh nghiệm',
                'slug' => 'kinh-nghiem',
                'seo_title' => 'Chia sẻ kinh nghiệm học làm bánh',
                'seo_description' => 'Chia sẻ kinh nghiệm và mẹo hay khi học làm bánh',
                'description' => 'Chia sẻ kinh nghiệm và mẹo hay trong việc học làm bánh',
                'status' => 'active',
                'order' => 3,
            ],
            [
                'name' => 'Công thức bánh',
                'slug' => 'cong-thuc-banh',
                'seo_title' => 'Công thức làm bánh chi tiết',
                'seo_description' => 'Các công thức làm bánh chi tiết và dễ thực hiện tại nhà',
                'description' => 'Các công thức làm bánh chi tiết và dễ thực hiện',
                'status' => 'active',
                'order' => 4,
            ],
            [
                'name' => 'Mẹo làm bánh',
                'slug' => 'meo-lam-banh',
                'seo_title' => 'Mẹo hay khi làm bánh',
                'seo_description' => 'Các mẹo hay và bí quyết khi làm bánh để có sản phẩm hoàn hảo',
                'description' => 'Các mẹo hay và bí quyết khi làm bánh',
                'status' => 'active',
                'order' => 5,
            ],
            [
                'name' => 'Sự kiện',
                'slug' => 'su-kien',
                'seo_title' => 'Sự kiện và workshop làm bánh',
                'seo_description' => 'Thông tin về các sự kiện, workshop và hội thảo làm bánh',
                'description' => 'Thông tin về các sự kiện và workshop làm bánh',
                'status' => 'active',
                'order' => 6,
            ],
            [
                'name' => 'Dụng cụ làm bánh',
                'slug' => 'dung-cu-lam-banh',
                'seo_title' => 'Dụng cụ và nguyên liệu làm bánh',
                'seo_description' => 'Hướng dẫn chọn dụng cụ và nguyên liệu làm bánh chất lượng',
                'description' => 'Hướng dẫn về dụng cụ và nguyên liệu làm bánh',
                'status' => 'active',
                'order' => 7,
            ],
            [
                'name' => 'Video học tập',
                'slug' => 'video-hoc-tap',
                'seo_title' => 'Video học làm bánh miễn phí',
                'seo_description' => 'Thư viện video học làm bánh miễn phí từ cơ bản đến nâng cao',
                'description' => 'Thư viện video học làm bánh miễn phí',
                'status' => 'active',
                'order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            CatPost::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        $this->command->info("✅ Đã tạo " . count($categories) . " danh mục bài viết");
    }
}
