<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
        protected $guarded = [];

        protected $fillable = [
                'name',
                'description',
                'stock',
                'stock_type',
        ];

        public function purchaseSupplies()
        {
                return $this->hasMany(\App\Models\PurchaseSupply::class, 'supplies_id');
        }
}
