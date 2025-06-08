<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Association;

class AssociationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🤝 Tạo dữ liệu hiệp hội...');

        $associations = [
            [
                'name' => 'Hiệp hội Tin học Việt Nam',
                'image_link' => null, // KISS: Không có ảnh để test fallback UI
                'website_link' => 'https://www.vaip.org.vn',
                'description' => 'Hiệp hội Tin học Việt Nam - Tổ chức hàng đầu về công nghệ thông tin',
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Hiệp hội Phần mềm và Dịch vụ CNTT Việt Nam',
                'image_link' => null, // KISS: Không có ảnh để test fallback UI
                'website_link' => 'https://www.vinasa.org.vn',
                'description' => 'VINASA - Hiệp hội Phần mềm và Dịch vụ CNTT Việt Nam',
                'status' => 'active',
                'order' => 2,
            ],
            [
                'name' => 'Hiệp hội Doanh nghiệp nhỏ và vừa Việt Nam',
                'image_link' => null, // KISS: Không có ảnh để test fallback UI
                'website_link' => 'https://www.vinasme.org.vn',
                'description' => 'VINASME - Hiệp hội Doanh nghiệp nhỏ và vừa Việt Nam',
                'status' => 'active',
                'order' => 3,
            ],
            [
                'name' => 'Hiệp hội Kế toán và Kiểm toán Việt Nam',
                'image_link' => null, // KISS: Không có ảnh để test fallback UI
                'website_link' => 'https://www.vaa.net.vn',
                'description' => 'VAA - Hiệp hội Kế toán và Kiểm toán Việt Nam',
                'status' => 'active',
                'order' => 4,
            ],
            [
                'name' => 'Hiệp hội Đào tạo và Phát triển Nguồn nhân lực',
                'image_link' => null, // KISS: Không có ảnh để test fallback UI
                'website_link' => 'https://www.hrda.org.vn',
                'description' => 'HRDA - Hiệp hội Đào tạo và Phát triển Nguồn nhân lực Việt Nam',
                'status' => 'active',
                'order' => 5,
            ],
        ];

        foreach ($associations as $associationData) {
            Association::updateOrCreate(
                ['name' => $associationData['name']],
                $associationData
            );
        }

        $this->command->info("✅ Đã tạo " . count($associations) . " hiệp hội");
    }
}
