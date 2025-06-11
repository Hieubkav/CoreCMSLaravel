<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Bảng điều khiển';
    protected static ?string $title = 'Bảng điều khiển Core Framework';
    // Bỏ custom view để sử dụng Filament widgets system
    // protected static string $view = 'filament.admin.pages.dashboard';

    public function getWidgets(): array
    {
        $widgets = [];

        // Ưu tiên AnalyticsOverviewWidget nếu có (sau khi setup admin-config)
        if (class_exists('App\\Filament\\Admin\\Widgets\\AnalyticsOverviewWidget')) {
            $widgets[] = 'App\\Filament\\Admin\\Widgets\\AnalyticsOverviewWidget';
        } else {
            // Fallback: sử dụng Filament built-in AccountWidget khi chưa setup xong
            // Đây là widget an toàn, luôn có sẵn trong Filament
            $widgets[] = \Filament\Widgets\AccountWidget::class;
        }

        // Load thêm các widgets khác nếu có
        $additionalWidgets = [
            'App\\Filament\\Admin\\Widgets\\StatsOverviewWidget',
            'App\\Filament\\Admin\\Widgets\\QuickActionsWidget',
        ];

        foreach ($additionalWidgets as $widgetClass) {
            if (class_exists($widgetClass)) {
                $widgets[] = $widgetClass;
            }
        }

        return $widgets;
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

                    // Clear visitor stats cache (chỉ khi class tồn tại)
                    try {
                        if (class_exists('App\\Actions\\System\\GetVisitorStats')) {
                            \App\Actions\System\GetVisitorStats::clearCache();
                        }
                    } catch (\Exception) {
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
                        // Xóa tất cả dữ liệu visitors (chỉ khi model tồn tại)
                        if (class_exists('App\\Models\\Visitor')) {
                            \App\Models\Visitor::truncate();
                        }

                        // Clear cache
                        \Illuminate\Support\Facades\Cache::forget('visitor_realtime_stats');
                        if (class_exists('App\\Actions\\System\\GetVisitorStats')) {
                            \App\Actions\System\GetVisitorStats::clearCache();
                        }

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
