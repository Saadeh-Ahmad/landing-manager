<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    protected $fillable = [
        'username',
        'password',
        'msisdn',
        'action_type',
        'action_type_label',
        'service_id',
        'sp_id',
        'date',
        'requestid',
        'sc',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'action_type' => 'integer',
        'processed_at' => 'datetime',
    ];

    // Action type constants
    const ACTION_SUB = 1;
    const ACTION_UNSUB = 0;
    const ACTION_RENEWAL = 2;
    const ACTION_OUT_OF_BALANCE = 3;

    protected static function booted(): void
    {
        static::saving(function (Callback $callback) {
            $callback->action_type_label = self::labelForActionType(
                $callback->action_type !== null ? (int) $callback->action_type : null
            );
        });
    }

    public static function labelForActionType(?int $actionType): ?string
    {
        return match ($actionType) {
            self::ACTION_UNSUB => 'unsub',
            self::ACTION_SUB => 'sub',
            self::ACTION_RENEWAL => 'renewal',
            self::ACTION_OUT_OF_BALANCE => 'out_of_balance',
            default => null,
        };
    }
}
