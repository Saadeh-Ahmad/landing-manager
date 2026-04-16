<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            // ── DCB operator (double-click HE) ──
            [
                'key'         => 'endpoints_dcb.api_host',
                'value'       => 'https://services.mediaworldiq.com:450',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'DCB operator API base URL (GET send/verify/unsubscribe)',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_dcb.send_pincode',
                'value'       => '/dcb/API/VMSDCBSubscription/actions/sendPincode',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'DCB send pincode path',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_dcb.verify_pincode',
                'value'       => '/dcb/API/VMS-DCBSubscription/actions/verifyPincode',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'DCB verify pincode path',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_dcb.unsubscribe',
                'value'       => '/dcb/API/VMSDCBSubscription/actions/unsubscribeUser',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'DCB unsubscribe path',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_dcb.he_redirect',
                'value'       => '/HE/v1.3/doubleclick/sub.php',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'Header enrichment redirect path (DCB double-click)',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_dcb.send_sms',
                'value'       => '/dcb/API/DCB-SMS/actions/sendDCBSMS',
                'type'        => 'string',
                'group'       => 'endpoints_dcb',
                'description' => 'DCB endpoint for send SMS',
                'is_active'   => true,
            ],

            // ── VAS operator (one-click HE, VMS API) ──
            [
                'key'         => 'endpoints_vas.api_host',
                'value'       => 'https://services.mediaworldiq.com:450',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS operator API base URL (GET send/verify/unsubscribe)',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_vas.send_pincode',
                'value'       => '/vms/API/VMS-Subscription/actions/sendPincode',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS send pincode path (VMS-Subscription API)',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_vas.verify_pincode',
                'value'       => '/vms/API/VMS-Subscription/actions/verifyPincode',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS verify pincode path (ti/ts per Evina OTP guide)',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_vas.unsubscribe',
                'value'       => '/vms/API/VMS-Subscription/actions/unsubscribeUser',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS unsubscribe path',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_vas.he_redirect',
                'value'       => '/HE/v1.3/oneclick/sub.php',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS one-click header enrichment redirect path',
                'is_active'   => true,
            ],

            // ── Evina anti-fraud platform ──
            [
                'key'         => 'endpoints_evina.base_url',
                'value'       => 'http://www.social-sms.com/iq-dcb',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS one-click Evina',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_vas_he.base_url',
                'value'       => 'http://www.social-sms.com/iq-duel',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS one-click header enrichment redirect path',
                'is_active'   => true,
            ],
            [
                'key'         => 'endpoints_evina.get_script',
                'value'       => '/dcbprotect.php',
                'type'        => 'string',
                'group'       => 'endpoints_vas',
                'description' => 'VAS one-click Evina Get Script',
                'is_active'   => true,
            ],
        ];

        foreach ($configs as $config) {
            SystemConfig::updateOrCreate(['key' => $config['key']], $config);
        }

        $this->command->info('System configurations seeded successfully!');
    }
}
