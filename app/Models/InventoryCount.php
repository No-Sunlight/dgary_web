<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCount extends Model
{
    protected $fillable = [
        'supply_id',
        'product_id',
        'stock_system',
        'stock_real',
        'difference'
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
