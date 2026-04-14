<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/** @todo: remove */
class ContentPortalService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('apis.content_portal');
    }

    /**
     * Request session ID from content portal
     */
    public function requestSessionId(string $msisdn, array $userData = []): array
    {
        $mode = $this->config['mode'] ?? 'dummy';

        if ($mode === 'live') {
            return $this->requestLiveSession($msisdn, $userData);
        }

        return $this->requestDummySession($msisdn, $userData);
    }

    /**
     * Request session using live API
     */
    protected function requestLiveSession(string $msisdn, array $userData): array
    {
        try {
            $liveConfig = $this->config['live'];
            $endpoint = $liveConfig['api_url'] . $liveConfig['endpoints']['create_session'];

            $response = Http::timeout($liveConfig['timeout'])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $liveConfig['api_key'],
                    'Accept' => 'application/json',
                ])
                ->post($endpoint, [
                    'msisdn' => $msisdn,
                    'user_data' => $userData,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info("Content Portal session created for {$msisdn}");

                return [
                    'status' => 'success',
                    'message' => 'Session created successfully',
                    'session_id' => $data['session_id'],
                    'expires_in' => $data['expires_in'] ?? $this->config['session_expiry'],
                    'content_url' => route('content.portal', ['session' => $data['session_id']]),
                    'user' => $data['user'] ?? [
                        'msisdn' => $msisdn,
                        'subscription_status' => 'active',
                    ],
                ];
            }

            throw new \Exception('Portal API error: ' . $response->body());

        } catch (\Exception $e) {
            Log::error("Live Portal failed: " . $e->getMessage());

            // Fallback to dummy
            return $this->requestDummySession($msisdn, $userData);
        }
    }

    /**
     * Request session using dummy mode
     */
    protected function requestDummySession(string $msisdn, array $userData): array
    {
        $dummyConfig = $this->config['dummy'];

        // Simulate API delay
        if (isset($dummyConfig['delay_ms'])) {
            usleep($dummyConfig['delay_ms'] * 1000);
        }

        // Generate session ID
        $sessionId = 'SESS_' . Str::random($dummyConfig['session_id_length'] ?? 32);

        // Log the request
        Log::info("Session ID requested for {$msisdn}");

        return [
            'status' => 'success',
            'message' => 'Session created successfully',
            'session_id' => $sessionId,
            'expires_in' => $this->config['session_expiry'],
            'content_url' => route('content.portal', ['session' => $sessionId]),
            'user' => [
                'msisdn' => $msisdn,
                'subscription_status' => 'active',
                'subscription_plan' => 'daily',
            ],
        ];
    }

    /**
     * Validate session ID (Dummy API)
     */
    public function validateSession(string $sessionId): array
    {
        // Simulate API delay
        usleep(200000); // 0.2 seconds

        // Check if session exists in Laravel session
        $storedSessionId = session('content_session_id');

        if ($storedSessionId === $sessionId) {
            return [
                'status' => 'success',
                'valid' => true,
                'message' => 'Session is valid',
            ];
        }

        return [
            'status' => 'error',
            'valid' => false,
            'message' => 'Invalid or expired session',
        ];
    }

    /**
     * Refresh session (Dummy API)
     */
    public function refreshSession(string $sessionId): array
    {
        // Simulate API delay
        usleep(300000); // 0.3 seconds

        return [
            'status' => 'success',
            'message' => 'Session refreshed successfully',
            'session_id' => $sessionId,
            'expires_in' => 86400,
        ];
    }

    /**
     * Terminate session (Dummy API)
     */
    public function terminateSession(string $sessionId): array
    {
        // Simulate API delay
        usleep(200000); // 0.2 seconds

        // Clear from Laravel session
        session()->forget(['content_session_id', 'content_expires_at']);

        return [
            'status' => 'success',
            'message' => 'Session terminated successfully',
        ];
    }
}

