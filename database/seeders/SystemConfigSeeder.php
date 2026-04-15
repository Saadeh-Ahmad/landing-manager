<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds two operator integration profiles:
     * - endpoints_dcb: legacy DCB subscription API + double-click HE (Evina config defaults).
     * - endpoints_vas: VMS subscription API + one-click HE (VAS PDFs).
     */
    public function run(): void
    {
        $evinaBase = config('apis.evina.endpoints.base_url', 'http://www.social-sms.com/iq-dcb');
        $heBase = config('apis.vas_operator.he_base_url', 'http://www.social-sms.com/iq-duel');
        $dcbEp = config('apis.evina.endpoints', []);
        $vas = config('apis.vas_operator', []);

        $defaultDcbHost = env('DCB_API_HOST', $evinaBase);
        $defaultVasHost = env('VAS_API_HOST') ?: $defaultDcbHost;

        $configs = [
            // --- DCB operator (double-click HE + /dcb/API/... ) ---
            [
                'key' => 'endpoints_dcb.api_host',
                'value' => $defaultDcbHost,
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB operator API base URL (GET send/verify/unsubscribe)',
            ],
            [
                'key' => 'endpoints_dcb.send_pincode',
                'value' => $dcbEp['send_pincode'] ?? '/dcb/API/VMSDCBSubscription/actions/sendPincode',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB send pincode path',
            ],
            [
                'key' => 'endpoints_dcb.verify_pincode',
                'value' => $dcbEp['verify_pincode'] ?? '/dcb/API/VMS-DCBSubscription/actions/verifyPincode',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB verify pincode path',
            ],
            [
                'key' => 'endpoints_dcb.unsubscribe',
                'value' => $dcbEp['unsubscribe'] ?? '/dcb/API/VMSDCBSubscription/actions/unsubscribeUser',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB unsubscribe path',
            ],
            [
                'key' => 'endpoints_dcb.he_redirect',
                'value' => $dcbEp['he_redirect'] ?? '/HE/v1.3/doubleclick/sub.php',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'Header enrichment redirect path (DCB double-click)',
            ],
            [
                'key' => 'endpoints_dcb.send_sms',
                'value' => '/dcb/API/DCB-SMS/actions/sendDCBSMS',
                'type' => 'string',
                'group' => 'endpoints_dcb',
                'description' => 'DCB endpoint for send SMS',
            ],

            // --- Evina Anti-Fraud platform (shared by OTP and HE flows) ---
            [
                'key'         => 'endpoints_evina.base_url',
                'value'       => $evinaBase,
                'type'        => 'string',
                'group'       => 'endpoints_evina',
                'description' => 'Evina GetScript base URL (http://www.social-sms.com/iq-dcb)',
            ],
            [
                'key'         => 'endpoints_evina.get_script',
                'value'       => '/dcbprotect.php',
                'type'        => 'string',
                'group'       => 'endpoints_evina',
                'description' => 'Evina GetScript endpoint path (/dcbprotect.php)',
            ],
            // endpoints_vas_he.base_url — used by HeController for one-click HE Evina calls
            [
                'key'         => 'endpoints_vas_he.base_url',
                'value'       => $heBase,
                'type'        => 'string',
                'group'       => 'endpoints_vas_he',
                'description' => 'Evina base URL for VAS HE flow (same host as OTP Evina)',
            ],

            // --- VAS operator (VMS API + one-click HE, Evina) ---
            [
                'key' => 'endpoints_vas.api_host',
                'value' => $defaultVasHost,
                'type' => 'string',
                'group' => 'endpoints_vas',
                'description' => 'VAS operator API base URL (GET send/verify/unsubscribe)',
            ],
            [
                'key' => 'endpoints_vas.send_pincode',
                'value' => $vas['send_pincode'] ?? '/vms/API/VMS-Subscription/actions/sendPincode',
                'type' => 'string',
                'group' => 'endpoints_vas',
                'description' => 'VAS send pincode path (VMS-Subscription API)',
            ],
            [
                'key' => 'endpoints_vas.verify_pincode',
                'value' => $vas['verify_pincode'] ?? '/vms/API/VMS-Subscription/actions/verifyPincode',
                'type' => 'string',
                'group' => 'endpoints_vas',
                'description' => 'VAS verify pincode path (ti/ts per Evina OTP guide)',
            ],
            [
                'key' => 'endpoints_vas.unsubscribe',
                'value' => $vas['unsubscribe'] ?? '/vms/API/VMS-Subscription/actions/unsubscribeUser',
                'type' => 'string',
                'group' => 'endpoints_vas',
                'description' => 'VAS unsubscribe path',
            ],
            [
                'key' => 'endpoints_vas.he_redirect',
                'value' => $vas['he_redirect'] ?? '/HE/v1.3/oneclick/sub.php',
                'type' => 'string',
                'group' => 'endpoints_vas',
                'description' => 'VAS one-click header enrichment redirect path',
            ],
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
