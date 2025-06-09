<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo roles và permissions...');

        try {
            // Tạo roles trực tiếp
            $this->createRoles();
            $this->createPermissions();
            $this->assignPermissionsToRoles();
            $this->assignSuperAdminToFirstUser();

            $this->command->info('🎉 Hoàn thành setup roles và permissions!');
        } catch (\Exception $e) {
            $this->command->error('❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function createRoles()
    {
        $roles = [
            ['name' => 'Super Admin', 'guard_name' => 'web'],
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['name' => 'Editor', 'guard_name' => 'web'],
            ['name' => 'Viewer', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                array_merge($role, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('✅ Đã tạo 4 roles');
    }

    private function createPermissions()
    {
        $resources = [
            'posts', 'post-categories', 'sliders', 'users', 'system-configurations',
            'settings', 'galleries', 'brands', 'faqs', 'statistics', 'testimonials',
            'services', 'features', 'partners', 'schedules', 'timelines'
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];

        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'name' => "{$action}_{$resource}",
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('✅ Đã tạo ' . count($permissions) . ' permissions');
    }

    private function assignPermissionsToRoles()
    {
        // Lấy tất cả permissions
        $allPermissions = DB::table('permissions')->pluck('id')->toArray();

        // Super Admin - tất cả permissions
        $superAdminId = DB::table('roles')->where('name', 'Super Admin')->value('id');
        foreach ($allPermissions as $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'role_id' => $superAdminId,
                'permission_id' => $permissionId
            ]);
        }

        // Admin - view, create, update permissions
        $adminId = DB::table('roles')->where('name', 'Admin')->value('id');
        $adminPermissions = DB::table('permissions')
            ->where('name', 'like', '%view%')
            ->orWhere('name', 'like', '%create%')
            ->orWhere('name', 'like', '%update%')
            ->pluck('id')->toArray();

        foreach ($adminPermissions as $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'role_id' => $adminId,
                'permission_id' => $permissionId
            ]);
        }

        // Editor - view, create permissions
        $editorId = DB::table('roles')->where('name', 'Editor')->value('id');
        $editorPermissions = DB::table('permissions')
            ->where('name', 'like', '%view%')
            ->orWhere('name', 'like', '%create%')
            ->pluck('id')->toArray();

        foreach ($editorPermissions as $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'role_id' => $editorId,
                'permission_id' => $permissionId
            ]);
        }

        // Viewer - chỉ view permissions
        $viewerId = DB::table('roles')->where('name', 'Viewer')->value('id');
        $viewerPermissions = DB::table('permissions')
            ->where('name', 'like', '%view%')
            ->pluck('id')->toArray();

        foreach ($viewerPermissions as $permissionId) {
            DB::table('role_has_permissions')->updateOrInsert([
                'role_id' => $viewerId,
                'permission_id' => $permissionId
            ]);
        }

        $this->command->info('✅ Đã gán permissions cho tất cả roles');
    }

    private function assignSuperAdminToFirstUser()
    {
        $firstUser = DB::table('users')->first();
        if (!$firstUser) {
            $this->command->warn('⚠️ Không tìm thấy user nào trong hệ thống');
            return;
        }

        $superAdminId = DB::table('roles')->where('name', 'Super Admin')->value('id');

        DB::table('model_has_roles')->updateOrInsert([
            'role_id' => $superAdminId,
            'model_type' => 'App\\Models\\User',
            'model_id' => $firstUser->id
        ]);

        $this->command->info("✅ Đã gán role Super Admin cho user: {$firstUser->email}");
    }
}
