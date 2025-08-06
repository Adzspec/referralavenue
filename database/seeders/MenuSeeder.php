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
        Menu::create([
            'title' => 'Companies',
            'href' => '/companies',
            'icon' => 'Building2',
            'permission' => 'manage companies'
        ]);
        $subscriptions = Menu::create([
            'title' => 'Subscriptions',
            'href' => '/subscriptions',
            'icon' => 'CalendarSync',
            'permission' => 'manage subscriptions'
        ]);

        $subscriptions->children()->createMany([
            [
                'title' => 'Subscriptions List',
                'href' => '/subscriptions',
                'icon' => 'List',
                'permission' => 'manage subscriptions',
            ],
            [
                'title' => 'Subscription Features',
                'href' => '/feature-matrix',
                'icon' => 'ListChecks',
                'permission' => '',
            ],
            [
                'title' => 'Company Subscriptions',
                'href' => '/company_subscriptions',
                'icon' => 'NotebookTabs',
                'permission' => 'manage company subscriptions',
            ],
        ]);

        Menu::create([
            'title' => 'Categories',
            'href' => '/categories',
            'icon' => 'ChartBarStacked',
            'permission' => 'manage categories'
        ]);

        Menu::create([
            'title' => 'Stores',
            'href' => '/stores',
            'icon' => 'Store',
            'permission' => 'manage stores'
        ]);
        Menu::create([
            'title' => 'Offers',
            'href' => '/offers',
            'icon' => 'Percent',
            'permission' => 'manage offers'
        ]);

        $settings = Menu::create([
            'title' => 'Company Settings',
            'href' => '/company/settings',
            'icon' => 'ChartBarStacked',
            'permission' => 'manage company settings',
        ]);

        $settings->children()->createMany([
            [
                'title' => 'Company Settings',
                'href' => '/company/company-settings',
                'icon' => 'List',
                'permission' => 'manage company settings',
            ],
            [
                'title' => 'Homepage Settings',
                'href' => '/company/home_settings',
                'icon' => 'List',
                'permission' => 'manage homepage settings',
            ],
            [
                'title' => 'Integrations Settings',
                'href' => '/company/integrations',
                'icon' => 'NotebookTabs',
                'permission' => 'manage integrations settings',
            ],
        ]);
    }
}
