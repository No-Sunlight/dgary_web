<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StockCount extends Model
{
    //Me dio flojera crear una clase enum solo para este modelo. Supuestamente es una buena practica
    //definirlo con variables
    public const STATUS_UNCOUNTED = 'uncounted';
    public const STATUS_MATCHED = 'matched';
    public const STATUS_DISCREPANCY = 'discrepancy';
    public const STATUS_STALE = 'stale';


    
    protected $fillable = ['product_id', 'count', 'last_counted_at'];
    protected $casts = [
        'last_counted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (! $this->last_counted_at) {
                    return self::STATUS_UNCOUNTED;
                }

                if ($this->product && $this->product->updated_at > $this->last_counted_at) {
                    return self::STATUS_STALE;
                }

                if ($this->product && $this->product->stock !== $this->count) {
                    return self::STATUS_DISCREPANCY;
                }

                    return self::STATUS_MATCHED;
            }
        );
    }

}
