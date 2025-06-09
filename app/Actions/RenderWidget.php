<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\WidgetSetting;

class RenderWidget
{
    use AsAction;

    public function handle(int $widgetId, array $data = []): string
    {
        $widget = WidgetSetting::find($widgetId);

        if (!$widget) {
            return '';
        }

        return $widget->render($data);
    }

    /**
     * Render widgets for position
     */
    public static function renderForPosition(string $position, array $data = []): string
    {
        $widgets = WidgetSetting::getForPosition($position);
        $output = '';

        foreach ($widgets as $widget) {
            $output .= $widget->render($data);
        }

        return $output;
    }

    /**
     * Render widget by slug
     */
    public static function renderBySlug(string $slug, array $data = []): string
    {
        $widget = WidgetSetting::where('slug', $slug)
                              ->where('status', 'active')
                              ->first();

        if (!$widget) {
            return '';
        }

        return $widget->render($data);
    }
}
