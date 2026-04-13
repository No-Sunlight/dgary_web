<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeSupply extends Model
{
        protected $fillable = ['recipe_id', 'supply_id', 'amount'];

        protected $guarded = [];

        public function supply()
        {
                return $this->belongsTo(Supply::class);
        }
}
