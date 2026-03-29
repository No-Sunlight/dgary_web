<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

      public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

   public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class,'order_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Order::class,'order_id');

    }
    
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }


}
