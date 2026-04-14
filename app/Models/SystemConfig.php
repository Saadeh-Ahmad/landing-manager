<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get configuration value by key
     *
     * @param string $key
     * @param mixed $default
     * @param bool $useCache
     * @return mixed
     */
    public static function get(string $key, $default = null, bool $useCache = true)
    {
        $config = static::where('key', $key)
            ->where('is_active', true)
            ->first();

        if (!$config) {
            return $default;
        }

        return static::castValue($config->value, $config->type);
    }

    /**
     * Set configuration value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $group
     * @param string|null $description
     * @return SystemConfig
     */
    public static function set(string $key, $value, string $type = 'string', ?string $group = null, ?string $description = null)
    {
        $stringValue = static::prepareValue($value, $type);
        
        $config = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_active' => true,
            ]
        );
        
        return $config;
    }

    /**
     * Get all configs by group
     *
     * @param string $group
     * @param bool $useCache
     * @return \Illuminate\Support\Collection
     */
    public static function getByGroup(string $group, bool $useCache = true)
    {
        return static::where('group', $group)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(function ($config) {
                return [$config->key => static::castValue($config->value, $config->type)];
            });
    }

    /**
     * Cast value to appropriate type
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'float':
            case 'double':
                return (float) $value;
            case 'json':
            case 'array':
                return json_decode($value, true);
            case 'string':
            default:
                return $value;
        }
    }

    /**
     * Prepare value for storage
     *
     * @param mixed $value
     * @param string $type
     * @return string
     */
    protected static function prepareValue($value, string $type): string
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'json':
            case 'array':
                return json_encode($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Clear all config cache (kept for compatibility, no-op)
     *
     * @return void
     */
    public static function clearCache(): void
    {
        // Cache removed - method kept for backward compatibility
    }

    /**
     * Clear specific config cache (kept for compatibility, no-op)
     *
     * @param string $key
     * @return void
     */
    public static function clearCacheFor(string $key): void
    {
        // Cache removed - method kept for backward compatibility
    }

    /**
     * Get all active configs
     *
     * @param bool $useCache
     * @return \Illuminate\Support\Collection
     */
    public static function getAllActive(bool $useCache = true)
    {
        return static::where('is_active', true)->get();
    }
}
