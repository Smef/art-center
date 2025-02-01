<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Spatie recommends running this before and after seeding
        // https://spatie.be/docs/laravel-permission/v5/advanced-usage/seeding

        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Set up permissiong
        // Make a permission for approved staff users to access the app
        $accessApp = Permission::create([
            'name' => 'access app',
            'description' => 'General access to the app.',
        ]);

        // Make a permission to access the admin area
        $accessAdmin = Permission::create([
            'name' => 'access admin',
            'description' => 'Access the admin areas such as user and role management.',
        ]);

        // Make a basic staff group
        Role::create([
            'name' => 'Staff',
        ])->givePermissionTo($accessApp);

        // Make an admin role
        Role::create([
            'name' => 'Admin',
        ])->givePermissionTo($accessAdmin);

        // flush again after seeding as noted above
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    }
}
