<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\EvinaService;
use App\Services\HeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HeController extends Controller
{
    protected $evinaService;
    protected $heService;

    public function __construct(EvinaService $evinaService, HeService $heService)
    {
        $this->evinaService = $evinaService;
        $this->heService = $heService;
    }

    /**
     * Show HE Landing Page
     *
     * @param string $serviceName - The service identifier
     * @param string $landingName
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLanding(Request $request, string $serviceName, string $landingName = 'he.landing', string $otpLandingName = 'landing.otp-subscription')
    {
        // Set locale from session, default to Arabic
        $locale = session('locale', 'ar');
        app()->setLocale($locale);
        
        Log::info("HE Landing accessed", [
            'service' => $serviceName,
            'uri' => $request->getRequestUri(),
            'method' => $request->method(),
            'secure' => $request->secure(),
            'x_forwarded_proto' => $request->header('X-Forwarded-Proto'),
            'locale' => $locale,
        ]);

        // Force HTTP for HE subscription (required for Header Enrichment to work)
        if ($request->secure() || $request->header('X-Forwarded-Proto') === 'https') {
            $httpUrl = 'http://' . $request->getHttpHost() . $request->getRequestUri();
            Log::info("Redirecting HTTPS to HTTP", ['url' => $httpUrl]);
            return redirect()->away($httpUrl, 301);
        }

        // Get service configuration
        $service = Service::getByName($serviceName);

        if (!$service) {
            Log::warning("HE Service not found", ['service' => $serviceName]);
            abort(404, 'Service not found');
        }

        if (!$service->is_active) {
            Log::warning("HE Service inactive", ['service' => $serviceName, 'service_id' => $service->service_id]);
            abort(404, 'Service is inactive');
        }

        // Extract service configuration
        $config = [
            'service_name' => $serviceName,
            'service_title' => $service->display_name,
            'enable_evina_fraud' => $service->enable_evina_fraud ?? false,
            'subscription_type' => $service->subscribtion_type ?? 'daily',
        ];

        // Prepare Evina configuration for frontend
        $evinaConfig = null;
        if ($config['enable_evina_fraud']) {
            $evinaServiceConfig = config('apis.evina');
            $baseUrl = \App\Models\SystemConfig::get('endpoints_evina.base_url', $evinaServiceConfig['endpoints']['base_url'] ?? '');
            $getScriptEndpoint = \App\Models\SystemConfig::get('endpoints_evina.get_script', '/dcbprotect.php');
            $heRedirectEndpoint = \App\Models\SystemConfig::get('endpoints_dcb.he_redirect', '/HE/v1.3/doubleclick/sub.php');

            $evinaConfig = [
                'base_url' => str_replace('https', 'http', $baseUrl),
                'get_script_endpoint' => $getScriptEndpoint,
                'he_redirect_endpoint' => $heRedirectEndpoint,
                'merchant_name' => $service->merchant_name ?? 'MediaWorld',
                'transaction_prefix' => 'MW',
                'service_name' => $service->display_name,
                'service_id' => $service->service_id,
                'sp_id' => $service->sp_id,
                'shortcode' => $service->shortcode,
            ];
        }

        Log::info("HE Landing page loaded", [
            'service' => $serviceName,
            'evina_enabled' => $config['enable_evina_fraud'],
        ]);

        return view($landingName, [
            'config' => $config,
            'evina_config' => $evinaConfig,
            'otp_landing_name' => $otpLandingName,
        ]);
    }
}

