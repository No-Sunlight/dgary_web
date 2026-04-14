<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(CustomerCoupon::class, 'coupon_id');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_coupons')
            ->withPivot('used_at', 'order_id')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
}

