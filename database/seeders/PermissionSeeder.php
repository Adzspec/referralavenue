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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
