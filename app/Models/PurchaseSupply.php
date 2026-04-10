<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseSupply extends Model
{
       protected $guarded = [];

       public function supply()
       {
              return $this->belongsTo(Supply::class, 'supplies_id');
       }

}
