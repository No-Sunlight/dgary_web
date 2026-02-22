<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerCoupon extends Model
{
    protected $guarded = [];


       public function coupons(): BelongsTo
    {
        return $this->belongsTo(Coupon::class,'id_coupon');
    }

        public function customers(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'id_customer');
    }

    


}
