<?php

use App\Models\User;
use App\Models\Company;
use App\Models\Category;
use App\Models\Store;
use App\Models\Offer;
use Inertia\Testing\AssertableInertia as Assert;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

test('super admins see global counts', function () {
    $user = User::factory()->create();

    $company1 = Company::create(['name' => 'Company One', 'email' => 'c1@example.com']);
    $company2 = Company::create(['name' => 'Company Two', 'email' => 'c2@example.com']);

    $store1 = Store::create(['name' => 'Store One', 'company_id' => $company1->id]);
    $store2 = Store::create(['name' => 'Store Two', 'company_id' => $company2->id]);

    Category::create(['name' => 'Cat One', 'company_id' => $company1->id]);
    Category::create(['name' => 'Cat Two', 'company_id' => $company2->id]);

    Offer::create(['title' => 'Offer One', 'company_id' => $company1->id, 'store_id' => $store1->id]);
    Offer::create(['title' => 'Offer Two', 'company_id' => $company2->id, 'store_id' => $store2->id]);

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertInertia(fn (Assert $page) => $page
        ->where('type', 'admin')
        ->where('counts.companies', 2)
        ->where('counts.categories', 2)
        ->where('counts.stores', 2)
        ->where('counts.offers', 2)
    );
});

test('company users see company counts', function () {
    $company1 = Company::create(['name' => 'Company One', 'email' => 'c1@example.com']);
    $company2 = Company::create(['name' => 'Company Two', 'email' => 'c2@example.com']);

    $store1 = Store::create(['name' => 'Store One', 'company_id' => $company1->id]);
    $store2 = Store::create(['name' => 'Store Two', 'company_id' => $company2->id]);

    Category::create(['name' => 'Cat One', 'company_id' => $company1->id]);
    Category::create(['name' => 'Cat Two', 'company_id' => $company2->id]);

    Offer::create(['title' => 'Offer One', 'company_id' => $company1->id, 'store_id' => $store1->id]);
    Offer::create(['title' => 'Offer Two', 'company_id' => $company2->id, 'store_id' => $store2->id]);

    $user = User::factory()->create(['company_id' => $company1->id]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertInertia(fn (Assert $page) => $page
        ->where('type', 'company')
        ->where('counts.categories', 1)
        ->where('counts.stores', 1)
        ->where('counts.offers', 1)
    );
});
