<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\EvinaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OtpController extends Controller
{
    protected $evinaService;

    public function __construct(EvinaService $evinaService)
    {
        $this->evinaService = $evinaService;
    }

    /**
     * Show OTP Landing Page
     *
     * @param string $serviceName - The service identifier
     * @param string $landingName
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showLanding(Request $request, string $serviceName, string $landingName = 'otp.main')
    {
        // Get service configuration
        $service = Service::getByName($serviceName);

        if (!$service || !$service->is_active) {
            abort(404, 'Service not found or inactive');
        }

        // Set locale from session, default to Arabic
        $locale = session('locale', 'ar');
        app()->setLocale($locale);

        // Preserve locale before flushing
        $preservedLocale = session('locale', 'ar');
        session()->flush();
        session(['locale' => $preservedLocale]);

        // Get campaign from request or default
        $campaign = $request->query('campaign', 'campaign1');

        // Store service info in session for subsequent requests
        session([
            'service_name' => $serviceName,
            'campaign' => $campaign,
        ]);

        // Extract service configuration
        $config = [
            'service_name' => $serviceName,
            'service_title' => $service->display_name ?? $service->name,
            'campaign' => $campaign,
            'enable_evina_fraud' => $service->enable_evina_fraud ?? false,
            'pin_length' => $service->settings['pin_length'] ?? 4,
            'subscription_type' => $service->subscribtion_type ?? 'daily',
        ];

        // Prepare Evina configuration for frontend
        $evinaConfig = null;
        if ($config['enable_evina_fraud']) {
            $evinaServiceConfig = config('apis.evina');
            $baseUrl = \App\Models\SystemConfig::get('endpoints_evina.base_url', '');
            $getScriptEndpoint = \App\Models\SystemConfig::get('endpoints_evina.get_script', '/dcbprotect.php');

            $evinaConfig = [
                'base_url' => str_replace('https', 'http', $baseUrl),
                'get_script_endpoint' => $getScriptEndpoint,
                'merchant_name' => $evinaServiceConfig['credentials']['merchant_name'] ?? 'MediaWorld',
                'transaction_prefix' => $evinaServiceConfig['credentials']['transaction_prefix'] ?? 'MW',
                'service_name' => $service->display_name ?? $service->name,
            ];
        }

        Log::info("OTP Landing page loaded", [
            'service' => $serviceName,
            'evina_enabled' => $config['enable_evina_fraud'],
        ]);

        return view($landingName, [
            'config' => $config,
            'evina_config' => $evinaConfig,
        ]);
    }
}

