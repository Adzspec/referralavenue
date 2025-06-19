<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::create([
            'title' => 'Dashboard',
            'href' => '/dashboard',
            'icon' => 'LayoutDashboard',
            'permission' => 'manage dashboard'
        ]);

        $users = Menu::create([
            'title' => 'Users',
            'href' => '/users',
            'icon' => 'UsersIcon',
            'permission' => 'manage users'
        ]);
        Menu::create([
            'title' => 'Roles',
            'href' => '/roles',
            'icon' => 'ScrollText',
            'permission' => 'manage roles'
        ]);
        Menu::create([
            'title' => 'Permissions',
            'href' => '/permissions',
            'icon' => 'FolderLock',
            'permission' => 'manage permissions'
        ]);
        Menu::create([
            'title' => 'Menus',
            'href' => '/menus',
            'icon' => 'Menu',
            'permission' => 'manage menus'
        ]);

//        $users->children()->createMany([
//            [
//                'title' => 'All Users',
//                'href' => '/users',
//                'permission' => 'manage users',
//            ],
//        ]);

    }
}
