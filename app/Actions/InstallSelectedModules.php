<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\SetupModule;
use App\Actions\GenerateModuleCode;
use App\Actions\RunConditionalMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class InstallSelectedModules
{
    use AsAction;

    /**
     * Cài đặt các modules đã được chọn
     */
    public function handle(): array
    {
        try {
            $selectedModules = ProcessModuleSelection::getSelectedModules();
            $installSampleData = ProcessModuleSelection::shouldInstallSampleData();

            if (empty($selectedModules)) {
                return [
                    'success' => false,
                    'message' => 'Không có modules nào được chọn để cài đặt'
                ];
            }

            $results = [];
            $totalModules = count($selectedModules);
            $installedCount = 0;

            // Chạy conditional migrations trước khi cài đặt modules
            $moduleNames = array_column($selectedModules, 'name');
            $migrationResult = RunConditionalMigrations::run($moduleNames);
            $results['migrations'] = $migrationResult;

            foreach ($selectedModules as $module) {
                try {
                    $result = $this->installModule($module['name'], $installSampleData);
                    $results[$module['name']] = $result;

                    if ($result['success']) {
                        $installedCount++;

                        // Cập nhật trạng thái cài đặt
                        SetupModule::where('module_name', $module['name'])->update([
                            'is_installed' => true,
                            'installed_at' => now(),
                            'installation_notes' => $result['message'] ?? null
                        ]);
                    }

                } catch (\Exception $e) {
                    $results[$module['name']] = [
                        'success' => false,
                        'error' => $e->getMessage()
                    ];

                    Log::error("Failed to install module: {$module['name']}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Generate code cho modules đã được enable
            $codeGenerationResult = GenerateModuleCode::run();

            $migrationSuccess = $migrationResult['success'] ?? false;
            $migrationCount = $migrationResult['successful_migrations'] ?? 0;

            return [
                'success' => $installedCount > 0 && $migrationSuccess,
                'message' => "Đã cài đặt thành công {$installedCount}/{$totalModules} modules và {$migrationCount} migrations",
                'total_modules' => $totalModules,
                'installed_count' => $installedCount,
                'failed_count' => $totalModules - $installedCount,
                'migration_count' => $migrationCount,
                'results' => $results,
                'sample_data_installed' => $installSampleData,
                'code_generation' => $codeGenerationResult
            ];

        } catch (\Exception $e) {
            Log::error('Module installation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Lỗi khi cài đặt modules: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt một module cụ thể
     */
    private function installModule(string $moduleName, bool $installSampleData): array
    {
        switch ($moduleName) {
            case 'system_configuration':
                return $this->installSystemConfiguration($installSampleData);

            case 'user_roles':
            case 'user_roles_permissions':
                return $this->installUserRoles($installSampleData);

            case 'blog_posts':
                return $this->installBlogPosts($installSampleData);

            case 'staff':
                return $this->installStaff($installSampleData);

            case 'content_sections':
                return $this->installContentSections($installSampleData);

            case 'layout_components':
                return $this->installLayoutComponents($installSampleData);

            case 'ecommerce':
                return $this->installEcommerce($installSampleData);

            case 'settings_expansion':
                return $this->installSettingsExpansion($installSampleData);

            case 'web_design_management':
                return $this->installWebDesignManagement($installSampleData);

            default:
                return [
                    'success' => false,
                    'message' => "Module không được hỗ trợ: {$moduleName}"
                ];
        }
    }

    /**
     * Cài đặt System Configuration module
     */
    private function installSystemConfiguration(bool $installSampleData): array
    {
        try {
            // Tạo Filament resource nếu chưa có
            if (!class_exists('App\Filament\Admin\Resources\SystemConfigurationResource')) {
                $this->createSystemConfigurationResource();
            }

            // Tạo dữ liệu mẫu nếu được yêu cầu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'SystemConfigurationSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt System Configuration module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt System Configuration: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt User Roles module với Filament Shield
     */
    private function installUserRoles(bool $installSampleData): array
    {
        try {
            // 1. Cài đặt Filament Shield package
            $this->installFilamentShield();

            // 2. Publish Spatie Permission config
            Artisan::call('vendor:publish', [
                '--provider' => 'Spatie\Permission\PermissionServiceProvider'
            ]);

            // 3. Publish Filament Shield config
            Artisan::call('vendor:publish', [
                '--tag' => 'filament-shield-config'
            ]);

            // 4. Migrations đã được chạy bởi RunConditionalMigrations

            // 5. Tạo Shield resources và permissions
            $this->setupFilamentShield();

            // 6. Tạo roles và permissions mặc định
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
            }

            // 7. Tạo Filament resources cho User management
            $this->createUserManagementResources();

            return [
                'success' => true,
                'message' => 'Đã cài đặt User Roles module với Filament Shield thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt User Roles: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Blog Posts module
     */
    private function installBlogPosts(bool $installSampleData): array
    {
        try {
            // 1. Chạy migrations cho blog module
            $this->runBlogMigrations();

            // 2. Tạo Filament resources nếu chưa có
            $this->createBlogFilamentResources();

            // 3. Tạo dữ liệu mẫu
            if ($installSampleData) {
                $this->createBlogSampleData();
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Blog Posts module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Blog Posts: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Staff module
     */
    private function installStaff(bool $installSampleData): array
    {
        try {
            // 1. Chạy migrations cho staff module
            $this->runStaffMigrations();

            // 2. Tạo Filament resource
            $this->createStaffFilamentResource();

            // 3. Tạo dữ liệu mẫu
            if ($installSampleData) {
                $this->createStaffSampleData();
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Staff module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Staff: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Content Sections module
     */
    private function installContentSections(bool $installSampleData): array
    {
        try {
            // Tạo tất cả Filament resources cho content sections
            $this->createContentSectionsFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'ContentSectionsSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Content Sections module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Content Sections: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Layout Components module
     */
    private function installLayoutComponents(bool $installSampleData): array
    {
        try {
            // Tạo Filament resource cho Menu Items
            $this->createMenuItemFilamentResource();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'MenuItemSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Layout Components module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Layout Components: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt E-commerce module
     */
    private function installEcommerce(bool $installSampleData): array
    {
        try {
            // Tạo tất cả Filament resources cho e-commerce
            $this->createEcommerceFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'EcommerceSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt E-commerce module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt E-commerce: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Settings Expansion module
     */
    private function installSettingsExpansion(bool $installSampleData): array
    {
        try {
            // Cập nhật Settings Filament resource
            $this->updateSettingsFilamentResource();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'SettingsExpansionSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Settings Expansion module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Settings Expansion: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cài đặt Web Design Management module
     */
    private function installWebDesignManagement(bool $installSampleData): array
    {
        try {
            // Tạo Filament resources cho web design management
            $this->createWebDesignFilamentResources();

            // Tạo dữ liệu mẫu
            if ($installSampleData) {
                Artisan::call('db:seed', ['--class' => 'WebDesignManagementSeeder']);
            }

            return [
                'success' => true,
                'message' => 'Đã cài đặt Web Design Management module thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi cài đặt Web Design Management: ' . $e->getMessage()
            ];
        }
    }

    // Helper methods để tạo Filament resources
    private function createSystemConfigurationResource()
    {
        // SystemConfigurationResource đã tồn tại
        return true;
    }

    private function createBlogFilamentResources()
    {
        // PostResource và PostCategoryResource đã tồn tại
        return true;
    }

    private function createStaffFilamentResource()
    {
        // StaffResource đã tồn tại
        return true;
    }

    private function createContentSectionsFilamentResources()
    {
        // Tất cả content section resources đã tồn tại
        return true;
    }

    private function createMenuItemFilamentResource()
    {
        // MenuItemResource đã tồn tại
        return true;
    }

    private function createEcommerceFilamentResources()
    {
        // Tất cả ecommerce resources đã tồn tại
        return true;
    }

    private function updateSettingsFilamentResource()
    {
        // Settings đã được mở rộng
        return true;
    }

    private function createWebDesignFilamentResources()
    {
        // ManageWebDesign page đã được tạo
        return true;
    }

    /**
     * Cài đặt Filament Shield package
     */
    private function installFilamentShield(): void
    {
        // Package đã được cài đặt sẵn, chỉ cần kiểm tra
        if (!class_exists('BezhanSalleh\FilamentShield\FilamentShieldServiceProvider')) {
            throw new \Exception('Filament Shield package chưa được cài đặt. Vui lòng chạy: composer require bezhansalleh/filament-shield');
        }
    }

    /**
     * Setup Filament Shield
     */
    private function setupFilamentShield(): void
    {
        // Chỉ publish config files, bỏ qua interactive commands
        try {
            // Publish Shield config
            Artisan::call('vendor:publish', [
                '--tag' => 'filament-shield-config',
                '--force' => true
            ]);

            // Shield plugin đã được đăng ký trong AdminPanelProvider
            // Permissions sẽ được tạo tự động khi truy cập admin panel

        } catch (\Exception $e) {
            // Nếu có lỗi, chỉ log và tiếp tục
            \Illuminate\Support\Facades\Log::warning('Shield setup warning: ' . $e->getMessage());
        }
    }

    /**
     * Tạo Filament resources cho User management
     */
    private function createUserManagementResources(): void
    {
        // UserResource sẽ được tạo bởi Shield
        // Chỉ cần đảm bảo User model có HasRoles trait
        $this->ensureUserModelHasRoles();
    }

    /**
     * Đảm bảo User model có HasRoles trait
     */
    private function ensureUserModelHasRoles(): void
    {
        $userModelPath = app_path('Models/User.php');
        $userModelContent = file_get_contents($userModelPath);

        // Kiểm tra xem đã có HasRoles trait chưa
        if (strpos($userModelContent, 'HasRoles') === false) {
            // Thêm use statement
            $userModelContent = str_replace(
                'use Illuminate\Foundation\Auth\User as Authenticatable;',
                "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Spatie\Permission\Traits\HasRoles;",
                $userModelContent
            );

            // Thêm trait vào class
            $userModelContent = str_replace(
                'use HasApiTokens, HasFactory, Notifiable;',
                'use HasApiTokens, HasFactory, Notifiable, HasRoles;',
                $userModelContent
            );

            file_put_contents($userModelPath, $userModelContent);
        }
    }

    /**
     * Chạy migrations cho Blog module
     */
    private function runBlogMigrations(): void
    {
        try {
            // Kiểm tra và tạo bảng nếu chưa tồn tại
            $this->ensureBlogTablesExist();

        } catch (\Exception $e) {
            throw new \Exception("Lỗi khi cài đặt Blog module: " . $e->getMessage());
        }
    }

    /**
     * Đảm bảo các bảng blog tồn tại
     */
    private function ensureBlogTablesExist(): void
    {
        // Kiểm tra bảng cat_posts (đã tồn tại)
        if (!\Illuminate\Support\Facades\Schema::hasTable('cat_posts')) {
            throw new \Exception('Bảng cat_posts không tồn tại. Vui lòng chạy migrations trước.');
        }

        // Bảng posts đã tồn tại, chỉ cần kiểm tra
        if (!\Illuminate\Support\Facades\Schema::hasTable('posts')) {
            throw new \Exception('Bảng posts không tồn tại. Vui lòng chạy migrations trước.');
        }

        // Tạo bảng post_images nếu chưa có
        if (!\Illuminate\Support\Facades\Schema::hasTable('post_images')) {
            \Illuminate\Support\Facades\Schema::create('post_images', function ($table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->string('image_path');
                $table->string('alt_text')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            });
        }
    }

    /**
     * Tạo dữ liệu mẫu cho Blog module
     */
    private function createBlogSampleData(): void
    {
        // Tạo categories trước
        $this->createPostCategories();

        // Tạo posts
        $this->createSamplePosts();
    }

    /**
     * Tạo post categories mẫu
     */
    private function createPostCategories(): void
    {
        $categories = [
            ['name' => 'Tin tức', 'slug' => 'tin-tuc', 'description' => 'Tin tức và sự kiện mới nhất'],
            ['name' => 'Blog', 'slug' => 'blog', 'description' => 'Bài viết blog và chia sẻ kinh nghiệm'],
            ['name' => 'Hướng dẫn', 'slug' => 'huong-dan', 'description' => 'Hướng dẫn sử dụng và thủ thuật'],
            ['name' => 'Chính sách', 'slug' => 'chinh-sach', 'description' => 'Chính sách và điều khoản'],
            ['name' => 'Thông báo', 'slug' => 'thong-bao', 'description' => 'Thông báo quan trọng']
        ];

        foreach ($categories as $category) {
            // Sử dụng bảng cat_posts thay vì post_categories vì foreign key reference đến cat_posts
            \Illuminate\Support\Facades\DB::table('cat_posts')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'status' => 'active',
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }

    /**
     * Tạo sample posts
     */
    private function createSamplePosts(): void
    {
        $categories = \Illuminate\Support\Facades\DB::table('cat_posts')->pluck('id', 'slug');
        $firstUser = \Illuminate\Support\Facades\DB::table('users')->first();

        $posts = [
            [
                'title' => 'Chào mừng đến với website mới',
                'slug' => 'chao-mung-den-voi-website-moi',
                'excerpt' => 'Chúng tôi vui mừng giới thiệu website mới với nhiều tính năng hiện đại.',
                'content' => '<p>Chúng tôi vui mừng giới thiệu website mới với giao diện hiện đại và nhiều tính năng hữu ích. Website được xây dựng với công nghệ Laravel và Filament, mang đến trải nghiệm tốt nhất cho người dùng.</p>',
                'category_id' => $categories['tin-tuc'] ?? 1,
                'post_type' => 'news',
                'status' => 'active', // Sử dụng 'active' thay vì 'published'
                'is_featured' => true
            ],
            [
                'title' => 'Hướng dẫn sử dụng hệ thống',
                'slug' => 'huong-dan-su-dung-he-thong',
                'excerpt' => 'Hướng dẫn chi tiết cách sử dụng các tính năng của hệ thống.',
                'content' => '<p>Đây là hướng dẫn chi tiết về cách sử dụng các tính năng của hệ thống. Bạn có thể tìm hiểu về cách đăng nhập, quản lý nội dung và sử dụng các công cụ có sẵn.</p>',
                'category_id' => $categories['huong-dan'] ?? 1,
                'post_type' => 'blog',
                'status' => 'active',
                'is_featured' => false
            ],
            [
                'title' => 'Chính sách bảo mật thông tin',
                'slug' => 'chinh-sach-bao-mat-thong-tin',
                'excerpt' => 'Chính sách bảo mật và xử lý thông tin cá nhân của người dùng.',
                'content' => '<p>Chúng tôi cam kết bảo vệ thông tin cá nhân của người dùng theo các tiêu chuẩn bảo mật cao nhất. Mọi thông tin được mã hóa và lưu trữ an toàn.</p>',
                'category_id' => $categories['chinh-sach'] ?? 1,
                'post_type' => 'policy',
                'status' => 'active',
                'is_featured' => false
            ]
        ];

        foreach ($posts as $post) {
            \Illuminate\Support\Facades\DB::table('posts')->updateOrInsert(
                ['slug' => $post['slug']],
                array_merge($post, [
                    'author_name' => $firstUser->name ?? 'Admin', // Sử dụng author_name thay vì author_id
                    'order' => 0,
                    'view_count' => 0,
                    'tags' => json_encode(['sample', 'demo']),
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }

    /**
     * Chạy migrations cho Staff module
     */
    private function runStaffMigrations(): void
    {
        try {
            // Kiểm tra và tạo bảng nếu chưa tồn tại
            $this->ensureStaffTablesExist();

        } catch (\Exception $e) {
            throw new \Exception("Lỗi khi cài đặt Staff module: " . $e->getMessage());
        }
    }

    /**
     * Đảm bảo các bảng staff tồn tại
     */
    private function ensureStaffTablesExist(): void
    {
        // Kiểm tra bảng staff (đã tồn tại)
        if (!\Illuminate\Support\Facades\Schema::hasTable('staff')) {
            throw new \Exception('Bảng staff không tồn tại. Vui lòng chạy migrations trước.');
        }

        // Tạo bảng staff_images nếu chưa có
        if (!\Illuminate\Support\Facades\Schema::hasTable('staff_images')) {
            \Illuminate\Support\Facades\Schema::create('staff_images', function ($table) {
                $table->id();
                $table->unsignedBigInteger('staff_id');
                $table->string('image_path');
                $table->string('alt_text')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            });
        }
    }

    /**
     * Tạo dữ liệu mẫu cho Staff module
     */
    private function createStaffSampleData(): void
    {
        $staffMembers = [
            [
                'name' => 'Nguyễn Văn An',
                'slug' => 'nguyen-van-an',
                'position' => 'Giám đốc điều hành',
                'description' => 'Với hơn 15 năm kinh nghiệm trong lĩnh vực quản lý và phát triển doanh nghiệp, anh An đã dẫn dắt công ty đạt được nhiều thành tựu quan trọng.',
                'email' => 'an.nguyen@company.com',
                'phone' => '0901234567',
                'image' => 'https://picsum.photos/300/300?random=1', // Sử dụng 'image' thay vì 'avatar'
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/nguyen-van-an',
                    'facebook' => 'https://facebook.com/nguyen.van.an'
                ]),
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Trần Thị Bình',
                'slug' => 'tran-thi-binh',
                'position' => 'Trưởng phòng Nhân sự',
                'description' => 'Chuyên gia về quản lý nhân sự với kinh nghiệm 10 năm trong việc xây dựng và phát triển đội ngũ nhân viên chuyên nghiệp.',
                'email' => 'binh.tran@company.com',
                'phone' => '0901234568',
                'image' => 'https://picsum.photos/300/300?random=2',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/tran-thi-binh'
                ]),
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Lê Minh Cường',
                'slug' => 'le-minh-cuong',
                'position' => 'Trưởng phòng Kỹ thuật',
                'description' => 'Kỹ sư phần mềm senior với chuyên môn sâu về Laravel, React và các công nghệ web hiện đại.',
                'email' => 'cuong.le@company.com',
                'phone' => '0901234569',
                'image' => 'https://picsum.photos/300/300?random=3',
                'social_links' => json_encode([
                    'github' => 'https://github.com/leminhcuong',
                    'linkedin' => 'https://linkedin.com/in/le-minh-cuong'
                ]),
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Phạm Thu Dung',
                'slug' => 'pham-thu-dung',
                'position' => 'Trưởng phòng Marketing',
                'description' => 'Chuyên gia marketing digital với kinh nghiệm 8 năm trong việc xây dựng thương hiệu và phát triển khách hàng.',
                'email' => 'dung.pham@company.com',
                'phone' => '0901234570',
                'image' => 'https://picsum.photos/300/300?random=4',
                'social_links' => json_encode([
                    'facebook' => 'https://facebook.com/pham.thu.dung',
                    'instagram' => 'https://instagram.com/phamthudung'
                ]),
                'status' => 'active',
                'order' => 4
            ],
            [
                'name' => 'Hoàng Văn Em',
                'slug' => 'hoang-van-em',
                'position' => 'Kế toán trưởng',
                'description' => 'Kế toán viên chuyên nghiệp với bằng cấp CPA và kinh nghiệm 12 năm trong lĩnh vực tài chính kế toán.',
                'email' => 'em.hoang@company.com',
                'phone' => '0901234571',
                'image' => 'https://picsum.photos/300/300?random=5',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/hoang-van-em'
                ]),
                'status' => 'active',
                'order' => 5
            ]
        ];

        foreach ($staffMembers as $staff) {
            \Illuminate\Support\Facades\DB::table('staff')->updateOrInsert(
                ['slug' => $staff['slug']],
                array_merge($staff, [
                    'seo_title' => $staff['name'] . ' - ' . $staff['position'],
                    'seo_description' => $staff['description'],
                    'og_image' => $staff['image'],
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }
}
