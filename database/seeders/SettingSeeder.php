<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Tạo dữ liệu cấu hình website...');

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Core Framework',
                'logo_link' => 'settings/logo-core-framework.webp',
                'favicon_link' => 'settings/favicon.ico',
                'seo_title' => 'Core Framework - Laravel Application Framework',
                'seo_description' => 'Core Framework provides a solid foundation for building modern Laravel applications with best practices and optimized performance.',
                'og_image_link' => 'settings/og-image-core-framework.webp',
                'placeholder_image' => 'settings/placeholder.webp',
                'hotline' => '0123.456.789',
                'address' => '123 Main Street, City, Country',
                'email' => 'contact@example.com',
                'slogan' => 'Build Better Laravel Applications',
                'facebook_link' => 'https://facebook.com/example',
                'zalo_link' => 'https://zalo.me/example',
                'youtube_link' => 'https://youtube.com/@example',
                'tiktok_link' => 'https://tiktok.com/@example',
                'messenger_link' => 'https://m.me/example',
                'working_hours' => 'Monday - Friday: 9:00 - 18:00, Saturday: 9:00 - 12:00',
                'footer_description' => 'Core Framework - A modern Laravel application framework designed for rapid development and scalability. Built with best practices and developer experience in mind.',
                'status' => 'active',
                'order' => 1,
            ]
        );

        $this->command->info('✅ Đã tạo cấu hình website');
    }
}
