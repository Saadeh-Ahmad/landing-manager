<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SessionService
{
    private $billingApiUrl;
    private $portalGatewayUrl;
    private $serviceId;
    private $privateKeyPath;

    public function __construct()
    {
        // Load configuration from system_configs table
        $this->billingApiUrl = \App\Models\SystemConfig::get('billing_api_url', 'https://billing.quickfun.games/mediaworld/token');
        $this->portalGatewayUrl = \App\Models\SystemConfig::get('portal_gateway_url', 'https://billing.quickfun.games/mediaworld/gateway');
        $this->serviceId = \App\Models\SystemConfig::get('session_service_id', 7);
        $this->privateKeyPath = \App\Models\SystemConfig::get('private_key_path', '/var/www/html/private_key.pem');
    }

    /**
     * Request session ID from billing API
     *
     * @param string $msisdn User ID (phone number)
     * @param string $serviceName Service name to get subscription type
     * @return array|null
     */
    public function requestSessionId(string $msisdn, string $serviceName)
    {
        try {
            $expiresIn = $this->calculateExpiry($serviceName);
            // Generate JWT token
            $token = $this->generateJWT($msisdn, $expiresIn);

            Log::info('---------- BILLING API: Session ID Request ----------', [
                'timestamp' => now()->toDateTimeString(),
                'billing_url' => $this->billingApiUrl,
                'msisdn' => $msisdn,
                'service_id' => $this->serviceId,
                'expires_at' => date('Y-m-d H:i:s', $expiresIn),
                'jwt_token_length' => strlen($token),
            ]);

            // Make API request
            $startTime = microtime(true);
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->post($this->billingApiUrl, [
                    'jwt' => $token
                ]);
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('---------- BILLING API: Session ID Response ----------', [
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'response_time_ms' => $responseTime,
                'response_body' => $response->body(),
            ]);

            /** @todo: add retry logic (failed OR sid is empty) */
            if ($response->successful()) {
                $data = $response->json();

                $sid = $data['sid'] ?? null;
                $status = !empty($sid);
                $portalUrl = $status ? $this->generatePortalUrl($sid) : null;
                if (!empty($sid) && !empty($portalUrl)) {
                    session([
                        'quick_fun_session' => [
                            'sid' => $sid,
                            'portal_url' => $portalUrl,
                            'redirect_url' => false,
                        ]
                    ]);
                }

                return [
                    'success' => $status,
                    'sid' => $sid,
                    'portal_url' => $portalUrl,
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Session Request Error', [
                'msisdn' => $msisdn,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate expiry timestamp based on service subscription type
     *
     * @param string|null $serviceName
     * @return int UNIX epoch timestamp
     */
    private function calculateExpiry(?string $serviceName = null): int
    {
        $duration = 86400; // Default: 24 hours (daily)

        if ($serviceName) {
            $service = \App\Models\Service::getByName($serviceName);
            if ($service && $service->subscribtion_type) {
                switch ($service->subscribtion_type) {
                    case 'weekly':
                        $duration = 604800; // 7 days
                        break;
                    case 'monthly':
                        $duration = 2592000; // 30 days
                        break;
                    case 'daily':
                    default:
                        $duration = 86400; // 24 hours
                        break;
                }
            }
        }

        return time() + $duration;
    }

    /**
     * Generate JWT token signed with private key
     *
     * @param string $msisdn
     * @param int $expiresIn
     * @return string
     * @throws \Exception
     */
    private function generateJWT(string $msisdn, int $expiresIn): string
    {
        $payload = [
            'expires_in' => $expiresIn,
            'user_id' => $msisdn,
            'service_id' => $this->serviceId,
        ];

        // Load private key from system config
        if (!file_exists($this->privateKeyPath)) {
            throw new \Exception('Private key not found at: ' . $this->privateKeyPath);
        }

        $privateKey = file_get_contents($this->privateKeyPath);

        // Generate JWT token with RS256 algorithm
        return JWT::encode($payload, $privateKey, 'RS256');
    }

    /**
     * Generate portal gateway URL (direct billing gateway URL)
     *
     * @param string $sid Session ID
     * @return string
     */
    public function generatePortalUrl(string $sid): string
    {
        return $this->portalGatewayUrl . '/' . $sid . '/' . $this->serviceId;
    }

    /**
     * Generate local portal redirect URL (goes through application first)
     * This should be used in SMS to ensure proper session handling
     *
     * @param string $sid Session ID
     * @return string
     */
    public function generateLocalPortalUrl(string $sid): string
    {
        // Use the local redirect route which will then redirect to billing gateway
        return url('/portal/' . $sid);
    }

    /**
     * Get billing API URL
     *
     * @return string
     */
    public function getBillingApiUrl(): string
    {
        return $this->billingApiUrl;
    }

    /**
     * Set billing API URL
     *
     * @param string $url
     * @return void
     */
    public function setBillingApiUrl(string $url): void
    {
        $this->billingApiUrl = $url;
    }

    /**
     * Get portal gateway URL
     *
     * @return string
     */
    public function getPortalGatewayUrl(): string
    {
        return $this->portalGatewayUrl;
    }

    /**
     * Set portal gateway URL
     *
     * @param string $url
     * @return void
     */
    public function setPortalGatewayUrl(string $url): void
    {
        $this->portalGatewayUrl = $url;
    }
}

