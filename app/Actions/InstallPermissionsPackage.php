<?php

namespace App\Actions;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallPermissionsPackage
{
    /**
     * Cài đặt và cấu hình Spatie Laravel Permission package
     */
    public static function run(): array
    {
        try {
            // Kiểm tra xem package đã được cài đặt chưa
            $composerJson = json_decode(File::get(base_path('composer.json')), true);
            $hasPermissionPackage = isset($composerJson['require']['spatie/laravel-permission']);
            $hasFilamentPlugin = isset($composerJson['require']['filament/spatie-laravel-permissions-plugin']);

            if (!$hasPermissionPackage || !$hasFilamentPlugin) {
                return [
                    'success' => false,
                    'message' => 'Packages chưa được cài đặt. Vui lòng chạy: composer require spatie/laravel-permission filament/spatie-laravel-permissions-plugin'
                ];
            }

            // Publish migration files nếu chưa có
            $migrationPath = database_path('migrations');
            $permissionMigrations = glob($migrationPath . '/*_create_permission_tables.php');
            
            if (empty($permissionMigrations)) {
                Artisan::call('vendor:publish', [
                    '--provider' => 'Spatie\Permission\PermissionServiceProvider'
                ]);
            }

            // Chạy migrations
            Artisan::call('migrate');

            // Tạo config file nếu chưa có
            $configPath = config_path('permission.php');
            if (!File::exists($configPath)) {
                Artisan::call('vendor:publish', [
                    '--provider' => 'Spatie\Permission\PermissionServiceProvider',
                    '--tag' => 'config'
                ]);
            }

            return [
                'success' => true,
                'message' => 'Spatie Laravel Permission đã được cài đặt và cấu hình thành công!'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cài đặt package: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Kiểm tra package đã được cài đặt chưa
     */
    public static function isInstalled(): bool
    {
        try {
            // Kiểm tra class có tồn tại không
            return class_exists(\Spatie\Permission\Models\Role::class) && 
                   class_exists(\Spatie\Permission\Models\Permission::class);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra migrations đã chạy chưa
     */
    public static function isMigrated(): bool
    {
        try {
            return \Schema::hasTable('roles') && 
                   \Schema::hasTable('permissions') && 
                   \Schema::hasTable('model_has_permissions') && 
                   \Schema::hasTable('model_has_roles') && 
                   \Schema::hasTable('role_has_permissions');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cấu hình User model để sử dụng permissions
     */
    public static function configureUserModel(): array
    {
        try {
            $userModelPath = app_path('Models/User.php');
            $userModelContent = File::get($userModelPath);

            // Kiểm tra xem đã có trait chưa
            if (strpos($userModelContent, 'HasRoles') !== false) {
                return [
                    'success' => true,
                    'message' => 'User model đã được cấu hình sẵn'
                ];
            }

            // Thêm use statement
            $useStatement = "use Spatie\Permission\Traits\HasRoles;";
            if (strpos($userModelContent, $useStatement) === false) {
                $userModelContent = str_replace(
                    "use Illuminate\Notifications\Notifiable;",
                    "use Illuminate\Notifications\Notifiable;\n" . $useStatement,
                    $userModelContent
                );
            }

            // Thêm trait vào class
            $traitStatement = "    use HasRoles;";
            if (strpos($userModelContent, $traitStatement) === false) {
                $userModelContent = str_replace(
                    "    use HasFactory, Notifiable;",
                    "    use HasFactory, Notifiable, HasRoles;",
                    $userModelContent
                );
            }

            File::put($userModelPath, $userModelContent);

            return [
                'success' => true,
                'message' => 'User model đã được cấu hình để sử dụng permissions'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cấu hình User model: ' . $e->getMessage()
            ];
        }
    }
}
