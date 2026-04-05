<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerApiToken extends Model
{
    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Generar un token único para el cliente
     */
    public static function generateToken(Customer $customer, string $name = 'mobile'): self
    {
        return self::create([
            'customer_id' => $customer->id,
            'token' => hash('sha256', bin2hex(random_bytes(32))),
            'name' => $name,
            'abilities' => '["*"]',
        ]);
    }

    /**
     * Encontrar token y cliente válido
     */
    public static function findValidToken(string $token): ?self
    {
        return self::where('token', $token)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }
}
