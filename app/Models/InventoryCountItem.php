<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCountItem extends Model
{

    protected $fillable = [
        'inventory_count_id',
        'product_id',
        'supply_id',
        'stock_system',
        'stock_real',
        'difference',
    ];

    public function inventoryCount()
    {
        return $this->belongsTo(\App\Models\InventoryCount::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    public function supply()
    {
        return $this->belongsTo(\App\Models\Supply::class);
    }
}
