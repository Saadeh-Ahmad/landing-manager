<?php

namespace App\Services;

use App\Models\Service;
use App\Models\SystemConfig;

class ServiceConfig
{
    /**
     * Get service configuration by name
     *
     * @param string $serviceName
     * @param bool $useCache
     * @return array|null
     */
    public static function get(string $serviceName, bool $useCache = true): ?array
    {
        $service = Service::getByName($serviceName, $useCache);

        if (!$service) {
            return null;
        }

        return $service->getConfig();
    }

    /**
     * Get service model by name
     *
     * @param string $serviceName
     * @param bool $useCache
     * @return Service|null
     */
    public static function getService(string $serviceName, bool $useCache = true): ?Service
    {
        return Service::getByName($serviceName, $useCache);
    }

    /**
     * Get endpoint URL for a service directly from SystemConfig
     *
     * @param string $serviceName
     * @param string $endpointKey
     * @param array $params Query parameters
     * @return string|null
     */
    public static function getEndpointUrl(string $serviceName, string $endpointKey, array $params = []): ?string
    {
        $service = Service::getByName($serviceName);

        if (!$service) {
            return null;
        }

        // Map service type to endpoint group
        $endpointGroup = self::getEndpointGroup($service->type);
        
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
    protected static function getEndpointGroup(string $serviceType): string
    {
        // Map service types to SystemConfig endpoint groups
        $mapping = [
            'dcb' => 'endpoints_dcb',
            'evina' => 'endpoints_evina',
            'quickfun' => 'endpoints_quickfun',
        ];

        return $mapping[$serviceType] ?? "endpoints_{$serviceType}";
    }

    /**
     * Build send pincode URL with parameters
     *
     * @param string $serviceName
     * @param string $msisdn
     * @return string|null
     */
    public static function buildSendPincodeUrl(string $serviceName, string $msisdn): ?string
    {
        $service = Service::getByName($serviceName);

        if (!$service) {
            return null;
        }

        $params = [
            'user' => $service->api_username,
            'password' => $service->api_password,
            'msisdn' => $msisdn,
            'shortcode' => $service->shortcode,
            'serviceId' => $service->service_id,
            'spId' => $service->sp_id,
        ];

        return self::getEndpointUrl($serviceName, 'send_pincode', $params);
    }

    /**
     * Build verify pincode URL with parameters
     *
     * @param string $serviceName
     * @param string $msisdn
     * @param string $pincode
     * @return string|null
     */
    public static function buildVerifyPincodeUrl(string $serviceName, string $msisdn, string $pincode): ?string
    {
        $service = Service::getByName($serviceName);

        if (!$service) {
            return null;
        }

        $params = [
            'user' => $service->api_username,
            'password' => $service->api_password,
            'msisdn' => $msisdn,
            'pincode' => $pincode,
            'shortcode' => $service->shortcode,
            'serviceId' => $service->service_id,
            'spId' => $service->sp_id,
        ];

        return self::getEndpointUrl($serviceName, 'verify_pincode', $params);
    }

    /**
     * Build unsubscribe URL with parameters
     *
     * @param string $serviceName
     * @param string $msisdn
     * @return string|null
     */
    public static function buildUnsubscribeUrl(string $serviceName, string $msisdn): ?string
    {
        $service = Service::getByName($serviceName);

        if (!$service) {
            return null;
        }

        $params = [
            'user' => $service->api_username,
            'password' => $service->api_password,
            'msisdn' => $msisdn,
            'shortcode' => $service->shortcode,
            'serviceId' => $service->service_id,
            'spId' => $service->sp_id,
        ];

        return self::getEndpointUrl($serviceName, 'unsubscribe', $params);
    }

    /**
     * Get all active services
     *
     * @param string|null $type Filter by type
     * @param bool $useCache
     * @return \Illuminate\Support\Collection
     */
    public static function getAllActive(?string $type = null, bool $useCache = true)
    {
        $services = Service::getAllActive($useCache);

        if ($type) {
            return $services->where('type', $type);
        }

        return $services;
    }

    /**
     * Get service setting value
     *
     * @param string $serviceName
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getSetting(string $serviceName, string $key, $default = null)
    {
        $service = Service::getByName($serviceName);

        if (!$service || !isset($service->settings[$key])) {
            return $default;
        }

        return $service->settings[$key];
    }

    /**
     * Check if service is in live mode
     *
     * @param string $serviceName
     * @return bool
     */
    public static function isLive(string $serviceName): bool
    {
        $service = Service::getByName($serviceName);

        return $service && $service->mode === 'live';
    }

    /**
     * Check if service is active
     *
     * @param string $serviceName
     * @return bool
     */
    public static function isActive(string $serviceName): bool
    {
        $service = Service::getByName($serviceName);

        return $service && $service->is_active;
    }

    /**
     * Clear all service caches
     */
    public static function clearCache(?string $serviceName = null): void
    {
        Service::clearCache($serviceName);
    }
}

