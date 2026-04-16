<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // ── Duel OTP ──
            [
                'name'               => 'duel-otp',
                'type'               => 'vas',
                'merchant_name'      => 'MediaWorld',
                'display_name'       => 'Duel',
                'description'        => 'Duel-VAS Subscription API for pin code verification and subscription',
                'api_username'       => env('DCB_USERNAME', 'mediaworld'),
                'api_password'       => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id'              => '2',
                'service_id'         => '1',
                'shortcode'          => '2600',
                'is_smart'           => false,
                'timeout'            => 30,
                'settings'           => [
                    'pin_length'         => 5,
                    'max_retry_attempts' => 3,
                    'pin_expiry_minutes' => 5,
                ],
                'is_active'          => true,
                'enable_evina_fraud' => true,
                'subscribtion_type'  => 'daily',
                'mode'               => env('DCB_MODE', 'live'),
                'price'              => 12.00,
                'currency'           => 'IQD',
                'operator'           => 'Multiple',
                'country_code'       => '964',
            ],

            // ── Duel HE ──
            [
                'name'               => 'duel-he',
                'type'               => 'evina',
                'merchant_name'      => 'MediaWorld',
                'display_name'       => 'Duel',
                'description'        => 'Duel-VAS service Header Enrichment support',
                'api_username'       => env('DCB_USERNAME', 'mediaworld'),
                'api_password'       => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id'              => '2',
                'service_id'         => '1',
                'shortcode'          => '2600',
                'is_smart'           => false,
                'timeout'            => 30,
                'settings'           => [
                    'pin_length'        => 5,
                    'default_flow'      => 'otp',
                    'cta_button_id'     => 'cta_button',
                    'antifraud_enabled' => true,
                ],
                'is_active'          => true,
                'enable_evina_fraud' => true,
                'subscribtion_type'  => 'daily',
                'mode'               => env('DCB_MODE', 'live'),
                'price'              => 12.00,
                'currency'           => 'IQD',
                'operator'           => 'Multiple',
                'country_code'       => '964',
            ],

            // ── Brainiac OTP ──
            [
                'name'               => 'brainiac-otp',
                'type'               => 'vas',
                'merchant_name'      => 'MediaWorld',
                'display_name'       => 'Brainiac',
                'description'        => 'Brainiac VAS Subscription — OTP flow',
                'api_username'       => env('DCB_USERNAME', 'mediaworld'),
                'api_password'       => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id'              => '2',
                'service_id'         => '4',
                'shortcode'          => '2222',
                'is_smart'           => false,
                'timeout'            => 30,
                'settings'           => [
                    'pin_length'         => 5,
                    'max_retry_attempts' => 3,
                    'pin_expiry_minutes' => 5,
                ],
                'is_active'          => true,
                'enable_evina_fraud' => true,
                'subscribtion_type'  => 'daily',
                'mode'               => env('DCB_MODE', 'live'),
                'price'              => 12.00,
                'currency'           => 'IQD',
                'operator'           => 'Multiple',
                'country_code'       => '964',
            ],

            // ── Brainiac HE ──
            [
                'name'               => 'brainiac-he',
                'type'               => 'evina',
                'merchant_name'      => 'MediaWorld',
                'display_name'       => 'Brainiac',
                'description'        => 'Brainiac VAS — Header Enrichment flow',
                'api_username'       => env('DCB_USERNAME', 'mediaworld'),
                'api_password'       => env('DCB_PASSWORD', 'mw2026@1'),
                'sp_id'              => '2',
                'service_id'         => '4',
                'shortcode'          => '2222',
                'is_smart'           => false,
                'timeout'            => 30,
                'settings'           => [
                    'pin_length'        => 5,
                    'default_flow'      => 'otp',
                    'cta_button_id'     => 'subscribe_btn',
                    'antifraud_enabled' => true,
                ],
                'is_active'          => true,
                'enable_evina_fraud' => true,
                'subscribtion_type'  => 'daily',
                'mode'               => env('DCB_MODE', 'live'),
                'price'              => 12.00,
                'currency'           => 'IQD',
                'operator'           => 'Multiple',
                'country_code'       => '964',
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(['name' => $data['name']], $data);
        }

        $this->command->info('Services seeded successfully!');
    }
}
