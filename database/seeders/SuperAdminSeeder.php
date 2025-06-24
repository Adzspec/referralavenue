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
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        $companyRole->syncPermissions(['manage dashboard','manage subscriptions','manage company subscriptions']);

        // 4. Assign Role to User
        $user->assignRole($adminRole);
    }

}
