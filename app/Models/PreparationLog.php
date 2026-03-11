<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreparationLog extends Model
{
        protected $guarded = [];


              public function recipes(): BelongsTo
    {
        return $this->belongsTo(Recipe::class,'recipe_id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
