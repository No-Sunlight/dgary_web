<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'stripe_response' => 'json',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Scope para pagos exitosos
     */
    public static function successful()
    {
        return self::where('status', 'succeeded');
    }

    /**
     * Scope para pagos pendientes
     */
    public static function pending()
    {
        return self::where('status', 'pending');
    }
}
