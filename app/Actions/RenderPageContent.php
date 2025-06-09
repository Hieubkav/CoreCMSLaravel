<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\PageBuilder;
use App\Models\WidgetSetting;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\View;

class RenderPageContent
{
    use AsAction;

    public function handle(string $pageSlug, array $data = []): array
    {
        // Get page
        $page = PageBuilder::where('slug', $pageSlug)
                          ->published()
                          ->first();

        if (!$page) {
            return [
                'success' => false,
                'message' => 'Page not found',
            ];
        }

        // Check if user can view page
        if (!$page->canView(auth()->id())) {
            return [
                'success' => false,
                'message' => 'Access denied',
            ];
        }

        // Get active theme
        $theme = ThemeSetting::getActiveTheme();

        // Render page content
        $content = $page->renderContent($data);

        // Get widgets for page
        $widgets = $this->getPageWidgets($page);

        // Increment view count
        $page->incrementViewCount();

        return [
            'success' => true,
            'page' => $page,
            'theme' => $theme,
            'content' => $content,
            'widgets' => $widgets,
            'meta' => $this->getPageMeta($page),
        ];
    }

    /**
     * Get widgets for page
     */
    private function getPageWidgets(PageBuilder $page): array
    {
        $widgets = [];

        // Get sidebar widgets if page uses sidebar
        if ($page->use_sidebar) {
            $position = $page->sidebar_position === 'left' ? 'sidebar_left' : 'sidebar_right';
            $widgets['sidebar'] = WidgetSetting::getForPosition($position);
        }

        // Get header widgets
        $widgets['header'] = WidgetSetting::getForPosition('header');

        // Get footer widgets
        $widgets['footer'] = WidgetSetting::getForPosition('footer');

        // Get before/after content widgets
        $widgets['before_content'] = WidgetSetting::getForPosition('before_content');
        $widgets['after_content'] = WidgetSetting::getForPosition('after_content');

        return $widgets;
    }

    /**
     * Get page meta data
     */
    private function getPageMeta(PageBuilder $page): array
    {
        return [
            'title' => $page->page_title,
            'description' => $page->meta_description,
            'keywords' => $page->meta_keywords,
            'og_title' => $page->og_title ?: $page->page_title,
            'og_description' => $page->og_description ?: $page->meta_description,
            'og_image' => $page->og_image,
            'canonical_url' => $page->canonical_url,
            'page_class' => $page->page_class,
            'custom_css_vars' => $page->custom_css_vars,
            'tracking_codes' => $page->tracking_codes,
        ];
    }

    /**
     * Render page for web response
     */
    public static function renderForWeb(string $pageSlug, array $data = []): \Illuminate\Http\Response
    {
        $result = static::run($pageSlug, $data);

        if (!$result['success']) {
            abort(404, $result['message']);
        }

        $page = $result['page'];
        $theme = $result['theme'];
        $content = $result['content'];
        $widgets = $result['widgets'];
        $meta = $result['meta'];

        // Set page title and meta
        View::share('pageTitle', $meta['title']);
        View::share('pageDescription', $meta['description']);
        View::share('pageKeywords', $meta['keywords']);
        View::share('ogTitle', $meta['og_title']);
        View::share('ogDescription', $meta['og_description']);
        View::share('ogImage', $meta['og_image']);

        // Render page view
        return response()->view($page->layout_template, compact(
            'page', 'theme', 'content', 'widgets', 'meta'
        ));
    }
}
