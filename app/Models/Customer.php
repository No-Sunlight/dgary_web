<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $guarded = [];

       public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

       public function user_coupon(): HasMany
    {
        return $this->hasMany(CustomerCoupon::class,'id_coupon');
    }

        protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
