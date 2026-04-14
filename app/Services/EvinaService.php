<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvinaService
{
    protected $service;
    protected $config;
    protected $credentials;
    protected $endpoints;
    protected $baseUrl;

    public function __construct()
    {
        // Fallback config from file (for backward compatibility)
        $this->config = config('apis.evina');
        $this->credentials = $this->config['credentials'];

        // Get base URL and endpoints from system config or fallback to config file
        $this->baseUrl = \App\Models\SystemConfig::get('endpoints_evina.base_url', $this->config['endpoints']['base_url'] ?? '');

        // Build endpoints array using system config with fallback
        $this->endpoints = [
            'base_url' => $this->baseUrl,
            'get_script' => \App\Models\SystemConfig::get('endpoints_evina.get_script', '/dcbprotect.php'),
            'he_redirect' => \App\Models\SystemConfig::get('endpoints_dcb.he_redirect', '/HE/v1.3/doubleclick/sub.php'),
        ];
    }

    /**
     * Generate unique transaction ID for Evina
     */
    public function generateTransactionId(): string
    {
        $prefix = $this->credentials['transaction_prefix'];
        $timestamp = time();
        $random = rand(1000, 9999);
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Generate current timestamp (epoch format)
     */
    public function generateTimestamp(): int
    {
        return time();
    }

    /**
     * Get Evina anti-fraud script
     *
     * @param string $ti Transaction ID
     * @param int $ts Timestamp
     * @param string $type Script type ('pin' or 'he')
     * @param string $teElement Target element selector for triggering events
     * @return array ['script' => 'script html', 'success' => bool]
     */
    public function getAntifraudScript($service, string $ti, int $ts, string $type = 'pin', string $teElement = '#cta_button'): array
    {
        $this->service = $service;

        try {
            $url = $this->endpoints['base_url'] . $this->endpoints['get_script'];
            $url = str_replace('https', 'http', $url);

            // Get merchant name from config
            $merchantName = $this->config['credentials']['merchant_name'] ?? 'MediaWorld';

            $params = [
                'action' => 'script',
                'servicename' => $this->service->display_name ?? $this->service->name,
                'merchantname' => $merchantName,
                'type' => $type,
                'ti' => $ti,
                'ts' => $ts,
                'te' => $teElement,
            ];

            $response = Http::timeout(10)->get($url, $params);
            if ($response->successful()) {
                $scriptContent = $response->body();

                Log::info('Evina anti-fraud script loaded', [
                    'ti' => $ti,
                    'type' => $type,
                ]);

                return [
                    'script' => $scriptContent,
                    'success' => true,
                ];
            }

            throw new \Exception('Failed to load anti-fraud script: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Evina Anti-fraud Script Error: ' . $e->getMessage());

            return [
                'script' => '<!-- Anti-fraud script failed to load -->',
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
