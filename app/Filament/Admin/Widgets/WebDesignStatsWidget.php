<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WebDesign;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WebDesignStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $webDesign = WebDesign::first();

        if (!$webDesign) {
            return [
                Stat::make('🎨 Giao diện', 'Chưa thiết lập')
                    ->description('Cần tạo cấu hình WebDesign')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('warning')
                    ->url(route('filament.admin.pages.manage-web-design')),
            ];
        }

        $sections = $webDesign->getOrderedSections();
        $enabledCount = count($sections);
        $totalSections = 10;

        // Calculate status
        $statusColor = $enabledCount >= 8 ? 'success' : ($enabledCount >= 5 ? 'warning' : 'danger');
        $statusIcon = $enabledCount >= 8 ? 'heroicon-m-check-circle' : ($enabledCount >= 5 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-x-circle');

        return [
            Stat::make('🎨 Sections hiển thị', $enabledCount . '/' . $totalSections)
                ->description('Số section đang hoạt động trên trang chủ')
                ->descriptionIcon($statusIcon)
                ->color($statusColor)
                ->url(route('filament.admin.pages.manage-web-design'))
                ->chart([
                    $webDesign->hero_banner_enabled ? 1 : 0,
                    $webDesign->courses_overview_enabled ? 1 : 0,
                    $webDesign->album_timeline_enabled ? 1 : 0,
                    $webDesign->course_groups_enabled ? 1 : 0,
                    $webDesign->course_categories_enabled ? 1 : 0,
                    $webDesign->testimonials_enabled ? 1 : 0,
                    $webDesign->faq_enabled ? 1 : 0,
                    $webDesign->partners_enabled ? 1 : 0,
                    $webDesign->blog_posts_enabled ? 1 : 0,
                    $webDesign->homepage_cta_enabled ? 1 : 0,
                ]),

            Stat::make('🎯 Hero Banner', $webDesign->hero_banner_enabled ? '✅ Hiển thị' : '❌ Đã ẩn')
                ->description('Banner chính trang chủ')
                ->descriptionIcon($webDesign->hero_banner_enabled ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                ->color($webDesign->hero_banner_enabled ? 'success' : 'danger')
                ->url(route('filament.admin.pages.manage-web-design')),

            Stat::make('📚 Khóa học', $webDesign->courses_overview_enabled ? '✅ Hiển thị' : '❌ Đã ẩn')
                ->description('Section giới thiệu khóa học')
                ->descriptionIcon($webDesign->courses_overview_enabled ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                ->color($webDesign->courses_overview_enabled ? 'success' : 'danger')
                ->url(route('filament.admin.pages.manage-web-design')),
        ];
    }
}
