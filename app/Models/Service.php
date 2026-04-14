<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'merchant_name',
        'display_name',
        'description',
        'api_username',
        'api_password',
        'sp_id',
        'service_id',
        'shortcode',
        'is_smart',
        'alphanumeric',
        'sub_message',
        'timeout',
        'settings',
        'is_active',
        'enable_evina_fraud',
        'subscribtion_type',
        'mode',
        'price',
        'currency',
        'operator',
        'country_code',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'enable_evina_fraud' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $hidden = [
        'api_password',
    ];

    /**
     * Get active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get services by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get services by operator
     */
    public function scopeForOperator($query, $operator)
    {
        return $query->where('operator', $operator);
    }

    /**
     * Get active services
     */
    public function scopeSmart($query)
    {
        return $query->where('is_smart', true);
    }

    /**
     * Get service configuration as array
     */
    public function getConfig()
    {
        // Get API host from SystemConfig based on service type
        $endpointGroup = $this->getEndpointGroupForType($this->type);

        // For Evina, use base_url; for others, use api_host
        if ($this->type === 'evina') {
            $apiHost = SystemConfig::get("{$endpointGroup}.base_url");
        } else {
            $apiHost = SystemConfig::get("{$endpointGroup}.api_host");
        }

        return [
            'host' => $apiHost,
            'username' => $this->api_username,
            'password' => $this->api_password,
            'sp_id' => $this->sp_id,
            'service_id' => $this->service_id,
            'shortcode' => $this->shortcode,
            'timeout' => $this->timeout,
            'settings' => $this->settings,
            'mode' => $this->mode,
            'price' => $this->price,
            'currency' => $this->currency,
            'enable_evina_fraud' => $this->enable_evina_fraud,
            'subscribtion_type' => $this->subscribtion_type,
        ];
    }

    /**
     * Map service type to SystemConfig endpoint group
     *
     * @param string $serviceType
     * @return string
     */
    protected function getEndpointGroupForType(string $serviceType): string
    {
        $mapping = [
            'dcb' => 'endpoints_dcb',
            'evina' => 'endpoints_evina',
            'quickfun' => 'endpoints_quickfun',
        ];

        return $mapping[$serviceType] ?? "endpoints_{$serviceType}";
    }

    /**
     * Get service by name
     */
    public static function getByName($name, $useCache = true)
    {
            return static::where('name', $name)
                ->where('is_active', true)
                ->first();
    }

    /**
     * Clear service cache (kept for compatibility, no-op)
     */
    public static function clearCache($name = null)
    {
        // Cache removed - method kept for backward compatibility
    }

    /**
     * Get all active services
     */
    public static function getAllActive($useCache = true)
    {
            return static::where('is_active', true)->get();
    }
}
