<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Services\CodeGeneratorService;
use App\Models\SetupModule;
use Illuminate\Support\Facades\Log;

class GenerateModuleCode
{
    use AsAction;

    /**
     * Generate code cho tất cả modules đã được enable
     */
    public function handle(): array
    {
        try {
            // Lấy danh sách modules enabled từ session hoặc database
            $enabledModules = $this->getEnabledModules();
            
            if (empty($enabledModules)) {
                return [
                    'success' => true,
                    'message' => 'Không có modules nào được enable để generate code',
                    'modules' => []
                ];
            }

            Log::info('Starting code generation for modules', ['modules' => $enabledModules]);

            // Generate code cho từng module
            $results = CodeGeneratorService::generateEnabledModules($enabledModules);

            // Tạo summary
            $summary = $this->createSummary($results);

            Log::info('Code generation completed', ['summary' => $summary]);

            return [
                'success' => true,
                'message' => "Đã generate code cho " . count($enabledModules) . " modules",
                'modules' => $enabledModules,
                'results' => $results,
                'summary' => $summary
            ];

        } catch (\Exception $e) {
            Log::error('Error generating module code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi generate code: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách modules đã được enable
     */
    private function getEnabledModules(): array
    {
        // Ưu tiên lấy từ session (trong quá trình setup)
        $moduleConfigs = session('module_configs', []);
        
        if (!empty($moduleConfigs)) {
            $enabledModules = [];
            foreach ($moduleConfigs as $moduleKey => $config) {
                if ($config['enable_module'] ?? false) {
                    $enabledModules[] = $moduleKey;
                }
            }
            return $enabledModules;
        }

        // Fallback: lấy từ database (sau khi setup hoàn thành)
        return SetupModule::where('is_installed', true)
            ->whereJsonContains('configuration->enable_module', true)
            ->pluck('module_name')
            ->toArray();
    }

    /**
     * Tạo summary của quá trình generation
     */
    private function createSummary(array $results): array
    {
        $summary = [
            'total_modules' => count($results),
            'total_files' => 0,
            'success_files' => 0,
            'failed_files' => 0,
            'file_types' => []
        ];

        foreach ($results as $moduleName => $moduleResults) {
            foreach ($moduleResults as $fileType => $files) {
                if (!isset($summary['file_types'][$fileType])) {
                    $summary['file_types'][$fileType] = [
                        'total' => 0,
                        'success' => 0,
                        'failed' => 0
                    ];
                }

                foreach ($files as $file) {
                    $summary['total_files']++;
                    $summary['file_types'][$fileType]['total']++;

                    if ($file['success'] ?? false) {
                        $summary['success_files']++;
                        $summary['file_types'][$fileType]['success']++;
                    } else {
                        $summary['failed_files']++;
                        $summary['file_types'][$fileType]['failed']++;
                    }
                }
            }
        }

        return $summary;
    }

    /**
     * Generate code cho một module cụ thể
     */
    public static function generateSingleModule(string $moduleName): array
    {
        try {
            $results = CodeGeneratorService::generateEnabledModules([$moduleName]);
            
            return [
                'success' => true,
                'message' => "Đã generate code cho module {$moduleName}",
                'results' => $results
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Lỗi khi generate code cho module {$moduleName}: " . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Preview files sẽ được generate cho 9 modules chính
     */
    public static function previewGeneration(array $enabledModules): array
    {
        $preview = [];
        $moduleMapping = [
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

        foreach ($enabledModules as $moduleName) {
            if (isset($moduleMapping[$moduleName])) {
                $preview[$moduleName] = $moduleMapping[$moduleName];
            }
        }

        return $preview;
    }
}
