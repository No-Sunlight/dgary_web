<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
        protected $guarded = [];

        public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class,'order_id');
    }
}
