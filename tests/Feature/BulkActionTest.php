<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Store;
use App\Models\Offer;
use App\Models\User;
use Tests\TestCase;

class BulkActionTest extends TestCase
{
    /** @test */
    public function offer_bulk_delete_is_limited_to_authenticated_users_company(): void
    {
        $companyA = Company::create([
            'name' => 'Company A',
            'email' => 'a@example.com',
            'domain' => 'a.com',
            'registration_no' => 'regA',
            'vat' => 'vatA',
            'status' => 1,
        ]);
        $companyB = Company::create([
            'name' => 'Company B',
            'email' => 'b@example.com',
            'domain' => 'b.com',
            'registration_no' => 'regB',
            'vat' => 'vatB',
            'status' => 1,
        ]);

        $storeA = Store::create([
            'company_id' => $companyA->id,
            'name' => 'Store A',
        ]);
        $storeB = Store::create([
            'company_id' => $companyB->id,
            'name' => 'Store B',
        ]);

        $offerA = Offer::create([
            'company_id' => $companyA->id,
            'store_id' => $storeA->id,
            'title' => 'Offer A',
        ]);
        $offerB = Offer::create([
            'company_id' => $companyB->id,
            'store_id' => $storeB->id,
            'title' => 'Offer B',
        ]);

        $user = User::factory()->create(['company_id' => $companyA->id]);

        $this->actingAs($user)
            ->post(route('offers.bulkDelete'), ['ids' => [$offerA->id, $offerB->id]])
            ->assertRedirect();

        $this->assertDatabaseMissing('offers', ['id' => $offerA->id]);
        $this->assertDatabaseHas('offers', ['id' => $offerB->id]);
    }

    /** @test */
    public function store_bulk_status_only_updates_records_for_users_company(): void
    {
        $companyA = Company::create([
            'name' => 'Company A',
            'email' => 'a@example.com',
            'domain' => 'a.com',
            'registration_no' => 'regA',
            'vat' => 'vatA',
            'status' => 1,
        ]);
        $companyB = Company::create([
            'name' => 'Company B',
            'email' => 'b@example.com',
            'domain' => 'b.com',
            'registration_no' => 'regB',
            'vat' => 'vatB',
            'status' => 1,
        ]);

        $storeA = Store::create([
            'company_id' => $companyA->id,
            'name' => 'Store A',
            'status' => 1,
        ]);
        $storeB = Store::create([
            'company_id' => $companyB->id,
            'name' => 'Store B',
            'status' => 1,
        ]);

        $user = User::factory()->create(['company_id' => $companyA->id]);

        $this->actingAs($user)
            ->post(route('stores.bulkStatus'), ['ids' => [$storeA->id, $storeB->id], 'status' => 2])
            ->assertRedirect();

        $this->assertDatabaseHas('stores', ['id' => $storeA->id, 'status' => 2]);
        $this->assertDatabaseHas('stores', ['id' => $storeB->id, 'status' => 1]);
    }
}

