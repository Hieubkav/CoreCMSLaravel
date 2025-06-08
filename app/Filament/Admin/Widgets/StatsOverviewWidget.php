<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Course;
use App\Models\Student;
use App\Models\Post;
use App\Models\Instructor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Cache thống kê để tăng performance
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return [
                'courses_total' => Course::count(),
                'courses_active' => Course::where('status', 'active')->count(),
                'students_total' => Student::count(),
                'students_active' => Student::where('status', 'active')->count(),
                'posts_total' => Post::count(),
                'posts_active' => Post::where('status', 'active')->count(),
                'instructors_total' => Instructor::count(),
                'instructors_active' => Instructor::where('status', 'active')->count(),
            ];
        });

        return [
            Stat::make('Tổng khóa học', $stats['courses_total'])
                ->description($stats['courses_active'] . ' đang hoạt động')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$dispatch("open-modal", { id: "courses-modal" })',
                ]),

            Stat::make('Tổng học viên', $stats['students_total'])
                ->description($stats['students_active'] . ' đang học')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Tổng bài viết', $stats['posts_total'])
                ->description($stats['posts_active'] . ' đã xuất bản')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Tổng giảng viên', $stats['instructors_total'])
                ->description($stats['instructors_active'] . ' đang hoạt động')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger')
                ->chart([2, 1, 3, 2, 4, 3, 5])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}
