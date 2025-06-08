<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🤝 Tạo dữ liệu đối tác...');

        $partners = [
            [
                'name' => 'Puratos Vietnam',
                'logo_link' => '/images/partners/puratos.jpg',
                'website_link' => 'https://puratos.com.vn',
                'description' => 'Nhà cung cấp nguyên liệu làm bánh hàng đầu thế giới',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Anchor Food Professionals',
                'logo_link' => '/images/partners/anchor.jpg',
                'website_link' => 'https://anchorfoodprofessionals.com',
                'description' => 'Thương hiệu sữa và kem chuyên dụng cho làm bánh',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Lesaffre Vietnam',
                'logo_link' => '/images/partners/lesaffre.jpg',
                'website_link' => 'https://lesaffre.com.vn',
                'description' => 'Chuyên gia về men nướng và giải pháp làm bánh',
                'order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Callebaut Vietnam',
                'logo_link' => '/images/partners/callebaut.jpg',
                'website_link' => 'https://callebaut.com',
                'description' => 'Thương hiệu chocolate cao cấp cho làm bánh',
                'order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Wilton Vietnam',
                'logo_link' => '/images/partners/wilton.jpg',
                'website_link' => 'https://wilton.com',
                'description' => 'Dụng cụ và phụ kiện trang trí bánh chuyên nghiệp',
                'order' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Bakels Vietnam',
                'logo_link' => '/images/partners/bakels.jpg',
                'website_link' => 'https://bakels.com.vn',
                'description' => 'Nguyên liệu và hỗn hợp làm bánh chất lượng cao',
                'order' => 6,
                'status' => 'active',
            ],
        ];

        foreach ($partners as $partnerData) {
            \App\Models\Partner::updateOrCreate(
                ['name' => $partnerData['name']],
                $partnerData
            );
        }

        $this->command->info("✅ Đã tạo " . count($partners) . " đối tác");
    }
}
