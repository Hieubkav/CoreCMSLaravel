<?php

namespace App\Actions;

class CreateDefaultRoles
{
    /**
     * Tạo các roles mặc định cho hệ thống
     */
    public static function run(): array
    {
        try {
            // Kiểm tra package đã được cài đặt chưa
            if (!class_exists(\Spatie\Permission\Models\Role::class)) {
                return [
                    'success' => false,
                    'message' => 'Spatie Permission package chưa được cài đặt. Sẽ được cài đặt khi chọn User Roles module.'
                ];
            }

            $rolesCreated = [];
            $rolesData = static::getDefaultRoles();

            foreach ($rolesData as $roleData) {
                $roleClass = \Spatie\Permission\Models\Role::class;
                $role = $roleClass::firstOrCreate(
                    ['name' => $roleData['name']],
                    [
                        'guard_name' => 'web',
                        'description' => $roleData['description'] ?? null
                    ]
                );

                $rolesCreated[] = $role->name;
            }

            return [
                'success' => true,
                'message' => 'Đã tạo thành công ' . count($rolesCreated) . ' roles',
                'roles_created' => $rolesCreated
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo roles: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách roles mặc định
     */
    public static function getDefaultRoles(): array
    {
        return [
            [
                'name' => 'Super Admin',
                'description' => 'Quyền cao nhất, có thể làm mọi thứ trong hệ thống'
            ],
            [
                'name' => 'Admin',
                'description' => 'Quản trị viên, có thể quản lý hầu hết các chức năng'
            ],
            [
                'name' => 'Editor',
                'description' => 'Biên tập viên, có thể tạo và chỉnh sửa nội dung'
            ],
            [
                'name' => 'Viewer',
                'description' => 'Người xem, chỉ có quyền xem thông tin'
            ]
        ];
    }

    /**
     * Tạo permissions cho tất cả Filament resources hiện có
     */
    public static function createResourcePermissions(): array
    {
        try {
            $permissionsCreated = [];
            $resources = static::getFilamentResources();

            foreach ($resources as $resource) {
                $permissions = static::getResourcePermissions($resource);
                
                foreach ($permissions as $permission) {
                    $perm = Permission::firstOrCreate(
                        ['name' => $permission],
                        ['guard_name' => 'web']
                    );
                    
                    $permissionsCreated[] = $permission;
                }
            }

            return [
                'success' => true,
                'message' => 'Đã tạo thành công ' . count($permissionsCreated) . ' permissions',
                'permissions_created' => $permissionsCreated
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo permissions: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy danh sách Filament resources
     */
    private static function getFilamentResources(): array
    {
        return [
            'posts',
            'post-categories', 
            'sliders',
            'users',
            'system-configurations',
            'settings'
        ];
    }

    /**
     * Lấy permissions cho một resource
     */
    private static function getResourcePermissions(string $resource): array
    {
        return [
            "view_{$resource}",
            "view_any_{$resource}",
            "create_{$resource}",
            "update_{$resource}",
            "delete_{$resource}",
            "delete_any_{$resource}",
            "force_delete_{$resource}",
            "force_delete_any_{$resource}",
            "restore_{$resource}",
            "restore_any_{$resource}",
            "replicate_{$resource}",
            "reorder_{$resource}"
        ];
    }

    /**
     * Gán permissions cho roles
     */
    public static function assignPermissionsToRoles(): array
    {
        try {
            // Super Admin - tất cả permissions
            $superAdmin = Role::findByName('Super Admin');
            $superAdmin->givePermissionTo(Permission::all());

            // Admin - hầu hết permissions trừ force delete
            $admin = Role::findByName('Admin');
            $adminPermissions = Permission::where('name', 'not like', '%force_delete%')->get();
            $admin->givePermissionTo($adminPermissions);

            // Editor - chỉ view, create, update
            $editor = Role::findByName('Editor');
            $editorPermissions = Permission::where('name', 'like', '%view%')
                ->orWhere('name', 'like', '%create%')
                ->orWhere('name', 'like', '%update%')
                ->get();
            $editor->givePermissionTo($editorPermissions);

            // Viewer - chỉ view
            $viewer = Role::findByName('Viewer');
            $viewerPermissions = Permission::where('name', 'like', '%view%')->get();
            $viewer->givePermissionTo($viewerPermissions);

            return [
                'success' => true,
                'message' => 'Đã gán permissions cho tất cả roles thành công'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gán permissions: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Gán role Super Admin cho user đầu tiên
     */
    public static function assignSuperAdminToFirstUser(): array
    {
        try {
            $firstUser = \App\Models\User::first();
            
            if (!$firstUser) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy user nào trong hệ thống'
                ];
            }

            $superAdminRole = Role::findByName('Super Admin');
            $firstUser->assignRole($superAdminRole);

            return [
                'success' => true,
                'message' => "Đã gán role Super Admin cho user: {$firstUser->email}"
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gán role: ' . $e->getMessage()
            ];
        }
    }
}
