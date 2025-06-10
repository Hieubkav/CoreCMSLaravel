<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SetupModule;
use Exception;

/**
 * Run Conditional Migrations Action
 * 
 * Chạy migrations dựa trên modules được chọn trong setup wizard
 * Tránh tạo quá nhiều bảng không cần thiết
 */
class RunConditionalMigrations
{
    use AsAction;

    /**
     * Chạy migrations dựa trên modules được chọn
     */
    public function handle(array $selectedModules = []): array
    {
        try {
            $results = [];
            $totalMigrations = 0;
            $successfulMigrations = 0;
            $errors = [];

            // Nếu không có modules được chọn, lấy từ database
            if (empty($selectedModules)) {
                $selectedModules = $this->getSelectedModulesFromDatabase();
            }

            // Lấy mapping migrations cho từng module
            $moduleMigrations = $this->getModuleMigrations();

            foreach ($selectedModules as $moduleName) {
                if (!isset($moduleMigrations[$moduleName])) {
                    continue;
                }

                $migrations = $moduleMigrations[$moduleName];
                $moduleResults = [];

                foreach ($migrations as $migration) {
                    $totalMigrations++;
                    
                    try {
                        // Kiểm tra migration file có tồn tại không
                        $migrationPath = database_path("migrations/{$migration}.php");
                        
                        if (!file_exists($migrationPath)) {
                            $errors[] = "Migration file không tồn tại: {$migration}";
                            continue;
                        }

                        // Chạy migration
                        Artisan::call('migrate', [
                            '--path' => "database/migrations/{$migration}.php",
                            '--force' => true
                        ]);

                        $moduleResults[] = $migration;
                        $successfulMigrations++;
                        
                    } catch (Exception $e) {
                        $errors[] = "Lỗi khi chạy migration {$migration}: " . $e->getMessage();
                        Log::warning("Migration failed: {$migration}", ['error' => $e->getMessage()]);
                    }
                }

                if (!empty($moduleResults)) {
                    $results[$moduleName] = $moduleResults;
                }
            }

            return [
                'success' => true,
                'message' => "Đã chạy {$successfulMigrations}/{$totalMigrations} migrations thành công",
                'results' => $results,
                'total_migrations' => $totalMigrations,
                'successful_migrations' => $successfulMigrations,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Lỗi khi chạy conditional migrations: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách modules được chọn từ database
     */
    private function getSelectedModulesFromDatabase(): array
    {
        try {
            return SetupModule::whereJsonContains('configuration->selected', true)
                ->pluck('module_name')
                ->toArray();
        } catch (Exception $e) {
            Log::warning('Không thể lấy selected modules từ database: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Mapping migrations cho từng module
     */
    private function getModuleMigrations(): array
    {
        return [
            // Core modules (luôn chạy)
            'system_configuration' => [
                '2025_06_09_011413_create_system_configurations_table',
            ],

            // Blog module
            'blog' => [
                '2025_05_09_114931_create_post_categories_table',
                '2025_05_09_122445_create_posts_table',
                '2025_05_09_130000_create_post_images_table',
                '2025_06_09_012729_update_posts_table_add_blog_fields',
            ],

            // Staff module
            'staff' => [
                '2025_06_09_013050_create_staff_table',
                '2025_06_09_013115_create_staff_images_table',
            ],

            // Content sections module
            'content_sections' => [
                '2025_06_09_014043_create_galleries_table',
                '2025_06_09_014105_create_brands_table',
                '2025_06_09_014127_create_faqs_table',
                '2025_06_09_014149_create_statistics_table',
                '2025_06_09_014211_create_testimonials_table',
                '2025_06_09_014232_create_services_table',
                '2025_06_09_014253_create_features_table',
                '2025_06_09_014318_create_partners_table',
                '2025_06_09_014339_create_schedules_table',
                '2025_06_09_014359_create_timelines_table',
            ],

            // E-commerce module
            'ecommerce' => [
                '2025_06_09_020708_create_product_categories_table',
                '2025_06_09_020748_create_products_table',
                '2025_06_09_020832_create_product_attributes_table',
                '2025_06_09_020918_create_product_attribute_values_table',
                '2025_06_09_020956_create_product_variants_table',
                '2025_06_09_021038_create_carts_table',
                '2025_06_09_021118_create_wishlists_table',
                '2025_06_09_021159_create_orders_table',
                '2025_06_09_021241_create_order_items_table',
                '2025_06_09_021319_create_coupons_table',
                '2025_06_09_021359_create_product_reviews_table',
                '2025_06_09_021441_create_shipping_methods_table',
                '2025_06_09_021514_create_payment_methods_table',
                '2025_06_09_021550_create_inventory_logs_table',
            ],

            // Layout components module
            'layout_components' => [
                '2025_05_09_130600_create_sliders_table',
                '2025_06_09_172650_create_menu_items_table_new',
            ],

            // Settings expansion module
            'settings_expansion' => [
                '2025_05_31_131928_update_settings_table_remove_dmca_add_messenger',
                '2025_06_03_195415_add_placeholder_image_to_settings_table',
                '2025_06_09_023426_create_tax_settings_table',
                '2025_06_09_023454_create_email_templates_table',
                '2025_06_09_023534_create_notification_settings_table',
                '2025_06_09_023611_create_backup_settings_table',
                '2025_06_09_173800_create_website_settings_table',
            ],

            // Web design management module
            'web_design_management' => [
                '2025_06_09_024842_create_theme_settings_table',
                '2025_06_09_024939_create_page_builders_table',
                '2025_06_09_025036_create_widget_settings_table',
                '2025_06_09_030000_create_web_designs_table',
            ],

            // Advanced features module
            'advanced_features' => [
                '2025_06_09_175000_create_multi_languages_table',
                '2025_06_09_175100_create_advanced_searches_table',
                '2025_06_09_175200_create_analytics_reportings_table',
                '2025_06_09_175300_create_automation_workflows_table',
            ],
        ];
    }

    /**
     * Kiểm tra migration đã chạy chưa
     */
    private function isMigrationRan(string $migration): bool
    {
        try {
            $result = DB::table('migrations')
                ->where('migration', $migration)
                ->exists();
            
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Lấy danh sách migrations có thể chạy cho modules được chọn
     */
    public static function getAvailableMigrations(array $selectedModules): array
    {
        $instance = new static();
        $moduleMigrations = $instance->getModuleMigrations();
        $availableMigrations = [];

        foreach ($selectedModules as $moduleName) {
            if (isset($moduleMigrations[$moduleName])) {
                $availableMigrations[$moduleName] = $moduleMigrations[$moduleName];
            }
        }

        return $availableMigrations;
    }
}
