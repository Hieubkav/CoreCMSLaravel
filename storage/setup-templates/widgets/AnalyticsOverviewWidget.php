<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Actions\System\GetVisitorStats;
use App\Models\AdminConfiguration;

class AnalyticsOverviewWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.analytics-overview-widget';

    protected int | string | array $columnSpan = 'full';

    /**
     * Polling mỗi 5 giây để update realtime
     */
    protected static ?string $pollingInterval = '5s';

    /**
     * Kiểm tra xem widget có thể hiển thị không
     */
    public static function canView(): bool
    {
        $config = AdminConfiguration::current();
        return $config && $config->visitor_analytics_enabled;
    }

    /**
     * Lấy visitor statistics realtime
     */
    public function getVisitorStats(): array
    {
        if (!static::canView()) {
            return [
                'today_unique' => 0,
                'today_total' => 0,
                'total_unique' => 0,
                'total_visits' => 0,
                'enabled' => false
            ];
        }

        $stats = GetVisitorStats::overview();
        $stats['enabled'] = true;
        
        return $stats;
    }

    /**
     * Reset visitor statistics
     */
    public function resetStats(): void
    {
        \App\Actions\System\ResetVisitorStats::reset();
        
        // Dispatch browser event để refresh widget
        $this->dispatch('visitor-stats-reset');
    }

    /**
     * Get view data
     */
    protected function getViewData(): array
    {
        return [
            'stats' => $this->getVisitorStats(),
            'config' => AdminConfiguration::current(),
            'tracking_interval' => env('VISITOR_TRACKING_INTERVAL', 5)
        ];
    }
}
