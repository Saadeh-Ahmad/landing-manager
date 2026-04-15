<?php

namespace App\Services;

use App\Models\Service;
use App\Models\SystemConfig;
use Illuminate\Support\Facades\Log;

class HeService
{
    protected $evinaService;

    public function __construct(EvinaService $evinaService)
    {
        $this->evinaService = $evinaService;
    }

    /**
     * Build HE redirect URL for Evina
     *
     * @param Service $service
     * @param string $ti Transaction ID
     * @param int $ts Timestamp
     * @return string HE redirect URL
     */
    public function buildHeRedirectUrl(Service $service, string $ti, int $ts): string
    {
        $heEndpoint = SystemConfig::get(
            $service->heRedirectConfigKey(),
            $service->defaultHeRedirectPath()
        );
        $baseUrl = SystemConfig::get('endpoints_vas_he.base_url','');
        $baseUrl = str_replace('https', 'http', $baseUrl);

        // Build full URL
        $url = rtrim($baseUrl, '/') . '/' . ltrim($heEndpoint, '/');

        $serviceId = $service->service_id;
        $spId = $service->sp_id;
        $shortcode = $service->shortcode;
        $serviceName = $service->display_name ?? $service->name;
        $merchantName = $service->merchant_name ?? 'MediaWorld';

        // Build query parameters
        $params = [
            'serviceId' => $serviceId,
            'spId' => $spId,
            'shortcode' => $shortcode,
            'ti' => $ti,
            'ts' => $ts,
            'servicename' => $serviceName,
            'merchantname' => $merchantName,
        ];

        $url .= '?' . http_build_query($params);

        Log::info('HE Redirect URL built', [
            'service' => $service->name,
            'ti' => $ti,
            'ts' => $ts,
            'url' => $url,
        ]);

        return $url;
    }

    /**
     * Get anti-fraud script for HE flow
     *
     * @param Service $service
     * @param string $ti Transaction ID
     * @param int $ts Timestamp
     * @param string $teElement Target element selector (default: #subscribe_btn)
     * @return array ['script' => string, 'success' => bool]
     */
    public function getAntifraudScript(Service $service, string $ti, int $ts, string $teElement = '#subscribe_btn'): array
    {
        return $this->evinaService->getAntifraudScript($service, $ti, $ts, 'he', $teElement);
    }
}

