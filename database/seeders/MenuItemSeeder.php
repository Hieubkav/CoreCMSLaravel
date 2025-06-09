<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Generated\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🍽️ Bắt đầu tạo menu items...');

        // Xóa dữ liệu cũ
        MenuItem::truncate();

        // Main Navigation Menu
        $this->createMainMenu();

        // Header Menu (quick links)
        $this->createHeaderMenu();

        // Footer Menu
        $this->createFooterMenu();

        $this->command->info('✅ Đã tạo menu items thành công!');
    }

    private function createMainMenu()
    {
        $this->command->info('📱 Tạo main navigation menu...');

        // Trang chủ
        MenuItem::create([
            'title' => 'Trang chủ',
            'slug' => 'trang-chu',
            'route_name' => 'home',
            'icon' => 'fas fa-home',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 1,
            'meta_title' => 'Trang chủ - ' . config('app.name'),
            'meta_description' => 'Trang chủ chính thức của ' . config('app.name'),
        ]);

        // Giới thiệu
        $about = MenuItem::create([
            'title' => 'Giới thiệu',
            'slug' => 'gioi-thieu',
            'route_name' => 'about',
            'icon' => 'fas fa-info-circle',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 2,
            'meta_title' => 'Giới thiệu về ' . config('app.name'),
            'meta_description' => 'Tìm hiểu về lịch sử, tầm nhìn và sứ mệnh của ' . config('app.name'),
        ]);

        // Sub-menu cho Giới thiệu
        MenuItem::create([
            'parent_id' => $about->id,
            'title' => 'Về chúng tôi',
            'slug' => 've-chung-toi',
            'route_name' => 'about.company',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 1,
        ]);

        MenuItem::create([
            'parent_id' => $about->id,
            'title' => 'Đội ngũ',
            'slug' => 'doi-ngu',
            'route_name' => 'about.team',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 2,
        ]);

        // Sản phẩm/Dịch vụ
        MenuItem::create([
            'title' => 'Sản phẩm',
            'slug' => 'san-pham',
            'route_name' => 'products.index',
            'icon' => 'fas fa-shopping-cart',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 3,
            'meta_title' => 'Sản phẩm - ' . config('app.name'),
            'meta_description' => 'Khám phá các sản phẩm chất lượng cao từ ' . config('app.name'),
        ]);

        // Tin tức
        $news = MenuItem::create([
            'title' => 'Tin tức',
            'slug' => 'tin-tuc',
            'route_name' => 'posts.index',
            'icon' => 'fas fa-newspaper',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 4,
            'meta_title' => 'Tin tức - ' . config('app.name'),
            'meta_description' => 'Cập nhật tin tức mới nhất từ ' . config('app.name'),
        ]);

        // Sub-menu cho Tin tức
        MenuItem::create([
            'parent_id' => $news->id,
            'title' => 'Tin công ty',
            'slug' => 'tin-cong-ty',
            'url' => '/tin-tuc/tin-cong-ty',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 1,
        ]);

        // Liên hệ
        MenuItem::create([
            'title' => 'Liên hệ',
            'slug' => 'lien-he',
            'route_name' => 'contact',
            'icon' => 'fas fa-envelope',
            'menu_location' => 'main',
            'is_active' => true,
            'order' => 5,
            'meta_title' => 'Liên hệ - ' . config('app.name'),
            'meta_description' => 'Thông tin liên hệ và địa chỉ của ' . config('app.name'),
        ]);
    }

    private function createHeaderMenu()
    {
        $this->command->info('📱 Tạo header menu...');

        MenuItem::create([
            'title' => 'Hotline',
            'slug' => 'hotline',
            'url' => 'tel:+84123456789',
            'icon' => 'fas fa-phone',
            'menu_location' => 'header',
            'target' => '_self',
            'is_active' => true,
            'order' => 1,
        ]);

        MenuItem::create([
            'title' => 'Email',
            'slug' => 'email',
            'url' => 'mailto:info@example.com',
            'icon' => 'fas fa-envelope',
            'menu_location' => 'header',
            'target' => '_self',
            'is_active' => true,
            'order' => 2,
        ]);
    }

    private function createFooterMenu()
    {
        $this->command->info('📱 Tạo footer menu...');

        MenuItem::create([
            'title' => 'Chính sách bảo mật',
            'slug' => 'chinh-sach-bao-mat',
            'route_name' => 'privacy',
            'menu_location' => 'footer',
            'is_active' => true,
            'order' => 1,
        ]);

        MenuItem::create([
            'title' => 'Điều khoản sử dụng',
            'slug' => 'dieu-khoan-su-dung',
            'route_name' => 'terms',
            'menu_location' => 'footer',
            'is_active' => true,
            'order' => 2,
        ]);

        MenuItem::create([
            'title' => 'Sitemap',
            'slug' => 'sitemap',
            'route_name' => 'sitemap',
            'menu_location' => 'footer',
            'is_active' => true,
            'order' => 3,
        ]);
    }
}
