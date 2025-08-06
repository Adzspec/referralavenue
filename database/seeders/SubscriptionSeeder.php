<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        DB::table('subscriptions')->insert([
            [
                'name' => 'Free Plan',
                'price_id' => 'price_1RsJcBCTIpgMLNY3CZBq99tK',
                'price' => 0,
                'duration' => 30, // 30 days
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'price_id' => 'price_1RoIsWCTIpgMLNY3gmmDMT7p',
                'price' => 2000,
                'duration' => 30, // 30 days
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'price_id' => 'price_1RsI25CTIpgMLNY38MdcKYBh',
                'price' => 3000,
                'duration' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'price_id' => 'price_1RsI2lCTIpgMLNY3xeamfWvx',
                'price' => 5000,
                'duration' => 30,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
