<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin User
        $user = User::firstOrCreate([
            'email' => 'superadmin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('12345678'), // Change this password
        ]);

        // 2. Create Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $companyRole = Role::firstOrCreate(['name' => 'company admin', 'guard_name' => 'web']);

        // 3. Give All Permissions to Admin Role
//        $permissions = Permission::all();
        $adminRole->syncPermissions(
            [
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
            ]
        );

        $companyRole->syncPermissions(
            [
                'manage dashboard',
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
            ]
        );

        // 4. Assign Role to User
        $user->assignRole($adminRole);
    }

}
