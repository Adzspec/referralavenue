<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage dashboard',
            'manage users',
            'create users',
            'edit users',
            'delete users',
            'manage roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'manage menus',
            'create menus',
            'edit menus',
            'delete menus',
            'manage companies',
            'create companies',
            'edit companies',
            'delete companies',
            'manage subscriptions',
            'manage subscriptions list',
            'create subscriptions',
            'edit subscriptions',
            'delete subscriptions',
            'manage company subscriptions',
            'create company subscriptions',
            'edit company subscriptions',
            'delete company subscriptions',
            'manage categories',
            'create categories',
            'edit categories',
            'delete categories',
            'manage stores',
            'create stores',
            'edit stores',
            'delete stores',
            'manage offers',
            'create offers',
            'edit offers',
            'delete offers',
            'manage company settings',
            'manage homepage settings',
            'manage integrations settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
