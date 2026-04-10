<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    protected $fillable = ['name', 'price', 'type'];

    protected $guarded = [];
    use SoftDeletes;

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
