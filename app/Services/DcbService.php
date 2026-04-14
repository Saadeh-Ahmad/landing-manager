<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Subscriber;
use App\Models\SystemConfig;
use App\Services\SessionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class DcbService
{
    /**
     * Static storage for dummy/test PIN codes (in-memory only)
     * Key: MSISDN, Value: ['pincode' => string, 'msisdn' => string, 'service' => string, 'created_at' => int]
     */
    protected static $dummyPinStorage = [];
    /**
     * Send pincode to the given MSISDN
     *
     * @param string $serviceName Service identifier
     * @param string $msisdn Phone number
     * @return array Response array with status, message, and data
     */
    public function sendPincode(string $serviceName, string $msisdn): array
    {
        try {
            // Get service configuration
            $service = Service::getByName($serviceName);

            if (!$service) {
                return [
                    'status' => 'error',
                    'message' => 'Service not found',
                ];
            }

            // Check if service is in test/dummy mode
            $mode = $service->mode ?? 'live';
//            if ($mode === 'test' || $mode === 'dummy') {
//                return $this->sendDummyPincode($serviceName, $msisdn);
//            }

            // Build API URL using ServiceConfig
            $params = [
                'user' => $service->api_username,
                'password' => $service->api_password,
                'msisdn' => $msisdn,
                'shortcode' => $service->shortcode,
                'serviceId' => $service->service_id,
                'spId' => $service->sp_id,
            ];

            $url = $this->buildEndpointUrl($service, 'send_pincode', $params);

            if (!$url) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to build endpoint URL',
                ];
            }

            Log::info('========== DCB SEND PINCODE REQUEST ==========', [
                'timestamp' => now()->toDateTimeString(),
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'api_url' => $url,
                'ip_address' => request()->ip(),
            ]);

            // Make API request
            $response = Http::retry(
                3,
                1000,
                fn ($e) => $e instanceof ConnectionException || $e->getCode() === 56
            )
                ->timeout($service->timeout ?? 30)
                ->withOptions([
                    'verify'  => false,
                    'version' => 1.1,
                ])
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->get($url);


            Log::info('========== DCB SEND PINCODE RESPONSE ==========', [
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'response_body' => $response->body(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                return [
                    'status' => 'success',
                    'message' => 'PIN code sent successfully',
                    'data' => $responseData,
                    'msisdn' => $msisdn,
                ];
            }
            if ($response->status() == 400) {
                $result = $this->handleUserAlreadyExists($response->json() ?? [], $msisdn, $service, $serviceName);
                if ($result !== null) {
                    return $result;
                }
            }

            return [
                'status' => 'error',
                'message' => 'Failed to send PIN code',
                'error' => $response->body(),
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            // Laravel's retry() throws RequestException for 4xx/5xx — handle 400 "User Already Exist" here
            if ($e instanceof \Illuminate\Http\Client\RequestException && $e->response->status() === 400) {
                $service = Service::getByName($serviceName);
                if ($service) {
                    $result = $this->handleUserAlreadyExists($e->response->json() ?? [], $msisdn, $service, $serviceName);
                    if ($result !== null) {
                        return $result;
                    }
                }
            }

            Log::error('DCB Send Pincode Error: ' . $e->getMessage(), [
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'exception' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify pincode for the given MSISDN
     *
     * @param string $serviceName Service identifier
     * @param string $msisdn Phone number
     * @param string $pincode PIN code to verify
     * @param string|null $ti Transaction ID (for Evina)
     * @param int|null $ts Timestamp (for Evina)
     * @return array Response array with verified status and data
     */
    public function verifyPincode(string $serviceName, string $msisdn, string $pincode, ?string $ti = null, ?int $ts = null): array
    {
        try {
            // Get service configuration
            $service = Service::getByName($serviceName);

            if (!$service) {
                return [
                    'verified' => false,
                    'message' => 'Service not found',
                ];
            }

            // Check if service is in test/dummy mode
            $mode = $service->mode ?? 'live';
//            if ($mode === 'test' || $mode === 'dummy') {
//                return $this->verifyDummyPincode($serviceName, $msisdn, $pincode);
//            }

            // Build API URL using ServiceConfig
            $params = [
                'user' => $service->api_username,
                'password' => $service->api_password,
                'msisdn' => $msisdn,
                'pincode' => $pincode,
                'shortcode' => $service->shortcode,
                'serviceId' => $service->service_id,
                'spId' => $service->sp_id,
            ];

            // Add Evina-specific params if provided
            if ($ti !== null) {
                $params['ti'] = $ti;
            }
            if ($ts !== null) {
                $params['ts'] = $ts;
            }

            $url = $this->buildEndpointUrl($service, 'verify_pincode', $params);

            if (!$url) {
                return [
                    'verified' => false,
                    'message' => 'Failed to build endpoint URL',
                ];
            }

            Log::info('========== DCB VERIFY PINCODE REQUEST ==========', [
                'timestamp' => now()->toDateTimeString(),
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'api_url' => $url,
                'has_ti_ts' => ($ti !== null && $ts !== null),
            ]);

            $response = Http::retry(
                3,
                1000,
                fn ($e) => $e instanceof ConnectionException || $e->getCode() === 56
            )
                ->timeout($service->timeout ?? 30)
                ->withOptions([
                    'verify'  => false,
                    'version' => 1.1,
                ])
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->get($url);

            Log::info('========== DCB VERIFY PINCODE RESPONSE ==========', [
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'response_body' => $response->body(),
            ]);

            if ($response->successful() || ($response->status())) {
                $responseData = $response->json();

                // Check if verification was successful
                $responseStatus = $responseData['status'] ?? '';
                $responseStatus = strtolower($responseStatus);

                $responseMsg = $responseData['msg'] ?? '';
                $responseMsg = rtrim($responseMsg, ".");
                $responseMsg = strtolower($responseMsg);

                $isVerified = ($responseStatus === 'success') ||
                              ($responseStatus === 'failed' && $responseMsg === 'user already exist');

                // Create or update subscriber after successful verification
                if ($isVerified) {
                    $this->createOrUpdateSubscriber($msisdn, $service);
                }

                return [
                    'verified' => $isVerified,
                    'message' => $responseData['msg'] ?? 'Verification completed',
                    'data' => $responseData,
                    'msisdn' => $msisdn,
                ];
            }

            return [
                'verified' => false,
                'message' => 'Failed to verify PIN code',
                'error' => $response->body(),
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('DCB Verify Pincode Error: ' . $e->getMessage(), [
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'exception' => $e->getMessage(),
            ]);

            return [
                'verified' => false,
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Unsubscribe user from the service
     *
     * @param string $serviceName Service identifier
     * @param string $msisdn Phone number
     * @return array Response array with status and message
     */
    public function unsubscribe(string $serviceName, string $msisdn): array
    {
        try {
            // Get service configuration
            $service = Service::getByName($serviceName);

            if (!$service) {
                return [
                    'status' => 'error',
                    'message' => 'Service not found',
                ];
            }

            // Check if service is in test/dummy mode
            $mode = $service->mode ?? 'live';
//            if ($mode === 'test' || $mode === 'dummy') {
//                return $this->unsubscribeDummyUser($serviceName, $msisdn);
//            }

            // Build API URL using ServiceConfig
            $params = [
                'user' => $service->api_username,
                'password' => $service->api_password,
                'msisdn' => $msisdn,
                'shortcode' => $service->shortcode,
                'serviceId' => $service->service_id,
                'spId' => $service->sp_id,
            ];

            $url = $this->buildEndpointUrl($service, 'unsubscribe', $params);

            if (!$url) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to build endpoint URL',
                ];
            }

            Log::info('========== DCB UNSUBSCRIBE REQUEST ==========', [
                'timestamp' => now()->toDateTimeString(),
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'api_url' => $url,
            ]);

            $response = Http::retry(
                3,
                1000,
                fn ($e) => $e instanceof ConnectionException || $e->getCode() === 56
            )
                ->timeout($service->timeout ?? 30)
                ->withOptions([
                    'verify'  => false,
                    'version' => 1.1,
                ])
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->get($url);

            Log::info('========== DCB UNSUBSCRIBE RESPONSE ==========', [
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'response_body' => $response->body(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                return [
                    'status' => 'success',
                    'message' => 'User unsubscribed successfully',
                    'data' => $responseData,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to unsubscribe user',
                'error' => $response->body(),
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('DCB Unsubscribe Error: ' . $e->getMessage(), [
                'service' => $serviceName,
                'msisdn' => $msisdn,
                'exception' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    public function sendSMS(Service $service, string $msisdn, string $portalUrl): array
    {
        try {
            // Build message with portal URL (keep URL in readable format)
            $message = trim($service->sub_message ?? '');
            if (!empty($message)) {
                $message .= " - $portalUrl";
            } else {
                $message = "$portalUrl";
            }
            $message = bin2hex(mb_convert_encoding($message, 'UCS-2', 'auto'));

            // Build API URL using ServiceConfig
            $params = [
                'user' => $service->api_username,
                'password' => $service->api_password,
                'msisdn' => $msisdn,
                'shortcode' => $service->shortcode,
                'serviceId' => $service->service_id,
                'spId' => $service->sp_id,
                'msg' => $message,
                'alphanumeric' => $service->alphanumeric,
                'spTransactionId' => 'SMS-' . time() . rand(1000, 9999)
            ];
            $url = $this->buildEndpointUrl($service, 'send_sms', $params);

            if (!$url) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to build endpoint URL',
                ];
            }

            Log::info('========== DCB SEND SMS REQUEST ==========', [
                'timestamp' => now()->toDateTimeString(),
                'service' => $service->service_id,
                'msisdn' => $msisdn,
                'api_url' => $url,
                'ip_address' => request()->ip(),
            ]);

            $response = Http::retry(
                3,
                1000,
                fn ($e) => $e instanceof ConnectionException || $e->getCode() === 56
            )
                ->timeout($service->timeout ?? 30)
                ->withOptions([
                    'verify'  => false,
                    'version' => 1.1,
                ])
                ->withHeaders([
                    'Accept' => '*/*',
                ])
                ->get($url);

            Log::info('========== DCB SEND SMS RESPONSE ==========', [
                'timestamp' => now()->toDateTimeString(),
                'status_code' => $response->status(),
                'success' => $response->successful(),
                'response_body' => $response->body(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                return [
                    'status' => 'success',
                    'message' => 'SMS sent successfully',
                    'data' => $responseData,
                    'msisdn' => $msisdn,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to send SMS',
                'error' => $response->body(),
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('DCB Send SMS Error: ' . $e->getMessage(), [
                'service' => $service->service_id,
                'msisdn' => $msisdn,
                'exception' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build endpoint URL with parameters
     *
     * @param Service $service
     * @param string $endpointKey
     * @param array $params
     * @return string|null
     */
    protected function buildEndpointUrl(Service $service, string $endpointKey, array $params = []): ?string
    {
        // Map service type to endpoint group
        $endpointGroup = $this->getEndpointGroup($service->type);

        // Get API host from SystemConfig
        // For Evina, use base_url; for others, use api_host
        if ($service->type === 'evina') {
            $apiHost = SystemConfig::get("{$endpointGroup}.base_url");
        } else {
            $apiHost = SystemConfig::get("{$endpointGroup}.api_host");
        }

        // Fetch endpoint path from SystemConfig
        $configKey = "{$endpointGroup}.{$endpointKey}";
        $endpointPath = SystemConfig::get($configKey);

        if (!$endpointPath || !$apiHost) {
            return null;
        }

        // Build full URL
        $url = rtrim($apiHost, '/') . '/' . ltrim($endpointPath, '/');

        // Add query parameters if provided
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Map service type to SystemConfig endpoint group
     *
     * @param string $serviceType
     * @return string
     */
    protected function getEndpointGroup(string $serviceType): string
    {
        $mapping = [
            'dcb' => 'endpoints_dcb',
            'evina' => 'endpoints_evina',
            'quickfun' => 'endpoints_quickfun',
        ];

        return $mapping[$serviceType] ?? "endpoints_{$serviceType}";
    }

    /**
     * Send dummy/test pincode
     *
     * @param string $serviceName
     * @param string $msisdn
     * @return array
     */
    protected function sendDummyPincode(string $serviceName, string $msisdn): array
    {
        // Simulate network delay
        usleep(500000); // 500ms

        $pincode = rand(1000, 9999);

        // Store in static array for verification (in-memory only, cleared on request end)
        $cacheData = [
            'pincode' => $pincode,
            'msisdn' => $msisdn,
            'service' => $serviceName,
            'created_at' => time(),
        ];

        // Store in static array (cleared when PHP process ends)
        static::$dummyPinStorage[$msisdn] = $cacheData;

        // Clean up old entries (older than 5 minutes)
        $this->cleanupDummyStorage();

        Log::info("DCB DUMMY: PIN sent to {$msisdn}: {$pincode}", [
            'service' => $serviceName,
            'msisdn' => $msisdn,
            'expires_in' => '5 minutes (in-memory)',
        ]);

        return [
            'status' => 'success',
            'message' => 'PIN sent successfully (TEST MODE)',
            'data' => [
                'pincode' => $pincode, // Only in test mode
                'msisdn' => $msisdn,
            ],
            'msisdn' => $msisdn,
        ];
    }

    /**
     * Verify dummy/test pincode
     *
     * @param string $serviceName
     * @param string $msisdn
     * @param string $pincode
     * @return array
     */
    protected function verifyDummyPincode(string $serviceName, string $msisdn, string $pincode): array
    {
        // Simulate network delay
        usleep(500000); // 500ms

        // Retrieve from static array
        $cacheData = static::$dummyPinStorage[$msisdn] ?? null;

        // Clean up old entries
        $this->cleanupDummyStorage();

        $expectedPincode = $cacheData['pincode'] ?? null;
        $expectedMsisdn = $cacheData['msisdn'] ?? null;

        $isValid = ($pincode === (string)$expectedPincode && $msisdn === $expectedMsisdn);

        Log::info("DCB DUMMY: PIN verification for {$msisdn}: " . ($isValid ? 'SUCCESS' : 'FAILED'), [
            'service' => $serviceName,
            'provided_pin' => $pincode,
            'expected_pin' => $expectedPincode,
            'provided_msisdn' => $msisdn,
            'expected_msisdn' => $expectedMsisdn,
            'storage_exists' => !is_null($cacheData),
            'storage_age_seconds' => $cacheData ? (time() - $cacheData['created_at']) : null,
        ]);

        if ($isValid) {
            // Clear test data from storage
            unset(static::$dummyPinStorage[$msisdn]);

            return [
                'verified' => true,
                'message' => 'PIN verified successfully (TEST MODE)',
                'data' => [
                    'status' => 'Success',
                    'msisdn' => $msisdn,
                ],
                'msisdn' => $msisdn,
            ];
        }

        return [
            'verified' => false,
            'message' => 'Invalid PIN code (TEST MODE)',
            'data' => [
                'status' => 'Failed',
            ],
        ];
    }

    /**
     * Clean up old dummy PIN storage entries (older than 5 minutes)
     */
    protected function cleanupDummyStorage(): void
    {
        $currentTime = time();
        $maxAge = 300; // 5 minutes

        foreach (static::$dummyPinStorage as $msisdn => $data) {
            if (isset($data['created_at']) && ($currentTime - $data['created_at']) > $maxAge) {
                unset(static::$dummyPinStorage[$msisdn]);
            }
        }
    }

    /**
     * Unsubscribe dummy/test user
     *
     * @param string $serviceName
     * @param string $msisdn
     * @return array
     */
    protected function unsubscribeDummyUser(string $serviceName, string $msisdn): array
    {
        // Simulate network delay
        usleep(500000); // 500ms

        Log::info("DCB DUMMY: User unsubscribed {$msisdn} (TEST MODE)", [
            'service' => $serviceName,
        ]);

        return [
            'status' => 'success',
            'message' => 'User unsubscribed successfully (TEST MODE)',
            'data' => [
                'status' => 'Success',
                'msisdn' => $msisdn,
            ],
        ];
    }

    /**
     * Normalize MSISDN by adding country code 964 if missing
     */
    protected function normalizeMsisdn(string $msisdn): string
    {
        // Remove any non-numeric characters
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);

        // If it doesn't start with 964, add it
        if (!str_starts_with($msisdn, '964')) {
            $msisdn = '964' . $msisdn;
        }

        return $msisdn;
    }

    /**
     * Handle the "User Already Exist" 400 response.
     *
     * Creates/updates the subscriber, fetches a session ID, and returns the
     * success response array. Returns null if the response data does not
     * indicate an already-existing user.
     *
     * @param array  $responseData Decoded JSON body from the API
     * @param string $msisdn
     * @param Service $service
     * @param string $serviceName
     * @return array|null
     */
    protected function handleUserAlreadyExists(array $responseData, string $msisdn, Service $service, string $serviceName): ?array
    {
        $responseStatus = strtolower($responseData['status'] ?? '');
        $responseMsg    = strtolower(rtrim($responseData['msg'] ?? '', '.'));

        if ($responseStatus !== 'failed' || $responseMsg !== 'user already exist') {
            return null;
        }

        $this->createOrUpdateSubscriber($msisdn, $service);

        $sessionService = new SessionService();
        $sessionResult  = $sessionService->requestSessionId($msisdn, $serviceName);

        if ($sessionResult['success'] ?? false) {
            if (isset($sessionResult['sid'])) {
                $responseData['session_id'] = $sessionResult['sid'];
            }
            if (isset($sessionResult['portal_url'])) {
                $responseData['portal_url'] = $sessionResult['portal_url'];
            }
        }

        Log::info('DCB Send Pincode: user already subscribed', [
            'service' => $serviceName,
            'msisdn'  => $msisdn,
        ]);

        return [
            'status'             => 'success',
            'message'            => 'User already subscribed',
            'data'               => $responseData,
            'msisdn'             => $msisdn,
            'already_subscribed' => true,
        ];
    }

    /**
     * Create or update subscriber after successful verification
     *
     * @param string $msisdn
     * @param Service $service
     * @return Subscriber
     */
    protected function createOrUpdateSubscriber(string $msisdn, Service $service): Subscriber
    {
        // Normalize MSISDN to ensure consistency
        $normalizedMsisdn = $this->normalizeMsisdn($msisdn);

        $subscriber = Subscriber::firstOrCreate(
            [
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ],
            [
                'status' => 'active',
                'subscription_plan' => $service->subscribtion_type,
                'rate' => $service->price,
                'subscribed_at' => now(),
                'last_active_at' => now(),
                'operator' => $service->operator,
            ]
        );

        // Update existing subscriber if already exists (not newly created)
        $wasNew = $subscriber->wasRecentlyCreated;
        if (!$wasNew) {
            // Don't update subscribed_at for existing subscribers - keep original subscription date
            // service_id is part of unique key, so it won't change
            $subscriber->update([
                'status' => 'active',
                'last_active_at' => now(),
            ]);
        }

        Log::info('Subscriber created/updated', [
            'msisdn' => $normalizedMsisdn,
            'service' => $service->name,
            'subscriber_id' => $subscriber->id,
            'was_recently_created' => $wasNew,
            'subscribed_at' => $subscriber->subscribed_at?->toDateTimeString(),
        ]);

        return $subscriber;
    }
}

