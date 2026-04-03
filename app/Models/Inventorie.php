<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventorie extends Model
{
    protected $fillable = ['name', 'unit', 'stock_actual'];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }
}
