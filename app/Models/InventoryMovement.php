<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
     protected $fillable = [
        'type',
        'supply_id',
        'product_id',
        'production_id',
        'quantity',
        'direction',
        'reason'
    ];

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
