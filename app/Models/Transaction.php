<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'subscriber_id',
        'msisdn',
        'type',
        'status',
        'amount',
        'currency',
        'operator',
        'callback_url',
        'request_payload',
        'response_payload',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the subscriber for this transaction
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Scope: Successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: By type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
