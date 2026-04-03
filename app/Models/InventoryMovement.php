<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
     protected $fillable = [
        'type',
        'inventory_id',
        'product_id',
        'quantity',
        'direction',
        'reason'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventorie::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
