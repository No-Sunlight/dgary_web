<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
     protected $guarded = [];

    protected static function booted(): void
    {
        static::saved(function (Delivery $delivery): void {
            $order = $delivery->order;

            if (!$order) {
                return;
            }

            $mappedOrderStatus = match ($delivery->status) {
                'pending' => 'Pending',
                'ready', 'in_transit' => 'Ready',
                'completed' => 'Completed',
                'canceled', 'refund' => 'Canceled',
                default => null,
            };

            if ($mappedOrderStatus !== null && $order->status !== $mappedOrderStatus) {
                $order->update(['status' => $mappedOrderStatus]);
            }
        });
    }

    protected $casts = [
        'destination_lat' => 'float',
        'destination_lng' => 'float',
        'driver_lat' => 'float',
        'driver_lng' => 'float',
        'driver_location_updated_at' => 'datetime',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    
   public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

      public function driver(): BelongsTo //No cambiar el nombre
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }


}
