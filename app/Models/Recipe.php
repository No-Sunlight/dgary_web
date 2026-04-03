<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//use Illuminate\Database\Eloquent\Relations\HasOne;

class Recipe extends Model
{
        protected $guarded = [];

    
   public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }

       public function details(): HasMany
    {
        return $this->hasMany(RecipeSupply::class,'recipe_id');
    }
}
