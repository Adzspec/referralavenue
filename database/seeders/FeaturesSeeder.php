<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeaturesSeeder extends Seeder
{
    public function run()
    {
        $features = [
            ['is_value_based' => 0, 'key' => 'home_data_customize', 'name' => 'Homepage data customization'],
//            ['is_value_based' => 1, 'key' => 'white_label_branding', 'name' => 'White-label branding'],
//            ['is_value_based' => 0, 'key' => 'custom_domain_support', 'name' => 'Custom domain support'],
            ['is_value_based' => 1, 'key' => 'affiliate_network_integrations', 'name' => 'Affiliate network integrations'],
//            ['is_value_based' => 1, 'key' => 'reporting_dashboard', 'name' => 'Real-time reporting dashboard'],
//            ['is_value_based' => 0, 'key' => 'api_access', 'name' => 'API access for external tools'],
            ['is_value_based' => 0, 'key' => 'client_dashboard', 'name' => 'White-labeled client dashboard'],
//            ['is_value_based' => 1, 'key' => 'multi_language_support', 'name' => 'Multi-language support'],
            ['is_value_based' => 0, 'key' => 'custom_ui_styling', 'name' => 'Custom UI styling (CSS override)'],
            ['is_value_based' => 1, 'key' => 'deals_limit', 'name' => 'Number of deals limit'],
            ['is_value_based' => 1, 'key' => 'revunue_share', 'name' => 'Revenue share'],
        ];

        DB::table('features')->insert($features);
    }
}
