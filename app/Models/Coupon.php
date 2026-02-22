<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $guarded = [];

       public function user_coupon(): HasMany
    {
        return $this->hasMany(CustomerCoupon::class,'id_coupon');
    }

    
}
