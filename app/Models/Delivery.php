<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
     protected $guarded = [];
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    
   public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

      public function driver(): BelongsTo //No cambiar el nombre
    {
        return $this->belongsTo(User::class,'user_id');
    }


    public function in_transit($query)
{
    return $query->where('status', 'in_transit');
}


}
