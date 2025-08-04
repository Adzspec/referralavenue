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
                'features' => json_encode([
                    '1 project',
                    'Basic support',
                    'Community access'
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'price_id' => 'price_1RoIsWCTIpgMLNY3gmmDMT7p',
                'price' => 2000,
                'duration' => 30, // 30 days
                'features' => json_encode([
                    '1 project',
                    'Basic support',
                    'Community access'
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'price_id' => 'price_1RsI25CTIpgMLNY38MdcKYBh',
                'price' => 3000,
                'duration' => 30,
                'features' => json_encode([
                    '10 projects',
                    'Priority support',
                    'Community access',
                    'Analytics'
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'price_id' => 'price_1RsI2lCTIpgMLNY3xeamfWvx',
                'price' => 5000,
                'duration' => 30,
                'features' => json_encode([
                    'Unlimited projects',
                    '24/7 support',
                    'Custom solutions',
                    'Advanced analytics'
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
