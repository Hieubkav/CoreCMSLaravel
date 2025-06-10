<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Exception;

class CodeGenerator
{
    /**
     * Core Models cần giữ lại khi reset (không xóa)
     */
    private static array $coreModels = [
        'User.php',
        'Setting.php',
        'SetupModule.php',
        'Visitor.php',
        'FrontendConfiguration.php',
        'AdminConfiguration.php',
    ];

    /**
     * Mapping giữa setup steps và files cần tạo
     * Quản lý: Models, Filament files - Seeders và Views giữ nguyên để đầy đủ
     */
    private static array $stepFileMapping = [
        'database' => [
            'migrations' => [
                'create_frontend_configurations_table.php',
                'create_admin_configurations_table.php',
            ],
            'models' => [
                'FrontendConfiguration.php',
                'AdminConfiguration.php',
            ],
            'filament_resources' => [],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'user-roles' => [
            'models' => [
                // User.php là core model, không sinh
            ],
            'filament_resources' => [
                'UserResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'blog' => [
            'migrations' => [
                'create_posts_table.php',
                'create_cat_posts_table.php',
                'create_post_images_table.php',
            ],
            'models' => [
                'Post.php',
                'CatPost.php', // PostCategory
                'PostImage.php',
            ],
            'filament_resources' => [
                'PostResource.php',
                'PostCategoryResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'staff' => [
            'models' => [
                'Staff.php',
                'StaffImage.php',
            ],
            'filament_resources' => [
                'StaffResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'content' => [
            'models' => [
                'Slider.php',
                'Gallery.php',
                'FAQ.php',
                'Testimonial.php',
            ],
            'filament_resources' => [
                'SliderResource.php',
                'GalleryResource.php',
                'FAQResource.php',
                'TestimonialResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'ecommerce' => [
            'models' => [
                'Product.php',
                'ProductCategory.php',
                'ProductImage.php',
                'ProductAttribute.php',
                'ProductAttributeValue.php',
                'ProductVariant.php',
                'ProductReview.php',
                'Brand.php',
                'Order.php',
                'OrderItem.php',
                'Coupon.php',
                'Cart.php',
                'Wishlist.php',
                'PaymentMethod.php',
                'ShippingMethod.php',
                'TaxSetting.php',
                'InventoryLog.php',
            ],
            'filament_resources' => [
                'ProductResource.php',
                'ProductCategoryResource.php',
                'BrandResource.php',
                'OrderResource.php',
                'CouponResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'layout' => [
            'models' => [
                'MenuItem.php',
            ],
            'filament_resources' => [
                'MenuItemResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [],
        ],
        'settings' => [
            'models' => [
                'SystemConfiguration.php',
                'ThemeSetting.php',
                'EmailTemplate.php',
                'NotificationSetting.php',
                'BackupSetting.php',
                'WidgetSetting.php',
            ],
            'filament_resources' => [
                'SystemConfigurationResource.php',
            ],
            'filament_pages' => [
                'ManageSettings.php',
            ],
            'filament_widgets' => [],
        ],
        'webdesign' => [
            'models' => [
                'WebDesign.php',
                'PageBuilder.php',
            ],
            'filament_resources' => [],
            'filament_pages' => [
                'ManageWebDesign.php',
                'ImageManager.php',
            ],
            'filament_widgets' => [
                'WebDesignStatsWidget.php',
            ],
        ],
        'advanced' => [
            'models' => [
                'Partner.php',
                'Service.php',
                'Feature.php',
                'Statistic.php',
                'Timeline.php',
                'Schedule.php',
                'CourseImage.php',
            ],
            'filament_resources' => [
                'PartnerResource.php',
                'ServiceResource.php',
                'FeatureResource.php',
                'StatisticResource.php',
                'TimelineResource.php',
                'ScheduleResource.php',
            ],
            'filament_pages' => [],
            'filament_widgets' => [
                'StatsOverviewWidget.php',
                'QuickActionsWidget.php',
            ],
        ],
    ];

    /**
     * Tạo templates từ existing files
     */
    public static function createTemplatesFromExisting(): array
    {
        try {
            $results = [];
            $templatesPath = base_path('storage/setup-templates');
            
            // Tạo thư mục templates nếu chưa có
            if (!File::exists($templatesPath)) {
                File::makeDirectory($templatesPath, 0755, true);
            }

            // Tạo templates cho Migrations
            self::createMigrationTemplates($templatesPath, $results);

            // Tạo templates cho Models
            self::createModelTemplates($templatesPath, $results);

            // Tạo templates cho Filament Resources
            self::createFilamentResourceTemplates($templatesPath, $results);

            // Tạo templates cho Filament Pages
            self::createFilamentPageTemplates($templatesPath, $results);

            // Tạo templates cho Filament Widgets
            self::createFilamentWidgetTemplates($templatesPath, $results);

            return [
                'success' => true,
                'message' => 'Đã tạo templates thành công!',
                'details' => $results
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tạo templates cho Migrations
     */
    private static function createMigrationTemplates(string $templatesPath, array &$results): void
    {
        $templateMigrationsPath = $templatesPath . '/migrations';

        if (!File::exists($templateMigrationsPath)) {
            File::makeDirectory($templateMigrationsPath, 0755, true);
        }

        $createdTemplates = [];

        // Tạo template cho frontend_configurations
        $frontendMigrationContent = self::getFrontendConfigurationMigrationTemplate();
        File::put($templateMigrationsPath . '/create_frontend_configurations_table.php', $frontendMigrationContent);
        $createdTemplates[] = 'create_frontend_configurations_table.php';

        // Tạo template cho admin_configurations
        $adminMigrationContent = self::getAdminConfigurationMigrationTemplate();
        File::put($templateMigrationsPath . '/create_admin_configurations_table.php', $adminMigrationContent);
        $createdTemplates[] = 'create_admin_configurations_table.php';

        $results['migrations'] = [
            'status' => 'success',
            'message' => 'Đã tạo ' . count($createdTemplates) . ' migration templates',
            'templates' => $createdTemplates
        ];
    }

    /**
     * Tạo templates cho Models
     */
    private static function createModelTemplates(string $templatesPath, array &$results): void
    {
        $modelsPath = app_path('Models');
        $templateModelsPath = $templatesPath . '/models';

        if (!File::exists($templateModelsPath)) {
            File::makeDirectory($templateModelsPath, 0755, true);
        }

        $createdTemplates = [];

        if (File::exists($modelsPath)) {
            $models = File::files($modelsPath);

            foreach ($models as $model) {
                $fileName = $model->getFilename();

                // Chỉ tạo template cho non-core models
                if (!in_array($fileName, self::$coreModels)) {
                    File::copy($model->getPathname(), $templateModelsPath . '/' . $fileName);
                    $createdTemplates[] = $fileName;
                }
            }
        }

        $results['models'] = [
            'status' => 'success',
            'message' => 'Đã tạo ' . count($createdTemplates) . ' model templates',
            'templates' => $createdTemplates
        ];
    }

    /**
     * Tạo templates cho Filament Resources
     */
    private static function createFilamentResourceTemplates(string $templatesPath, array &$results): void
    {
        $resourcesPath = app_path('Filament/Admin/Resources');
        $templateResourcesPath = $templatesPath . '/filament/resources';
        
        if (!File::exists($templateResourcesPath)) {
            File::makeDirectory($templateResourcesPath, 0755, true);
        }

        $createdTemplates = [];
        
        if (File::exists($resourcesPath)) {
            $resources = File::directories($resourcesPath);
            
            foreach ($resources as $resourceDir) {
                $resourceName = basename($resourceDir);
                $templateDir = $templateResourcesPath . '/' . $resourceName;
                
                // Copy toàn bộ thư mục resource
                File::copyDirectory($resourceDir, $templateDir);
                $createdTemplates[] = $resourceName;
            }
            
            // Copy các file resource đơn lẻ
            $resourceFiles = File::files($resourcesPath);
            foreach ($resourceFiles as $file) {
                $fileName = $file->getFilename();
                File::copy($file->getPathname(), $templateResourcesPath . '/' . $fileName);
                $createdTemplates[] = $fileName;
            }
        }

        $results['filament_resources'] = [
            'status' => 'success',
            'message' => 'Đã tạo ' . count($createdTemplates) . ' resource templates',
            'templates' => $createdTemplates
        ];
    }

    /**
     * Tạo templates cho Filament Pages
     */
    private static function createFilamentPageTemplates(string $templatesPath, array &$results): void
    {
        $pagesPath = app_path('Filament/Admin/Pages');
        $templatePagesPath = $templatesPath . '/filament/pages';
        
        if (!File::exists($templatePagesPath)) {
            File::makeDirectory($templatePagesPath, 0755, true);
        }

        $createdTemplates = [];
        
        if (File::exists($pagesPath)) {
            $pages = File::files($pagesPath);
            
            foreach ($pages as $page) {
                $fileName = $page->getFilename();
                // Bỏ qua Dashboard.php vì đây là core file
                if ($fileName !== 'Dashboard.php') {
                    File::copy($page->getPathname(), $templatePagesPath . '/' . $fileName);
                    $createdTemplates[] = $fileName;
                }
            }
        }

        $results['filament_pages'] = [
            'status' => 'success',
            'message' => 'Đã tạo ' . count($createdTemplates) . ' page templates',
            'templates' => $createdTemplates
        ];
    }

    /**
     * Tạo templates cho Filament Widgets
     */
    private static function createFilamentWidgetTemplates(string $templatesPath, array &$results): void
    {
        $widgetsPath = app_path('Filament/Admin/Widgets');
        $templateWidgetsPath = $templatesPath . '/filament/widgets';
        
        if (!File::exists($templateWidgetsPath)) {
            File::makeDirectory($templateWidgetsPath, 0755, true);
        }

        $createdTemplates = [];
        
        if (File::exists($widgetsPath)) {
            $widgets = File::files($widgetsPath);
            
            foreach ($widgets as $widget) {
                $fileName = $widget->getFilename();
                File::copy($widget->getPathname(), $templateWidgetsPath . '/' . $fileName);
                $createdTemplates[] = $fileName;
            }
        }

        $results['filament_widgets'] = [
            'status' => 'success',
            'message' => 'Đã tạo ' . count($createdTemplates) . ' widget templates',
            'templates' => $createdTemplates
        ];
    }

    /**
     * Sinh code cho một setup step cụ thể
     */
    public static function generateForStep(string $step): array
    {
        try {
            if (!isset(self::$stepFileMapping[$step])) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy mapping cho step: $step"
                ];
            }

            $results = [];
            $mapping = self::$stepFileMapping[$step];
            $templatesPath = base_path('storage/setup-templates');

            // Sinh Migrations
            if (!empty($mapping['migrations'])) {
                self::generateMigrations($mapping['migrations'], $templatesPath, $results);
            }

            // Sinh Models
            if (!empty($mapping['models'])) {
                self::generateModels($mapping['models'], $templatesPath, $results);
            }

            // Sinh Filament Resources
            if (!empty($mapping['filament_resources'])) {
                self::generateFilamentResources($mapping['filament_resources'], $templatesPath, $results);
            }

            // Sinh Filament Pages
            if (!empty($mapping['filament_pages'])) {
                self::generateFilamentPages($mapping['filament_pages'], $templatesPath, $results);
            }

            // Sinh Filament Widgets
            if (!empty($mapping['filament_widgets'])) {
                self::generateFilamentWidgets($mapping['filament_widgets'], $templatesPath, $results);
            }

            return [
                'success' => true,
                'message' => "Đã sinh code cho step: $step",
                'details' => $results
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi sinh code: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sinh Migrations từ templates
     */
    private static function generateMigrations(array $migrations, string $templatesPath, array &$results): void
    {
        $targetPath = database_path('migrations');
        $templatePath = $templatesPath . '/migrations';

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $generatedFiles = [];

        foreach ($migrations as $migration) {
            $templateFilePath = $templatePath . '/' . $migration;

            if (File::exists($templateFilePath)) {
                // Tạo tên file migration với timestamp
                $timestamp = date('Y_m_d_His');
                $migrationFileName = $timestamp . '_' . $migration;

                File::copy($templateFilePath, $targetPath . '/' . $migrationFileName);
                $generatedFiles[] = $migrationFileName;
            }
        }

        $results['migrations'] = [
            'status' => 'success',
            'message' => 'Đã sinh ' . count($generatedFiles) . ' Migrations',
            'files' => $generatedFiles
        ];
    }

    /**
     * Sinh Models từ templates
     */
    private static function generateModels(array $models, string $templatesPath, array &$results): void
    {
        $targetPath = app_path('Models');
        $templatePath = $templatesPath . '/models';

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $generatedFiles = [];

        foreach ($models as $model) {
            $templateFilePath = $templatePath . '/' . $model;

            if (File::exists($templateFilePath)) {
                File::copy($templateFilePath, $targetPath . '/' . $model);
                $generatedFiles[] = $model;
            }
        }

        $results['models'] = [
            'status' => 'success',
            'message' => 'Đã sinh ' . count($generatedFiles) . ' Models',
            'files' => $generatedFiles
        ];
    }

    /**
     * Sinh Filament Resources từ templates
     */
    private static function generateFilamentResources(array $resources, string $templatesPath, array &$results): void
    {
        $targetPath = app_path('Filament/Admin/Resources');
        $templatePath = $templatesPath . '/filament/resources';
        
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $generatedFiles = [];

        foreach ($resources as $resource) {
            $resourceName = str_replace('.php', '', $resource);
            $templateResourcePath = $templatePath . '/' . $resourceName;
            $templateFilePath = $templatePath . '/' . $resource;
            
            // Nếu là thư mục resource (có Pages, RelationManagers)
            if (File::exists($templateResourcePath)) {
                $targetResourcePath = $targetPath . '/' . $resourceName;
                File::copyDirectory($templateResourcePath, $targetResourcePath);
                $generatedFiles[] = $resourceName . ' (directory)';
            }
            
            // Nếu là file resource đơn lẻ
            if (File::exists($templateFilePath)) {
                File::copy($templateFilePath, $targetPath . '/' . $resource);
                $generatedFiles[] = $resource;
            }
        }

        $results['filament_resources'] = [
            'status' => 'success',
            'message' => 'Đã sinh ' . count($generatedFiles) . ' Filament resources',
            'files' => $generatedFiles
        ];
    }

    /**
     * Sinh Filament Pages từ templates
     */
    private static function generateFilamentPages(array $pages, string $templatesPath, array &$results): void
    {
        $targetPath = app_path('Filament/Admin/Pages');
        $templatePath = $templatesPath . '/filament/pages';

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $generatedFiles = [];

        foreach ($pages as $page) {
            $templateFilePath = $templatePath . '/' . $page;

            if (File::exists($templateFilePath)) {
                File::copy($templateFilePath, $targetPath . '/' . $page);
                $generatedFiles[] = $page;
            }
        }

        $results['filament_pages'] = [
            'status' => 'success',
            'message' => 'Đã sinh ' . count($generatedFiles) . ' Filament pages',
            'files' => $generatedFiles
        ];
    }

    /**
     * Sinh Filament Widgets từ templates
     */
    private static function generateFilamentWidgets(array $widgets, string $templatesPath, array &$results): void
    {
        $targetPath = app_path('Filament/Admin/Widgets');
        $templatePath = $templatesPath . '/filament/widgets';

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        $generatedFiles = [];

        foreach ($widgets as $widget) {
            $templateFilePath = $templatePath . '/' . $widget;

            if (File::exists($templateFilePath)) {
                File::copy($templateFilePath, $targetPath . '/' . $widget);
                $generatedFiles[] = $widget;
            }
        }

        $results['filament_widgets'] = [
            'status' => 'success',
            'message' => 'Đã sinh ' . count($generatedFiles) . ' Filament widgets',
            'files' => $generatedFiles
        ];
    }

    /**
     * Lấy danh sách files cần tạo cho một step
     */
    public static function getFilesForStep(string $step): array
    {
        return self::$stepFileMapping[$step] ?? [];
    }

    /**
     * Lấy tất cả steps có mapping
     */
    public static function getAllSteps(): array
    {
        return array_keys(self::$stepFileMapping);
    }

    /**
     * Template cho frontend_configurations migration
     */
    private static function getFrontendConfigurationMigrationTemplate(): string
    {
        return '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(\'frontend_configurations\', function (Blueprint $table) {
            $table->id();

            // Theme Configuration
            $table->string(\'theme_mode\')->default(\'light\'); // light, dark, auto
            $table->string(\'design_style\')->default(\'minimalist\'); // minimalist, modern, classic
            $table->string(\'icon_system\')->default(\'fontawesome\'); // fontawesome, heroicons, custom

            // Color Scheme
            $table->string(\'primary_color\')->default(\'#dc2626\'); // Red-600
            $table->string(\'secondary_color\')->default(\'#f97316\'); // Orange-500
            $table->string(\'accent_color\')->default(\'#059669\'); // Emerald-600
            $table->string(\'background_color\')->default(\'#ffffff\');
            $table->string(\'text_color\')->default(\'#1f2937\');

            // Typography
            $table->string(\'font_family\')->default(\'Inter\'); // Inter, Roboto, Open Sans
            $table->string(\'font_size\')->default(\'base\'); // sm, base, lg
            $table->string(\'font_weight\')->default(\'normal\'); // light, normal, medium, semibold

            // Layout Settings
            $table->string(\'container_width\')->default(\'max-w-7xl\'); // max-w-6xl, max-w-7xl, max-w-full
            $table->boolean(\'enable_breadcrumbs\')->default(true);
            $table->boolean(\'enable_back_to_top\')->default(true);
            $table->boolean(\'enable_loading_spinner\')->default(true);

            // Navigation
            $table->boolean(\'sticky_navbar\')->default(true);
            $table->boolean(\'show_search_bar\')->default(true);
            $table->boolean(\'show_language_switcher\')->default(false);
            $table->string(\'menu_style\')->default(\'horizontal\'); // horizontal, vertical, mega

            // Footer
            $table->boolean(\'show_footer_social\')->default(false);
            $table->boolean(\'show_footer_newsletter\')->default(false);
            $table->text(\'footer_copyright\')->nullable();

            // Performance & SEO
            $table->boolean(\'enable_lazy_loading\')->default(true);
            $table->boolean(\'enable_image_optimization\')->default(true);
            $table->boolean(\'enable_minification\')->default(true);
            $table->boolean(\'enable_caching\')->default(true);

            // Error Pages
            $table->json(\'error_pages\')->nullable(); // [\'404\', \'500\', \'503\']

            // Custom CSS/JS
            $table->text(\'custom_css\')->nullable();
            $table->text(\'custom_js\')->nullable();
            $table->text(\'custom_head_tags\')->nullable();

            // Metadata
            $table->boolean(\'is_active\')->default(true);
            $table->string(\'created_by\')->nullable();
            $table->string(\'updated_by\')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\'frontend_configurations\');
    }
};';
    }

    /**
     * Template cho admin_configurations migration
     */
    private static function getAdminConfigurationMigrationTemplate(): string
    {
        return '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(\'admin_configurations\', function (Blueprint $table) {
            $table->id();

            // Dashboard Settings
            $table->string(\'dashboard_title\')->default(\'Admin Dashboard\');
            $table->string(\'dashboard_theme\')->default(\'light\'); // light, dark
            $table->string(\'sidebar_style\')->default(\'expanded\'); // expanded, collapsed, overlay
            $table->boolean(\'enable_dark_mode_toggle\')->default(true);

            // Navigation & Menu
            $table->boolean(\'show_dashboard_widgets\')->default(true);
            $table->boolean(\'show_quick_actions\')->default(true);
            $table->boolean(\'enable_breadcrumbs\')->default(true);
            $table->string(\'menu_style\')->default(\'sidebar\'); // sidebar, topbar, hybrid

            // Data Management
            $table->integer(\'records_per_page\')->default(25); // 10, 25, 50, 100
            $table->boolean(\'enable_bulk_actions\')->default(true);
            $table->boolean(\'enable_export_functions\')->default(true);
            $table->boolean(\'enable_import_functions\')->default(false);
            $table->boolean(\'enable_advanced_filters\')->default(true);

            // User Management
            $table->boolean(\'enable_user_registration\')->default(false);
            $table->boolean(\'require_email_verification\')->default(true);
            $table->boolean(\'enable_two_factor_auth\')->default(false);
            $table->string(\'default_user_role\')->default(\'user\');

            // Content Management
            $table->boolean(\'enable_rich_text_editor\')->default(true);
            $table->boolean(\'enable_media_library\')->default(true);
            $table->boolean(\'enable_file_manager\')->default(true);
            $table->string(\'default_image_quality\')->default(\'80\'); // 60, 80, 90, 100
            $table->string(\'max_upload_size\')->default(\'10MB\'); // 5MB, 10MB, 20MB, 50MB

            // SEO & Analytics
            $table->boolean(\'enable_seo_tools\')->default(true);
            $table->boolean(\'enable_analytics_dashboard\')->default(true);
            $table->boolean(\'enable_visitor_tracking\')->default(true);
            $table->boolean(\'seo_auto_generate\')->default(true);
            $table->string(\'default_description\')->default(\'Powered by Core Laravel Framework\');

            // Security Settings
            $table->boolean(\'enable_activity_log\')->default(true);
            $table->boolean(\'enable_login_attempts_limit\')->default(true);
            $table->integer(\'max_login_attempts\')->default(5);
            $table->boolean(\'enable_ip_whitelist\')->default(false);
            $table->json(\'allowed_ips\')->nullable();

            // Backup & Maintenance
            $table->boolean(\'enable_auto_backup\')->default(false);
            $table->string(\'backup_frequency\')->default(\'weekly\'); // daily, weekly, monthly
            $table->boolean(\'enable_maintenance_mode\')->default(false);
            $table->text(\'maintenance_message\')->nullable();

            // Notifications
            $table->boolean(\'enable_email_notifications\')->default(true);
            $table->boolean(\'enable_browser_notifications\')->default(false);
            $table->boolean(\'enable_slack_notifications\')->default(false);
            $table->string(\'notification_email\')->nullable();

            // Performance
            $table->boolean(\'enable_query_optimization\')->default(true);
            $table->boolean(\'enable_cache_management\')->default(true);
            $table->boolean(\'enable_database_optimization\')->default(false);
            $table->string(\'cache_driver\')->default(\'file\'); // file, redis, memcached

            // Customization
            $table->string(\'admin_logo\')->nullable();
            $table->string(\'admin_favicon\')->nullable();
            $table->text(\'custom_admin_css\')->nullable();
            $table->text(\'custom_admin_js\')->nullable();

            // Metadata
            $table->boolean(\'is_active\')->default(true);
            $table->string(\'created_by\')->nullable();
            $table->string(\'updated_by\')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\'admin_configurations\');
    }
};';
    }

    /**
     * Kiểm tra xem templates đã tồn tại chưa
     */
    public static function templatesExist(): bool
    {
        $templatesPath = base_path('storage/setup-templates');
        return File::exists($templatesPath . '/filament');
    }

    /**
     * Xóa tất cả generated files (Models + Filament files)
     */
    public static function cleanupGeneratedFiles(): array
    {
        try {
            $deletedFiles = [];

            // Xóa Models (trừ core models)
            self::cleanupModels($deletedFiles);

            // Xóa Filament Resources (trừ core files)
            self::cleanupFilamentResources($deletedFiles);

            // Xóa Filament Pages (trừ Dashboard.php)
            self::cleanupFilamentPages($deletedFiles);

            // Xóa Filament Widgets
            self::cleanupFilamentWidgets($deletedFiles);

            return [
                'success' => true,
                'message' => 'Đã xóa ' . count($deletedFiles) . ' generated files (Models + Filament)',
                'files' => $deletedFiles
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa generated files: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xóa Models (trừ core models)
     */
    private static function cleanupModels(array &$deletedFiles): void
    {
        $modelsPath = app_path('Models');

        if (!File::exists($modelsPath)) {
            return;
        }

        $modelFiles = File::files($modelsPath);
        foreach ($modelFiles as $file) {
            $fileName = $file->getFilename();

            // Chỉ xóa nếu không phải core model
            if (!in_array($fileName, self::$coreModels)) {
                File::delete($file->getPathname());
                $deletedFiles[] = "Models/{$fileName}";
            }
        }
    }

    /**
     * Xóa Filament Resources
     */
    private static function cleanupFilamentResources(array &$deletedFiles): void
    {
        $resourcesPath = app_path('Filament/Admin/Resources');

        if (!File::exists($resourcesPath)) {
            return;
        }

        // Core resources cần giữ lại
        $coreResources = ['UserResource', 'UserResource.php'];

        // Xóa thư mục resources
        $resourceDirs = File::directories($resourcesPath);
        foreach ($resourceDirs as $dir) {
            $dirName = basename($dir);
            if (!in_array($dirName, $coreResources)) {
                File::deleteDirectory($dir);
                $deletedFiles[] = "Resources/{$dirName}";
            }
        }

        // Xóa file resources đơn lẻ
        $resourceFiles = File::files($resourcesPath);
        foreach ($resourceFiles as $file) {
            $fileName = $file->getFilename();
            if (!in_array($fileName, $coreResources)) {
                File::delete($file->getPathname());
                $deletedFiles[] = "Resources/{$fileName}";
            }
        }
    }

    /**
     * Xóa Filament Pages
     */
    private static function cleanupFilamentPages(array &$deletedFiles): void
    {
        $pagesPath = app_path('Filament/Admin/Pages');

        if (!File::exists($pagesPath)) {
            return;
        }

        // Core pages cần giữ lại
        $corePages = ['Dashboard.php'];

        $pageFiles = File::files($pagesPath);
        foreach ($pageFiles as $file) {
            $fileName = $file->getFilename();
            if (!in_array($fileName, $corePages)) {
                File::delete($file->getPathname());
                $deletedFiles[] = "Pages/{$fileName}";
            }
        }
    }

    /**
     * Xóa Filament Widgets
     */
    private static function cleanupFilamentWidgets(array &$deletedFiles): void
    {
        $widgetsPath = app_path('Filament/Admin/Widgets');

        if (!File::exists($widgetsPath)) {
            return;
        }

        // Core widgets cần giữ lại (nếu có)
        $coreWidgets = [];

        $widgetFiles = File::files($widgetsPath);
        foreach ($widgetFiles as $file) {
            $fileName = $file->getFilename();
            if (!in_array($fileName, $coreWidgets)) {
                File::delete($file->getPathname());
                $deletedFiles[] = "Widgets/{$fileName}";
            }
        }
    }
}
