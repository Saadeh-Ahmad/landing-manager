<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'duel-otp',
                'type' => 'vas',
                'merchant_name' => env('MERCHANT_NAME', 'MediaWorld'),
                'display_name' => 'Duel VAS Service OTP',
                'description' => 'Duel-VAS Subscription API for pin code verification and subscription',
                'api_username' => env('DCB_USERNAME', 'mediaworld'),
                'api_password' => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id' => env('DCB_SP_ID', '2'),
                'service_id' => env('DCB_SERVICE_ID', '1'),
                'shortcode' => env('DCB_SHORTCODE', '2600'),
                'timeout' => 30,
                'settings' => [
                    'pin_length' => 5,
                    'pin_expiry_minutes' => 5,
                    'max_retry_attempts' => 3,
                ],
                'is_active' => true,
                'enable_evina_fraud' => false,
                'subscribtion_type' => 'daily',
                'mode' => env('DCB_MODE', 'live'),
                'price' => 12.00,
                'currency' => 'IQD',
                'operator' => 'Multiple',
                'country_code' => '964',
            ],
            [
                'name' => 'duel-he',
                'type' => 'evina',
                'merchant_name' => env('MERCHANT_NAME', 'MediaWorld'),
                'display_name' => 'Duel VAS Service HE',
                'description' => 'Duel-VAS service Header Enrichment support',
                'api_username' => env('DCB_USERNAME', 'mediaworld'),
                'api_password' => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id' => env('DCB_SP_ID', '2'),
                'service_id' => env('DCB_SERVICE_ID', '1'),
                'shortcode' => env('DCB_SHORTCODE', '2600'),
                'timeout' => 30,
                'settings' => [
                    'antifraud_enabled' => true,
                    'default_flow' => 'otp',
                    'cta_button_id' => 'cta_button',
                    'pin_length' => 5,
                ],
                'is_active' => true,
                'enable_evina_fraud' => true,
                'subscribtion_type' => 'daily',
                'mode' => env('DCB_MODE', 'live'),
                'price' => 12.00,
                'currency' => 'IQD',
                'operator' => 'Multiple',
                'country_code' => '964',
            ],
//            [
//                'name' => 'smart-subscription',
//                'type' => 'dcb',
//                'merchant_name' => env('MERCHANT_NAME', 'MediaWorld'),
//                'display_name' => 'MWtest',
//                'description' => 'DCB SMART subscription',
//                'api_username' => env('DCB_USERNAME', 'MWtest'),
//                'api_password' => env('DCB_PASSWORD', 'MWtest24'),
//                'sp_id' => env('DCB_SP_ID', '2'),
//                'service_id' => '1018',
//                'shortcode' => '4089',
//                'is_smart' => true,
//                'alphanumeric' => 'Landing',
//                'sub_message' => 'Landing subscription - israa',
//                'timeout' => 30,
//                'settings' => [],
//                'is_active' => true,
//                'enable_evina_fraud' => false,
//                'subscribtion_type' => 'daily',
//                'mode' => env('DCB_MODE', 'live'),
//                'price' => 12.00,
//                'currency' => 'IQD',
//                'operator' => 'Multiple',
//                'country_code' => '964',
//            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['name' => $serviceData['name']],
                $serviceData
            );
        }

        $this->command->info('Services seeded successfully!');
    }
}
