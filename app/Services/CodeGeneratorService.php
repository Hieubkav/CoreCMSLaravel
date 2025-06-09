<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CodeGeneratorService
{
    /**
     * Base path cho generated code
     */
    private const GENERATED_PATH = 'app/Generated';
    private const TEMPLATES_PATH = 'stubs/modules';

    /**
     * Mapping giữa 9 modules chính và files cần generate
     */
    private static array $moduleFileMapping = [
        // 1. SYSTEM CONFIGURATION MODULE (Bắt buộc)
        'system_configuration' => [
            'models' => ['SystemConfiguration'],
            'resources' => ['SystemConfigurationResource'],
            'actions' => ['UploadFaviconAction'],
            'migrations' => ['create_system_configurations_table'],
            'seeders' => ['SystemConfigurationSeeder'],
        ],

        // 2. USER ROLES & PERMISSIONS MODULE (Tùy chọn)
        'user_roles_permissions' => [
            'resources' => ['RoleResource', 'PermissionResource'],
            'actions' => ['SetupSpatiePermissions'],
            'seeders' => ['RolesAndPermissionsSeeder'],
        ],

        // 3. BLOG/POST MODULE (Tùy chọn)
        'blog_posts' => [
            'models' => ['Post', 'PostCategory', 'PostImage'],
            'resources' => ['PostResource', 'PostCategoryResource'],
            'observers' => ['PostObserver'],
            'livewire' => ['BlogListing', 'PostDetail', 'PostFilter'],
            'views' => ['blog-listing', 'post-detail', 'post-filter'],
            'migrations' => ['create_posts_table', 'create_post_categories_table', 'create_post_images_table'],
            'seeders' => ['BlogModuleSeeder'],
        ],

        // 4. STAFF MODULE (Tùy chọn)
        'staff' => [
            'models' => ['Staff', 'StaffImage'],
            'resources' => ['StaffResource'],
            'livewire' => ['StaffListing', 'StaffDetail'],
            'views' => ['staff-listing', 'staff-detail'],
            'migrations' => ['create_staff_table', 'create_staff_images_table'],
            'seeders' => ['StaffSeeder'],
        ],

        // 5. CONTENT SECTIONS MODULE (Tùy chọn)
        'content_sections' => [
            'models' => ['Slider', 'Gallery', 'Brand', 'FAQ', 'Statistic', 'Testimonial', 'Service', 'Feature', 'Partner', 'Schedule', 'Timeline'],
            'resources' => ['SliderResource', 'GalleryResource', 'BrandResource', 'FAQResource', 'StatisticResource', 'TestimonialResource', 'ServiceResource', 'FeatureResource', 'PartnerResource', 'ScheduleResource', 'TimelineResource'],
            'components' => ['slider', 'gallery', 'brand', 'faq', 'statistic', 'testimonial', 'service', 'feature', 'partner', 'schedule', 'timeline'],
            'migrations' => ['create_sliders_table', 'create_galleries_table', 'create_brands_table', 'create_faqs_table', 'create_statistics_table', 'create_testimonials_table', 'create_services_table', 'create_features_table', 'create_partners_table', 'create_schedules_table', 'create_timelines_table'],
            'seeders' => ['ContentSectionsSeeder'],
        ],

        // 6. LAYOUT COMPONENTS (Bắt buộc)
        'layout_components' => [
            'models' => ['MenuItem'],
            'resources' => ['MenuItemResource'],
            'livewire' => ['SearchSuggestions', 'DynamicMenu', 'ToastNotifications'],
            'components' => ['header', 'footer', 'speedial', 'subnav', 'navbar'],
            'migrations' => ['create_menu_items_table'],
            'seeders' => ['MenuItemSeeder'],
        ],

        // 7. E-COMMERCE MODULE (Tùy chọn)
        'ecommerce' => [
            'models' => ['Product', 'ProductCategory', 'ProductImage', 'ProductTag', 'ProductVersion', 'Cart', 'CartItem', 'Order', 'OrderItem', 'Customer', 'WishList', 'Coupon', 'Payment', 'Review'],
            'resources' => ['ProductResource', 'ProductCategoryResource', 'OrderResource', 'CustomerResource', 'CouponResource'],
            'livewire' => ['ProductFilter', 'ProductDetail', 'CartSidebar', 'CustomerDashboard'],
            'views' => ['product-filter', 'product-detail', 'cart-sidebar', 'customer-dashboard'],
            'layouts' => ['shop'],
            'migrations' => ['create_products_table', 'create_product_categories_table', 'create_product_images_table', 'create_product_tags_table', 'create_product_versions_table', 'create_carts_table', 'create_cart_items_table', 'create_orders_table', 'create_order_items_table', 'create_customers_table', 'create_wish_lists_table', 'create_coupons_table', 'create_payments_table', 'create_reviews_table'],
            'seeders' => ['EcommerceSeeder'],
        ],

        // 8. SETTINGS EXPANSION (Bắt buộc - mở rộng model hiện tại)
        'settings_expansion' => [
            'migrations' => ['expand_settings_table'],
            'seeders' => ['SettingsExpansionSeeder'],
        ],

        // 9. WEB DESIGN MANAGEMENT (Bắt buộc)
        'web_design_management' => [
            'models' => ['WebDesign'],
            'resources' => ['WebDesignResource'],
            'pages' => ['ManageWebDesign'],
            'migrations' => ['create_web_designs_table'],
            'seeders' => ['WebDesignSeeder'],
        ],
    ];

    /**
     * Generate code cho modules đã được enable
     */
    public static function generateEnabledModules(array $enabledModules): array
    {
        $results = [];
        
        // Tạo thư mục Generated nếu chưa có
        self::ensureGeneratedDirectory();
        
        foreach ($enabledModules as $moduleName) {
            if (isset(self::$moduleFileMapping[$moduleName])) {
                $results[$moduleName] = self::generateModule($moduleName);
            }
        }
        
        return $results;
    }

    /**
     * Generate code cho một module
     */
    private static function generateModule(string $moduleName): array
    {
        $moduleFiles = self::$moduleFileMapping[$moduleName];
        $generated = [];
        
        foreach ($moduleFiles as $type => $files) {
            $generated[$type] = [];
            
            foreach ($files as $fileName) {
                try {
                    $result = self::generateFile($type, $fileName, $moduleName);
                    $generated[$type][] = $result;
                } catch (\Exception $e) {
                    $generated[$type][] = [
                        'file' => $fileName,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }
        }
        
        return $generated;
    }

    /**
     * Generate một file cụ thể
     */
    private static function generateFile(string $type, string $fileName, string $moduleName): array
    {
        switch ($type) {
            case 'models':
                return self::generateModel($fileName, $moduleName);
            case 'resources':
                return self::generateResource($fileName, $moduleName);
            case 'migrations':
                return self::generateMigration($fileName, $moduleName);
            case 'controllers':
                return self::generateController($fileName, $moduleName);
            case 'views':
                return self::generateView($fileName, $moduleName);
            case 'components':
                return self::generateComponent($fileName, $moduleName);
            case 'pages':
                return self::generatePage($fileName, $moduleName);
            case 'services':
                return self::generateService($fileName, $moduleName);
            case 'actions':
                return self::generateAction($fileName, $moduleName);
            case 'observers':
                return self::generateObserver($fileName, $moduleName);
            case 'livewire':
                return self::generateLivewire($fileName, $moduleName);
            case 'layouts':
                return self::generateLayout($fileName, $moduleName);
            case 'seeders':
                return self::generateSeeder($fileName, $moduleName);
            default:
                throw new \Exception("Unknown file type: {$type}");
        }
    }

    /**
     * Generate Model
     */
    private static function generateModel(string $modelName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Models/{$modelName}.php");
        
        // Sử dụng Laravel's make:model command
        Artisan::call('make:model', [
            'name' => "Generated/Models/{$modelName}",
            '--migration' => true,
        ]);
        
        return [
            'file' => $modelName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'model'
        ];
    }

    /**
     * Generate Filament Resource
     */
    private static function generateResource(string $resourceName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Filament/Resources/{$resourceName}.php");
        
        // Extract model name from resource name
        $modelName = str_replace('Resource', '', $resourceName);
        
        Artisan::call('make:filament-resource', [
            'name' => "Generated/Filament/Resources/{$resourceName}",
            '--model' => "App\\Generated\\Models\\{$modelName}",
            '--generate',
        ]);
        
        return [
            'file' => $resourceName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'resource'
        ];
    }

    /**
     * Generate Migration
     */
    private static function generateMigration(string $migrationName, string $moduleName): array
    {
        Artisan::call('make:migration', [
            'name' => $migrationName,
        ]);
        
        return [
            'file' => $migrationName,
            'success' => true,
            'type' => 'migration'
        ];
    }

    /**
     * Generate Controller
     */
    private static function generateController(string $controllerName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Http/Controllers/{$controllerName}.php");
        
        Artisan::call('make:controller', [
            'name' => "Generated/Http/Controllers/{$controllerName}",
            '--resource',
        ]);
        
        return [
            'file' => $controllerName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'controller'
        ];
    }

    /**
     * Generate View
     */
    private static function generateView(string $viewName, string $moduleName): array
    {
        $targetPath = resource_path("views/generated/{$viewName}.blade.php");
        
        // Tạo thư mục nếu chưa có
        File::ensureDirectoryExists(dirname($targetPath));
        
        // Tạo view cơ bản
        $content = self::getViewTemplate($viewName, $moduleName);
        File::put($targetPath, $content);
        
        return [
            'file' => $viewName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'view'
        ];
    }

    /**
     * Generate Component
     */
    private static function generateComponent(string $componentName, string $moduleName): array
    {
        $targetPath = resource_path("views/generated/components/{$componentName}.blade.php");
        
        File::ensureDirectoryExists(dirname($targetPath));
        
        $content = self::getComponentTemplate($componentName, $moduleName);
        File::put($targetPath, $content);
        
        return [
            'file' => $componentName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'component'
        ];
    }

    /**
     * Generate Filament Page
     */
    private static function generatePage(string $pageName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Filament/Pages/{$pageName}.php");
        
        Artisan::call('make:filament-page', [
            'name' => "Generated/Filament/Pages/{$pageName}",
        ]);
        
        return [
            'file' => $pageName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'page'
        ];
    }

    /**
     * Generate Service
     */
    private static function generateService(string $serviceName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Services/{$serviceName}.php");
        
        File::ensureDirectoryExists(dirname($targetPath));
        
        $content = self::getServiceTemplate($serviceName, $moduleName);
        File::put($targetPath, $content);
        
        return [
            'file' => $serviceName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'service'
        ];
    }

    /**
     * Đảm bảo thư mục Generated tồn tại
     */
    private static function ensureGeneratedDirectory(): void
    {
        $directories = [
            app_path('Generated'),
            app_path('Generated/Models'),
            app_path('Generated/Http'),
            app_path('Generated/Http/Controllers'),
            app_path('Generated/Filament'),
            app_path('Generated/Filament/Resources'),
            app_path('Generated/Filament/Pages'),
            app_path('Generated/Services'),
            app_path('Generated/Actions'),
            app_path('Generated/Observers'),
            app_path('Generated/Livewire'),
            resource_path('views/generated'),
            resource_path('views/generated/components'),
            resource_path('views/generated/layouts'),
            resource_path('views/livewire/generated'),
        ];

        foreach ($directories as $directory) {
            File::ensureDirectoryExists($directory);
        }
    }

    /**
     * Get view template
     */
    private static function getViewTemplate(string $viewName, string $moduleName): string
    {
        return "{{-- Generated view for {$moduleName} module --}}\n@extends('layouts.app')\n\n@section('content')\n<div class=\"container\">\n    <h1>{{ ucfirst('{$viewName}') }}</h1>\n    {{-- Add your content here --}}\n</div>\n@endsection\n";
    }

    /**
     * Get component template
     */
    private static function getComponentTemplate(string $componentName, string $moduleName): string
    {
        return "{{-- Generated component for {$moduleName} module --}}\n<div class=\"{$componentName}-component\">\n    {{-- Add your {$componentName} content here --}}\n</div>\n";
    }

    /**
     * Get service template
     */
    private static function getServiceTemplate(string $serviceName, string $moduleName): string
    {
        $className = Str::studly($serviceName);
        return "<?php\n\nnamespace App\\Generated\\Services;\n\nclass {$className}\n{\n    /**\n     * Generated service for {$moduleName} module\n     */\n    public function handle()\n    {\n        // Add your service logic here\n    }\n}\n";
    }

    /**
     * Generate Action
     */
    private static function generateAction(string $actionName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Actions/{$actionName}.php");

        File::ensureDirectoryExists(dirname($targetPath));

        $content = self::getActionTemplate($actionName, $moduleName);
        File::put($targetPath, $content);

        return [
            'file' => $actionName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'action'
        ];
    }

    /**
     * Generate Observer
     */
    private static function generateObserver(string $observerName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Observers/{$observerName}.php");

        Artisan::call('make:observer', [
            'name' => "Generated/Observers/{$observerName}",
        ]);

        return [
            'file' => $observerName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'observer'
        ];
    }

    /**
     * Generate Livewire Component
     */
    private static function generateLivewire(string $componentName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Livewire/{$componentName}.php");

        Artisan::call('make:livewire', [
            'name' => "Generated/Livewire/{$componentName}",
        ]);

        return [
            'file' => $componentName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'livewire'
        ];
    }

    /**
     * Generate Layout
     */
    private static function generateLayout(string $layoutName, string $moduleName): array
    {
        $targetPath = resource_path("views/generated/layouts/{$layoutName}.blade.php");

        File::ensureDirectoryExists(dirname($targetPath));

        $content = self::getLayoutTemplate($layoutName, $moduleName);
        File::put($targetPath, $content);

        return [
            'file' => $layoutName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'layout'
        ];
    }

    /**
     * Generate Seeder
     */
    private static function generateSeeder(string $seederName, string $moduleName): array
    {
        Artisan::call('make:seeder', [
            'name' => $seederName,
        ]);

        return [
            'file' => $seederName,
            'success' => true,
            'type' => 'seeder'
        ];
    }

    /**
     * Get action template
     */
    private static function getActionTemplate(string $actionName, string $moduleName): string
    {
        $className = Str::studly($actionName);
        return "<?php\n\nnamespace App\\Generated\\Actions;\n\nuse Lorisleiva\\Actions\\Concerns\\AsAction;\n\nclass {$className}\n{\n    use AsAction;\n\n    /**\n     * Generated action for {$moduleName} module\n     */\n    public function handle()\n    {\n        // Add your action logic here\n    }\n}\n";
    }

    /**
     * Get layout template
     */
    private static function getLayoutTemplate(string $layoutName, string $moduleName): string
    {
        return "{{-- Generated layout for {$moduleName} module --}}\n<!DOCTYPE html>\n<html lang=\"{{ str_replace('_', '-', app()->getLocale()) }}\">\n<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <title>@yield('title', config('app.name'))</title>\n    @vite(['resources/css/app.css', 'resources/js/app.js'])\n</head>\n<body>\n    @yield('content')\n</body>\n</html>\n";
    }

    /**
     * Clean up generated files
     */
    public static function cleanupGenerated(): array
    {
        $generatedPath = app_path('Generated');
        $viewsPath = resource_path('views/generated');

        $results = [];

        if (File::exists($generatedPath)) {
            File::deleteDirectory($generatedPath);
            $results['app_generated'] = 'Deleted';
        }

        if (File::exists($viewsPath)) {
            File::deleteDirectory($viewsPath);
            $results['views_generated'] = 'Deleted';
        }

        return $results;
    }
}
