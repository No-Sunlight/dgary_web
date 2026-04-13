<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{


    protected $guarded = [];
    use SoftDeletes;

    protected $fillable = [
    'name',
    'image',
    'category_id',
    'price',
    'stock',
    'points',
    'type',
    'expire',
    'status',
];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

     public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
