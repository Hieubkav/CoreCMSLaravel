<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Bắt đầu tạo dữ liệu mẫu nhân viên...');

        $staffData = [
            [
                'name' => 'Nguyễn Văn An',
                'slug' => 'nguyen-van-an',
                'position' => 'Giám đốc',
                'email' => 'giamdoc@company.vn',
                'phone' => '0901234567',
                'description' => '<p>Ông Nguyễn Văn An là Giám đốc điều hành với hơn 15 năm kinh nghiệm trong lĩnh vực quản lý doanh nghiệp. Ông đã dẫn dắt công ty phát triển mạnh mẽ và đạt được nhiều thành tựu quan trọng.</p><p>Với tầm nhìn chiến lược và khả năng lãnh đạo xuất sắc, ông An luôn hướng tới việc xây dựng một môi trường làm việc tích cực và hiệu quả.</p>',
                'social_links' => [
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com/in/nguyen-van-an'],
                    ['platform' => 'email', 'url' => 'mailto:giamdoc@company.vn'],
                ],
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Trần Thị Bình',
                'slug' => 'tran-thi-binh',
                'position' => 'Phó Giám đốc',
                'email' => 'phogiamdoc@company.vn',
                'phone' => '0901234568',
                'description' => '<p>Bà Trần Thị Bình là Phó Giám đốc phụ trách mảng kinh doanh với 12 năm kinh nghiệm. Bà có khả năng phân tích thị trường sắc bén và đã đóng góp tích cực vào sự phát triển của công ty.</p><p>Bà Bình luôn tập trung vào việc nâng cao chất lượng dịch vụ và mở rộng thị trường.</p>',
                'social_links' => [
                    ['platform' => 'facebook', 'url' => 'https://facebook.com/tran.thi.binh'],
                    ['platform' => 'email', 'url' => 'mailto:phogiamdoc@company.vn'],
                ],
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Lê Minh Cường',
                'slug' => 'le-minh-cuong',
                'position' => 'Trưởng phòng Kỹ thuật',
                'email' => 'kythuat@company.vn',
                'phone' => '0901234569',
                'description' => '<p>Anh Lê Minh Cường là Trưởng phòng Kỹ thuật với chuyên môn sâu về công nghệ thông tin. Anh có 10 năm kinh nghiệm trong việc phát triển và quản lý các dự án công nghệ.</p><p>Anh Cường luôn cập nhật những xu hướng công nghệ mới nhất để áp dụng vào công việc.</p>',
                'social_links' => [
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com/in/le-minh-cuong'],
                    ['platform' => 'github', 'url' => 'https://github.com/leminhcuong'],
                ],
                'status' => 'active',
                'order' => 3,
            ],
            [
                'name' => 'Phạm Thị Dung',
                'slug' => 'pham-thi-dung',
                'position' => 'Trưởng phòng Nhân sự',
                'email' => 'nhansu@company.vn',
                'phone' => '0901234570',
                'description' => '<p>Bà Phạm Thị Dung là Trưởng phòng Nhân sự với 8 năm kinh nghiệm trong lĩnh vực quản lý nhân sự. Bà chuyên về tuyển dụng, đào tạo và phát triển nguồn nhân lực.</p><p>Bà Dung luôn tạo ra môi trường làm việc thân thiện và hỗ trợ nhân viên phát triển.</p>',
                'social_links' => [
                    ['platform' => 'facebook', 'url' => 'https://facebook.com/pham.thi.dung'],
                    ['platform' => 'email', 'url' => 'mailto:nhansu@company.vn'],
                ],
                'status' => 'active',
                'order' => 4,
            ],
            [
                'name' => 'Hoàng Văn Em',
                'slug' => 'hoang-van-em',
                'position' => 'Chuyên viên Marketing',
                'email' => 'marketing@company.vn',
                'phone' => '0901234571',
                'description' => '<p>Anh Hoàng Văn Em là Chuyên viên Marketing với 6 năm kinh nghiệm trong lĩnh vực truyền thông và quảng cáo. Anh có khả năng sáng tạo nội dung và quản lý các chiến dịch marketing hiệu quả.</p><p>Anh Em luôn theo dõi xu hướng marketing mới và áp dụng vào thực tế.</p>',
                'social_links' => [
                    ['platform' => 'instagram', 'url' => 'https://instagram.com/hoangvanem'],
                    ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@hoangvanem'],
                    ['platform' => 'email', 'url' => 'mailto:marketing@company.vn'],
                ],
                'status' => 'active',
                'order' => 5,
            ],
            [
                'name' => 'Vũ Thị Giang',
                'slug' => 'vu-thi-giang',
                'position' => 'Chuyên viên Kế toán',
                'email' => 'ketoan@company.vn',
                'phone' => '0901234572',
                'description' => '<p>Chị Vũ Thị Giang là Chuyên viên Kế toán với 7 năm kinh nghiệm trong lĩnh vực tài chính kế toán. Chị có chuyên môn vững vàng về các quy định tài chính và thuế.</p><p>Chị Giang luôn đảm bảo tính chính xác và minh bạch trong công việc.</p>',
                'social_links' => [
                    ['platform' => 'email', 'url' => 'mailto:ketoan@company.vn'],
                    ['platform' => 'zalo', 'url' => 'https://zalo.me/vuthigiang'],
                ],
                'status' => 'active',
                'order' => 6,
            ],
            [
                'name' => 'Đỗ Minh Hải',
                'slug' => 'do-minh-hai',
                'position' => 'Nhân viên Kinh doanh',
                'email' => 'kinhdoanh@company.vn',
                'phone' => '0901234573',
                'description' => '<p>Anh Đỗ Minh Hải là Nhân viên Kinh doanh với 4 năm kinh nghiệm trong lĩnh vực bán hàng và chăm sóc khách hàng. Anh có kỹ năng giao tiếp tốt và hiểu rõ nhu cầu khách hàng.</p><p>Anh Hải luôn nỗ lực để mang lại sự hài lòng tối đa cho khách hàng.</p>',
                'social_links' => [
                    ['platform' => 'facebook', 'url' => 'https://facebook.com/do.minh.hai'],
                    ['platform' => 'email', 'url' => 'mailto:kinhdoanh@company.vn'],
                ],
                'status' => 'active',
                'order' => 7,
            ],
            [
                'name' => 'Ngô Thị Lan',
                'slug' => 'ngo-thi-lan',
                'position' => 'Thực tập sinh',
                'email' => 'thuctapsinh@company.vn',
                'phone' => '0901234574',
                'description' => '<p>Chị Ngô Thị Lan là Thực tập sinh năng động và ham học hỏi. Chị đang theo học chuyên ngành Quản trị Kinh doanh và mong muốn phát triển sự nghiệp trong lĩnh vực này.</p><p>Chị Lan luôn tích cực tham gia các hoạt động và học hỏi kinh nghiệm từ các đồng nghiệp.</p>',
                'social_links' => [
                    ['platform' => 'instagram', 'url' => 'https://instagram.com/ngothilan'],
                    ['platform' => 'email', 'url' => 'mailto:thuctapsinh@company.vn'],
                ],
                'status' => 'active',
                'order' => 8,
            ],
        ];

        foreach ($staffData as $data) {
            Staff::create($data);
        }

        $this->command->info('✅ Đã tạo ' . count($staffData) . ' nhân viên mẫu');
        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu nhân viên!');
    }
}
