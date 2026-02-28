<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
        protected $guarded = [];

        public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

        public function details(): HasMany
    {
        return $this->hasMany(PurchaseSupply::class,'purchase_id');
    }
}
