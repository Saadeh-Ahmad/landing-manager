<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
//            // Session Service Configuration
//            [
//                'key' => 'billing_api_url',
//                'value' => env('BILLING_API_URL', 'https://billing.quickfun.games/mediaworld/token'),
//                'type' => 'string',
//                'group' => 'session',
//                'description' => 'Billing API URL for requesting session IDs',
//            ],
//            [
//                'key' => 'portal_gateway_url',
//                'value' => env('PORTAL_GATEWAY_URL', 'https://billing.quickfun.games/mediaworld/gateway'),
//                'type' => 'string',
//                'group' => 'session',
//                'description' => 'Portal gateway URL for content access',
//            ],
//            [
//                'key' => 'session_service_id',
//                'value' => env('SESSION_SERVICE_ID', '7'),
//                'type' => 'integer',
//                'group' => 'session',
//                'description' => 'Service ID for billing API',
//            ],
//            [
//                'key' => 'private_key_path',
//                'value' => env('PRIVATE_KEY_PATH', '/var/www/html/private_key.pem'),
//                'type' => 'string',
//                'group' => 'session',
//                'description' => 'Path to private key file for JWT signing',
//            ],
//
//            // DCB Endpoints
//            [
//                'key' => 'endpoints_dcb.api_host',
//                'value' => env('DCB_HOST', 'https://services.mediaworldiq.com:456'),
//                'type' => 'string',
//                'group' => 'endpoints_dcb',
//                'description' => 'DCB API host URL',
//            ],
//            [
//                'key' => 'endpoints_dcb.send_pincode',
//                'value' => '/dcb/API/VMS-DCBSubscription/actions/sendPincode',
//                'type' => 'string',
//                'group' => 'endpoints_dcb',
//                'description' => 'DCB endpoint for sending pincode',
//            ],
//            [
//                'key' => 'endpoints_dcb.verify_pincode',
//                'value' => '/dcb/API/VMS-DCBSubscription/actions/verifyPincode',
//                'type' => 'string',
//                'group' => 'endpoints_dcb',
//                'description' => 'DCB endpoint for verifying pincode',
//            ],
//            [
//                'key' => 'endpoints_dcb.unsubscribe',
//                'value' => '/dcb/API/VMS-DCBSubscription/actions/unsubscribe',
//                'type' => 'string',
//                'group' => 'endpoints_dcb',
//                'description' => 'DCB endpoint for unsubscribing users',
//            ],
            [
                'key' => 'endpoints_dcb.send_sms',
                'value' => '/dcb/API/DCB-SMS/actions/sendDCBSMS',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB endpoint for send SMS',
            ],

//            // Evina Endpoints
//            [
//                'key' => 'endpoints_evina.base_url',
//                'value' => env('EVINA_BASE_URL', 'https://www.social-sms.com/iq-dcb'),
//                'type' => 'string',
//                'group' => 'endpoints_evina',
//                'description' => 'Evina base URL',
//            ],
//            [
//                'key' => 'endpoints_evina.get_script',
//                'value' => '/dcbprotect.php',
//                'type' => 'string',
//                'group' => 'endpoints_evina',
//                'description' => 'Evina endpoint for getting anti-fraud script',
//            ],
//            [
//                'key' => 'endpoints_dcb.he_redirect',
//                'value' => '/HE/v1.3/doubleclick/sub.php',
//                'type' => 'string',
//                'group' => 'endpoints_dcb',
//                'description' => 'DCB Header Enrichment redirect endpoint',
//            ],
//
//            // QuickFun Endpoints (if any)
//            [
//                'key' => 'endpoints_quickfun.base_url',
//                'value' => env('QUICKFUN_BASE_URL', 'https://billing.quickfun.games'),
//                'type' => 'string',
//                'group' => 'endpoints_quickfun',
//                'description' => 'QuickFun base URL',
//            ],
        ];

        foreach ($configs as $config) {
            SystemConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }

        $this->command->info('System configurations seeded successfully!');
    }
}
