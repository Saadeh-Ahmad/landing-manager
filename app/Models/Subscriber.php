<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
    protected $fillable = [
        'msisdn',
        'service_id',
        'status',
        'subscription_plan',
        'rate',
        'subscribed_at',
        'last_active_at',
        'last_billing_date',
        'unsubscribed_at',
        'operator',
        'session_id',
        'expired_in',
        'metadata',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'last_active_at' => 'datetime',
        'last_billing_date' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'metadata' => 'array',
        'rate' => 'decimal:2',
        'expired_in' => 'integer',
    ];

    /**
     * Get the service that this subscriber belongs to
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    /**
     * Get transactions for this subscriber
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if subscriber is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get subscriber's lifetime value
     */
    public function getLifetimeValue(): float
    {
        if (!$this->subscribed_at) {
            return 0.00;
        }

        $days = now()->diffInDays($this->subscribed_at);
        return round($days * $this->rate, 2);
    }

    /**
     * Scope: Active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Inactive subscribers
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
