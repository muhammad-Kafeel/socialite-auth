<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view-dashboard',
            'create-user',
            'edit-user',
            'delete-user',
            'view-user',
            'create-post',
            'edit-post',
            'delete-post',
            'view-post',
            'manage-roles',
            'manage-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // Admin Role - Has all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Manager Role - Has limited permissions
        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view-dashboard',
            'view-user',
            'create-post',
            'edit-post',
            'view-post',
        ]);

        // User Role - Has basic permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view-dashboard',
            'view-post',
        ]);

        echo "Roles and Permissions created successfully!\n";
    }
}