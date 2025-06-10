<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\CreateAdminUser;
use Illuminate\Http\Request;

class ProcessAdminStep
{
    /**
     * Xử lý bước tạo admin
     */
    public static function handle(Request $request): array
    {
        try {
            $data = $request->only(['name', 'email', 'password', 'password_confirmation']);
            $result = CreateAdminUser::createWithValidation($data);

            if ($result['success']) {
                // Sinh UserResource và RoleResource sau khi tạo admin thành công
                $generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('admin');

                // Tạo roles và permissions cơ bản
                $rolesResult = self::createBasicRolesAndPermissions();

                $message = $result['message'];
                if ($generateResult['success']) {
                    $message .= ' Đã tạo UserResource và RoleResource để quản lý người dùng.';
                }

                if ($rolesResult['success']) {
                    $message .= ' ' . $rolesResult['message'];
                }

                $result['message'] = $message;
                $result['generate_result'] = $generateResult;
                $result['roles_result'] = $rolesResult;

                return $result;
            } else {
                return $result;
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tạo roles và permissions cơ bản
     */
    private static function createBasicRolesAndPermissions(): array
    {
        try {
            // Kiểm tra xem có bảng roles và permissions không
            if (!\Illuminate\Support\Facades\Schema::hasTable('roles') ||
                !\Illuminate\Support\Facades\Schema::hasTable('permissions')) {
                return [
                    'success' => false,
                    'message' => 'Bảng roles/permissions chưa tồn tại. Cần cài đặt Spatie Permission package.'
                ];
            }

            $rolesCreated = [];
            $permissionsCreated = [];

            // Tạo permissions cơ bản cho User và Role management (Filament convention)
            $basicPermissions = [
                'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user', 'delete_any_user',
                'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role', 'delete_any_role',
                'manage_permissions'
            ];

            foreach ($basicPermissions as $permission) {
                $perm = \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web'
                ]);
                $permissionsCreated[] = $permission;
            }

            // Tạo roles cơ bản
            $basicRoles = [
                [
                    'name' => 'Super Admin',
                    'permissions' => $basicPermissions
                ],
                [
                    'name' => 'Admin',
                    'permissions' => ['view_any_user', 'view_user', 'create_user', 'update_user']
                ],
                [
                    'name' => 'User',
                    'permissions' => []
                ]
            ];

            foreach ($basicRoles as $roleData) {
                $role = \Spatie\Permission\Models\Role::firstOrCreate([
                    'name' => $roleData['name'],
                    'guard_name' => 'web'
                ]);

                // Gán permissions cho role
                if (!empty($roleData['permissions'])) {
                    $role->syncPermissions($roleData['permissions']);
                }

                $rolesCreated[] = $roleData['name'];
            }

            return [
                'success' => true,
                'message' => 'Đã tạo ' . count($rolesCreated) . ' roles và ' . count($permissionsCreated) . ' permissions cơ bản.',
                'roles' => $rolesCreated,
                'permissions' => $permissionsCreated
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo roles/permissions: ' . $e->getMessage()
            ];
        }
    }
}
