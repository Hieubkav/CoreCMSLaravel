<?php

namespace App\Filament\Admin\Widgets;

use App\Models\AdminConfiguration;
use App\Actions\System\GetVisitorStats;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class AnalyticsOverviewWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.analytics-overview';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        // Chỉ hiển thị khi analytics được bật
        $config = AdminConfiguration::current();
        return $config && $config->visitor_analytics_enabled;
    }

    public function getViewData(): array
    {
        if (!static::canView()) {
            return [];
        }

        try {
            // Cache detailed stats để tăng performance
            $data = Cache::remember('analytics_overview_widget', 600, function () {
                return GetVisitorStats::detailed();
            });

            return [
                'stats' => $data['overview'],
                'popular_posts' => $data['popular_posts'] ?? [],
                'popular_courses' => $data['popular_courses'] ?? [],
                'config' => AdminConfiguration::current(),
            ];
        } catch (\Exception $e) {
            return [
                'stats' => [
                    'today_unique' => 0,
                    'today_total' => 0,
                    'total_unique' => 0,
                    'total_visits' => 0,
                ],
                'popular_posts' => [],
                'popular_courses' => [],
                'config' => AdminConfiguration::current(),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getDisplayName(): string
    {
        return 'Analytics Overview';
    }

    protected function getColumns(): int
    {
        return 1;
    }
}
