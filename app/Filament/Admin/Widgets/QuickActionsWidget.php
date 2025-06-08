<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 4;
    
    protected static string $view = 'filament.admin.widgets.quick-actions';
    
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Tạo khóa học',
                    'description' => 'Thêm khóa học mới',
                    'icon' => 'heroicon-o-academic-cap',
                    'color' => 'blue',
                    'url' => route('filament.admin.resources.courses.create'),
                ],
                [
                    'label' => 'Viết bài',
                    'description' => 'Tạo bài viết mới',
                    'icon' => 'heroicon-o-document-text',
                    'color' => 'green',
                    'url' => route('filament.admin.resources.posts.create'),
                ],
                [
                    'label' => 'Thêm học viên',
                    'description' => 'Đăng ký học viên',
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'purple',
                    'url' => route('filament.admin.resources.students.create'),
                ],
                [
                    'label' => 'Tạo banner',
                    'description' => 'Slider trang chủ',
                    'icon' => 'heroicon-o-photo',
                    'color' => 'orange',
                    'url' => route('filament.admin.resources.sliders.create'),
                ],
                [
                    'label' => 'Quản lý menu',
                    'description' => 'Cấu hình navigation',
                    'icon' => 'heroicon-o-bars-3',
                    'color' => 'indigo',
                    'url' => route('filament.admin.resources.menu-items.index'),
                ],
                [
                    'label' => 'Thêm giảng viên',
                    'description' => 'Quản lý giảng viên',
                    'icon' => 'heroicon-o-user-group',
                    'color' => 'pink',
                    'url' => route('filament.admin.resources.instructors.create'),
                ],
                [
                    'label' => 'Cài đặt hệ thống',
                    'description' => 'Cấu hình website',
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'color' => 'gray',
                    'url' => route('filament.admin.pages.manage-settings'),
                ],
                [
                    'label' => 'Xem website',
                    'description' => 'Mở trang chủ',
                    'icon' => 'heroicon-o-globe-alt',
                    'color' => 'emerald',
                    'url' => route('storeFront'),
                    'external' => true,
                ],
            ]
        ];
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}
