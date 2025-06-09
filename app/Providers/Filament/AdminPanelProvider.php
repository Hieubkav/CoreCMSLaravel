<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Generated\Models\WebsiteSettings;


class AdminPanelProvider extends PanelProvider
{
    /**
     * Cấu hình hiển thị brand - true: hiển thị logo, false: chỉ hiển thị text
     */
    private const SHOW_LOGO = false;

    public function panel(Panel $panel): Panel
    {
        // Get settings from database (temporarily disabled until table is created)
        $settings = null; // WebsiteSettings::getActive();

        // Xác định brand name
        $brandName = $this->getBrandName($settings);

        // Xác định logo
        $brandLogo = $this->getBrandLogo($settings);

        // Xác định favicon
        $favicon = $this->getFavicon($settings);

        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Red,
                'gray' => Color::Slate,
            ])
            ->brandName($brandName)
            ->brandLogo($brandLogo)
            ->favicon($favicon)
            ->resources($this->getFilteredResources())
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                // \App\Filament\Admin\Pages\Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Quản lý khóa học')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Quản lý nội dung')
                    ->icon('heroicon-o-document-text')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Quản lý hệ thống')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth('full')
            ->topNavigation(false)
            ->breadcrumbs(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->widgets([
                // \App\Filament\Admin\Widgets\StatsOverviewWidget::class,
                // \App\Filament\Admin\Widgets\QuickActionsWidget::class,
            ])
            // ->spa() // Tạm tắt SPA mode để fix upload issue
            ->unsavedChangesAlerts()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): string => Blade::render('
                    <div class="flex items-center ml-4">
                        <a
                            href="{{ route(\'storeFront\') }}"
                            target="_blank"
                            title="Xem trang chủ website trong tab mới"
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                            style="background-color: #dc2626 !important; color: white !important; border: 1px solid #b91c1c;"
                            onmouseover="this.style.backgroundColor=\'#b91c1c\' !important"
                            onmouseout="this.style.backgroundColor=\'#dc2626\' !important"
                        >
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="hidden sm:inline" style="color: white !important;">Xem Trang Chủ</span>
                            <span class="sm:hidden text-lg">👁️</span>
                        </a>
                    </div>
                ')
            )
            ->login();
    }

    /**
     * Lấy danh sách resources (ưu tiên Generated resources)
     */
    private function getFilteredResources(): array
    {
        $resources = [];

        // 1. Load Generated resources (từ modules đã được enable)
        $generatedPath = app_path('Generated/Filament/Resources');
        if (is_dir($generatedPath)) {
            $generatedFiles = glob($generatedPath . '/*Resource.php');
            foreach ($generatedFiles as $file) {
                $className = 'App\\Generated\\Filament\\Resources\\' . basename($file, '.php');
                if (class_exists($className)) {
                    $resources[] = $className;
                }
            }
        }

        // 2. Load core resources (luôn hiển thị)
        $coreResources = [
            'App\\Generated\\Filament\\Resources\\SystemConfigurationResource',
            'App\\Generated\\Filament\\Resources\\WebsiteSettingsResource',
            'App\\Generated\\Filament\\Resources\\MenuItemResource',
            'App\\Filament\\Admin\\Resources\\UserResource',
        ];

        foreach ($coreResources as $resourceClass) {
            if (class_exists($resourceClass)) {
                $resources[] = $resourceClass;
            }
        }

        // 3. Load remaining resources từ Admin/Resources (nếu không có Generated)
        if (empty($resources)) {
            $adminPath = app_path('Filament/Admin/Resources');
            if (is_dir($adminPath)) {
                $adminFiles = glob($adminPath . '/*Resource.php');
                foreach ($adminFiles as $file) {
                    $className = 'App\\Filament\\Admin\\Resources\\' . basename($file, '.php');
                    if (class_exists($className)) {
                        $resources[] = $className;
                    }
                }
            }
        }

        return array_unique($resources);
    }

    /**
     * Lấy brand name từ settings hoặc fallback
     */
    private function getBrandName($settings): string
    {
        return $settings && $settings->site_name
            ? $settings->site_name
            : 'Core Framework';
    }

    /**
     * Lấy brand logo từ settings hoặc fallback
     */
    private function getBrandLogo($settings): ?string
    {
        if (!self::SHOW_LOGO) {
            return null; // Chỉ hiển thị text
        }

        return $settings && $settings->site_logo && Storage::disk('public')->exists($settings->site_logo)
            ? asset('storage/' . $settings->site_logo)
            : asset('images/logo.png');
    }

    /**
     * Lấy favicon từ settings hoặc fallback
     */
    private function getFavicon($settings): string
    {
        return $settings && $settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)
            ? asset('storage/' . $settings->site_favicon)
            : asset('favicon.ico');
    }
}
