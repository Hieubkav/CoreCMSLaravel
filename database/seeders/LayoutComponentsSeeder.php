<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class LayoutComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo dữ liệu mẫu cho Layout Components...');

        $this->createMenuItems();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu Layout Components!');
    }

    /**
     * Tạo menu items mẫu
     */
    private function createMenuItems()
    {
        // Xóa dữ liệu cũ
        DB::table('menu_items')->truncate();

        $menuItems = [
            // Top level menus
            [
                'id' => 1,
                'parent_id' => null,
                'label' => 'Trang chủ',
                'menu_type' => 'home',
                'link' => '/',
                'icon' => 'fas fa-home',
                'status' => 'active',
                'order' => 1
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'label' => 'Giới thiệu',
                'menu_type' => 'custom_link',
                'link' => '/gioi-thieu',
                'icon' => 'fas fa-info-circle',
                'status' => 'active',
                'order' => 2
            ],
            [
                'id' => 3,
                'parent_id' => null,
                'label' => 'Dịch vụ',
                'menu_type' => 'custom_link',
                'link' => '/dich-vu',
                'icon' => 'fas fa-cogs',
                'status' => 'active',
                'order' => 3
            ],
            [
                'id' => 4,
                'parent_id' => null,
                'label' => 'Sản phẩm',
                'menu_type' => 'all_products',
                'link' => '/san-pham',
                'icon' => 'fas fa-box',
                'status' => 'active',
                'order' => 4
            ],
            [
                'id' => 5,
                'parent_id' => null,
                'label' => 'Tin tức',
                'menu_type' => 'all_posts',
                'link' => '/tin-tuc',
                'icon' => 'fas fa-newspaper',
                'status' => 'active',
                'order' => 5
            ],
            [
                'id' => 6,
                'parent_id' => null,
                'label' => 'Liên hệ',
                'menu_type' => 'custom_link',
                'link' => '/lien-he',
                'icon' => 'fas fa-phone',
                'status' => 'active',
                'order' => 6
            ],

            // Sub menus cho Dịch vụ
            [
                'id' => 7,
                'parent_id' => 3,
                'label' => 'Phát triển Website',
                'menu_type' => 'custom_link',
                'link' => '/dich-vu/phat-trien-website',
                'icon' => 'fas fa-globe',
                'status' => 'active',
                'order' => 1
            ],
            [
                'id' => 8,
                'parent_id' => 3,
                'label' => 'Ứng dụng Mobile',
                'menu_type' => 'custom_link',
                'link' => '/dich-vu/ung-dung-mobile',
                'icon' => 'fas fa-mobile-alt',
                'status' => 'active',
                'order' => 2
            ],
            [
                'id' => 9,
                'parent_id' => 3,
                'label' => 'Tư vấn Công nghệ',
                'menu_type' => 'custom_link',
                'link' => '/dich-vu/tu-van-cong-nghe',
                'icon' => 'fas fa-users',
                'status' => 'active',
                'order' => 3
            ],

            // Sub menus cho Sản phẩm
            [
                'id' => 10,
                'parent_id' => 4,
                'label' => 'Phần mềm quản lý',
                'menu_type' => 'product_category',
                'link' => '/san-pham/phan-mem-quan-ly',
                'icon' => 'fas fa-desktop',
                'status' => 'active',
                'order' => 1
            ],
            [
                'id' => 11,
                'parent_id' => 4,
                'label' => 'Ứng dụng Web',
                'menu_type' => 'product_category',
                'link' => '/san-pham/ung-dung-web',
                'icon' => 'fas fa-window-maximize',
                'status' => 'active',
                'order' => 2
            ],
            [
                'id' => 12,
                'parent_id' => 4,
                'label' => 'Giải pháp E-commerce',
                'menu_type' => 'product_category',
                'link' => '/san-pham/giai-phap-ecommerce',
                'icon' => 'fas fa-shopping-cart',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($menuItems as $item) {
            DB::table('menu_items')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('✅ Đã tạo ' . count($menuItems) . ' menu items');
    }
}
