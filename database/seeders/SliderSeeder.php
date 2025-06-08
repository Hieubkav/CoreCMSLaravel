<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🖼️ Tạo dữ liệu slider banner...');

        $sliders = [
            [
                'title' => 'Khóa học làm bánh từ cơ bản đến chuyên nghiệp',
                'description' => 'Tham gia khóa học làm bánh toàn diện, từ những kỹ thuật cơ bản đến các món bánh phức tạp. Phù hợp cho mọi đối tượng từ người mới bắt đầu đến chuyên gia.',
                'image_link' => 'sliders/baking-course-professional.webp',
                'link' => '/khoa-hoc',
                'alt_text' => 'Khóa học làm bánh chuyên nghiệp',
                'status' => 'active',
                'order' => 1,
            ],
            [
                'title' => 'Học làm bánh Pháp cổ điển',
                'description' => 'Khám phá nghệ thuật làm bánh Pháp với các công thức cổ điển như Croissant, Macaron, Éclair. Được hướng dẫn bởi chuyên gia có kinh nghiệm.',
                'image_link' => 'sliders/french-pastry-classic.webp',
                'link' => '/khoa-hoc/banh-phap-co-dien',
                'alt_text' => 'Học làm bánh Pháp cổ điển',
                'status' => 'active',
                'order' => 2,
            ],
            [
                'title' => 'Giảng viên chuyên nghiệp',
                'description' => 'Học từ những chuyên gia có nhiều năm kinh nghiệm trong lĩnh vực làm bánh. Được hướng dẫn tận tình từ cơ bản đến nâng cao.',
                'image_link' => 'sliders/professional-baking-instructor.webp',
                'link' => '/giang-vien',
                'alt_text' => 'Đội ngũ giảng viên làm bánh chuyên nghiệp',
                'status' => 'active',
                'order' => 3,
            ],
            [
                'title' => 'Thực hành với công thức thực tế',
                'description' => 'Mỗi khóa học đều có các công thức thực tế giúp bạn áp dụng kiến thức đã học để tạo ra những chiếc bánh hoàn hảo.',
                'image_link' => 'sliders/real-baking-recipes.webp',
                'link' => '/cong-thuc-banh',
                'alt_text' => 'Công thức làm bánh thực tế',
                'status' => 'active',
                'order' => 4,
            ],
            [
                'title' => 'Hỗ trợ học viên 24/7',
                'description' => 'Đội ngũ hỗ trợ luôn sẵn sàng giải đáp thắc mắc và hỗ trợ học viên 24/7. Cộng đồng học viên làm bánh năng động và tích cực.',
                'image_link' => 'sliders/baking-support-247.webp',
                'link' => '/lien-he',
                'alt_text' => 'Hỗ trợ học viên làm bánh 24/7',
                'status' => 'active',
                'order' => 5,
            ],
        ];

        foreach ($sliders as $sliderData) {
            Slider::updateOrCreate(
                ['title' => $sliderData['title']],
                $sliderData
            );
        }

        $this->command->info("✅ Đã tạo " . count($sliders) . " slider banner");
    }
}
