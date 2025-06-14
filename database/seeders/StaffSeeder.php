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
        $staffData = [
            [
                'name' => 'Nguyễn Văn An',
                'slug' => 'nguyen-van-an',
                'position' => 'Giám đốc',
                'email' => 'an.nguyen@company.com',
                'phone' => '0901234567',
                'description' => $this->getDirectorDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/nguyen-van-an',
                    'facebook' => 'https://facebook.com/nguyenvanan',
                    'email' => 'an.nguyen@company.com'
                ],
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Trần Thị Bình',
                'slug' => 'tran-thi-binh',
                'position' => 'Trưởng phòng Kỹ thuật',
                'email' => 'binh.tran@company.com',
                'phone' => '0901234568',
                'description' => $this->getTechLeaderDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/tran-thi-binh',
                    'github' => 'https://github.com/tranthibinh',
                    'email' => 'binh.tran@company.com'
                ],
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Lê Minh Cường',
                'slug' => 'le-minh-cuong',
                'position' => 'Senior Developer',
                'email' => 'cuong.le@company.com',
                'phone' => '0901234569',
                'description' => $this->getSeniorDevDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/le-minh-cuong',
                    'github' => 'https://github.com/leminhcuong',
                    'twitter' => 'https://twitter.com/leminhcuong'
                ],
                'status' => 'active',
                'order' => 3,
            ],
            [
                'name' => 'Phạm Thu Dung',
                'slug' => 'pham-thu-dung',
                'position' => 'UI/UX Designer',
                'email' => 'dung.pham@company.com',
                'phone' => '0901234570',
                'description' => $this->getDesignerDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/pham-thu-dung',
                    'behance' => 'https://behance.net/phamthudung',
                    'instagram' => 'https://instagram.com/phamthudung'
                ],
                'status' => 'active',
                'order' => 4,
            ],
            [
                'name' => 'Hoàng Văn Em',
                'slug' => 'hoang-van-em',
                'position' => 'Frontend Developer',
                'email' => 'em.hoang@company.com',
                'phone' => '0901234571',
                'description' => $this->getFrontendDevDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/hoang-van-em',
                    'github' => 'https://github.com/hoangvanem',
                    'codepen' => 'https://codepen.io/hoangvanem'
                ],
                'status' => 'active',
                'order' => 5,
            ],
            [
                'name' => 'Vũ Thị Giang',
                'slug' => 'vu-thi-giang',
                'position' => 'Marketing Manager',
                'email' => 'giang.vu@company.com',
                'phone' => '0901234572',
                'description' => $this->getMarketingDescription(),
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/vu-thi-giang',
                    'facebook' => 'https://facebook.com/vuthigiang',
                    'instagram' => 'https://instagram.com/vuthigiang'
                ],
                'status' => 'active',
                'order' => 6,
            ],
        ];

        foreach ($staffData as $data) {
            Staff::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'position' => $data['position'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'description' => $data['description'],
                'social_links' => $data['social_links'],
                'status' => $data['status'],
                'order' => $data['order'],
                'seo_title' => $data['name'] . ' - ' . $data['position'],
                'seo_description' => $this->generateSeoDescription($data['description']),
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
     * Director description
     */
    private function getDirectorDescription(): string
    {
        return '<p>Với hơn 15 năm kinh nghiệm trong lĩnh vực công nghệ thông tin, anh Nguyễn Văn An đã dẫn dắt công ty phát triển mạnh mẽ và đạt được nhiều thành tựu đáng kể.</p>

<h3>Kinh nghiệm</h3>
<ul>
<li>15+ năm kinh nghiệm quản lý dự án công nghệ</li>
<li>Chuyên gia về chiến lược phát triển sản phẩm</li>
<li>Thạc sĩ Quản trị Kinh doanh - ĐH Kinh tế Quốc dân</li>
</ul>

<h3>Thành tựu</h3>
<ul>
<li>Dẫn dắt công ty tăng trưởng 300% trong 5 năm qua</li>
<li>Triển khai thành công hơn 200 dự án lớn</li>
<li>Giải thưởng "Doanh nhân xuất sắc" năm 2023</li>
</ul>';
    }

    /**
     * Tech leader description
     */
    private function getTechLeaderDescription(): string
    {
        return '<p>Chị Trần Thị Bình là chuyên gia công nghệ với 12 năm kinh nghiệm, chuyên về phát triển ứng dụng web và quản lý đội ngũ kỹ thuật.</p>

<h3>Chuyên môn</h3>
<ul>
<li>Laravel, PHP, MySQL, Redis</li>
<li>Vue.js, React, Node.js</li>
<li>DevOps, AWS, Docker</li>
<li>Quản lý đội ngũ 20+ developers</li>
</ul>

<h3>Dự án tiêu biểu</h3>
<ul>
<li>Hệ thống ERP cho doanh nghiệp 1000+ nhân viên</li>
<li>Platform e-commerce xử lý 10M+ giao dịch/tháng</li>
<li>API gateway phục vụ 50+ microservices</li>
</ul>';
    }

    /**
     * Senior developer description
     */
    private function getSeniorDevDescription(): string
    {
        return '<p>Anh Lê Minh Cường là Senior Developer với 8 năm kinh nghiệm, chuyên sâu về backend development và system architecture.</p>

<h3>Kỹ năng</h3>
<ul>
<li>PHP, Laravel, Symfony</li>
<li>MySQL, PostgreSQL, MongoDB</li>
<li>Redis, Elasticsearch</li>
<li>Microservices Architecture</li>
</ul>

<h3>Đóng góp</h3>
<ul>
<li>Contributor cho Laravel framework</li>
<li>Speaker tại PHP Vietnam Conference</li>
<li>Mentor cho 50+ junior developers</li>
</ul>';
    }

    /**
     * Designer description
     */
    private function getDesignerDescription(): string
    {
        return '<p>Chị Phạm Thu Dung là UI/UX Designer tài năng với 6 năm kinh nghiệm, chuyên tạo ra những trải nghiệm người dùng tuyệt vời.</p>

<h3>Chuyên môn</h3>
<ul>
<li>UI/UX Design, User Research</li>
<li>Figma, Adobe Creative Suite</li>
<li>Prototyping, Wireframing</li>
<li>Design System, Brand Identity</li>
</ul>

<h3>Thành tựu</h3>
<ul>
<li>Thiết kế 100+ website và mobile app</li>
<li>Giải thưởng "Best UI Design" - Vietnam Web Awards</li>
<li>Tăng conversion rate 40% cho các dự án e-commerce</li>
</ul>';
    }

    /**
     * Frontend developer description
     */
    private function getFrontendDevDescription(): string
    {
        return '<p>Anh Hoàng Văn Em là Frontend Developer với 5 năm kinh nghiệm, chuyên về JavaScript và các framework hiện đại.</p>

<h3>Công nghệ</h3>
<ul>
<li>JavaScript, TypeScript</li>
<li>Vue.js, React, Angular</li>
<li>HTML5, CSS3, SASS</li>
<li>Webpack, Vite, npm/yarn</li>
</ul>

<h3>Dự án</h3>
<ul>
<li>SPA cho 20+ doanh nghiệp lớn</li>
<li>PWA với 1M+ người dùng</li>
<li>Component library được sử dụng bởi 10+ team</li>
</ul>';
    }

    /**
     * Marketing manager description
     */
    private function getMarketingDescription(): string
    {
        return '<p>Chị Vũ Thị Giang là Marketing Manager với 7 năm kinh nghiệm, chuyên về digital marketing và growth hacking.</p>

<h3>Chuyên môn</h3>
<ul>
<li>Digital Marketing, SEO/SEM</li>
<li>Social Media Marketing</li>
<li>Content Marketing, Email Marketing</li>
<li>Google Analytics, Facebook Ads</li>
</ul>

<h3>Kết quả</h3>
<ul>
<li>Tăng traffic website 500% trong 2 năm</li>
<li>ROI từ digital ads đạt 300%</li>
<li>Xây dựng community 50K+ followers</li>
</ul>';
    }
}
