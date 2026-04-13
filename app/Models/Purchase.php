<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'total',
    ];

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseSupply::class, 'purchase_id');
    }

    protected static function booted()
    {
        static::creating(function ($purchase) {
            $purchase->user_id = Auth::id();
        });
    }
}
