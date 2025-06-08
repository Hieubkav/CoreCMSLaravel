<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\StatsOverviewWidget;
use App\Filament\Admin\Widgets\QuickActionsWidget;
use App\Filament\Admin\Widgets\WebDesignStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Bảng điều khiển';
    protected static ?string $title = 'Bảng điều khiển Core Framework';
    protected static string $view = 'filament.admin.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            WebDesignStatsWidget::class,
            QuickActionsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('refresh')
                ->label('Làm mới')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear cache
                    \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
                    \Illuminate\Support\Facades\Cache::forget('recent_activity_query');
                    \Illuminate\Support\Facades\Cache::forget('visitor_realtime_stats');

                    // Clear visitor stats cache
                    try {
                        $visitorService = new \App\Services\VisitorStatsService();
                        $visitorService->clearCache();
                    } catch (\Exception $e) {
                        // Silent fail
                    }

                    $this->dispatch('$refresh');

                    \Filament\Notifications\Notification::make()
                        ->title('Đã làm mới dữ liệu')
                        ->success()
                        ->send();
                }),

            \Filament\Actions\Action::make('view_website')
                ->label('Xem website')
                ->icon('heroicon-o-globe-alt')
                ->color('info')
                ->url(route('storeFront'))
                ->openUrlInNewTab(),

            \Filament\Actions\Action::make('reset_visitor_stats')
                ->label('Reset thống kê')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reset thống kê truy cập')
                ->modalDescription('Bạn có chắc chắn muốn xóa tất cả dữ liệu thống kê truy cập? Hành động này không thể hoàn tác.')
                ->modalSubmitActionLabel('Xóa tất cả')
                ->action(function () {
                    try {
                        // Xóa tất cả dữ liệu visitors
                        \App\Models\Visitor::truncate();

                        // Clear cache
                        \Illuminate\Support\Facades\Cache::forget('visitor_realtime_stats');
                        $visitorService = new \App\Services\VisitorStatsService();
                        $visitorService->clearCache();

                        $this->dispatch('$refresh');

                        \Filament\Notifications\Notification::make()
                            ->title('Đã reset thống kê thành công')
                            ->body('Tất cả dữ liệu thống kê truy cập đã được xóa.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Lỗi khi reset thống kê')
                            ->body('Không thể xóa dữ liệu: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
