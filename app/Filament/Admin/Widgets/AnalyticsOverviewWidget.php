<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AdminConfiguration;
use App\Actions\System\GetVisitorStats;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class AnalyticsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        // Chỉ hiển thị khi analytics được bật
        $config = AdminConfiguration::current();
        return $config && $config->visitor_analytics_enabled;
    }

    protected function getStats(): array
    {
        if (!static::canView()) {
            return [];
        }

        try {
            // Cache stats để tăng performance
            $stats = Cache::remember('analytics_widget_stats', 300, function () {
                return GetVisitorStats::overview();
            });

            return [
                Stat::make('Người xem hôm nay', $stats['today_unique'])
                    ->description('Unique visitors hôm nay')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('success')
                    ->chart($this->getTodayChart()),

                Stat::make('Lượt xem hôm nay', $stats['today_total'])
                    ->description('Tổng lượt xem hôm nay')
                    ->descriptionIcon('heroicon-m-eye')
                    ->color('info')
                    ->chart($this->getTodayViewsChart()),

                Stat::make('Tổng người xem', $stats['total_unique'])
                    ->description('Từ trước đến giờ')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('warning'),

                Stat::make('Tổng lượt xem', $stats['total_visits'])
                    ->description('Từ trước đến giờ')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('primary'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Lỗi Analytics', 'N/A')
                    ->description('Không thể tải dữ liệu')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
            ];
        }
    }

    private function getTodayChart(): array
    {
        try {
            // Tạo chart data cho 24 giờ qua
            $hours = [];
            for ($i = 23; $i >= 0; $i--) {
                $hour = now()->subHours($i)->format('H');
                $hours[] = rand(0, 10); // Mock data - thay bằng real data
            }
            return $hours;
        } catch (\Exception $e) {
            return [1, 2, 3, 4, 5];
        }
    }

    private function getTodayViewsChart(): array
    {
        try {
            // Tạo chart data cho lượt xem theo giờ
            $hours = [];
            for ($i = 23; $i >= 0; $i--) {
                $hour = now()->subHours($i)->format('H');
                $hours[] = rand(0, 20); // Mock data - thay bằng real data
            }
            return $hours;
        } catch (\Exception $e) {
            return [2, 4, 6, 8, 10];
        }
    }

    protected function getColumns(): int
    {
        return 4;
    }

    public function getDisplayName(): string
    {
        return 'Thống kê Analytics & Visitor';
    }
}
