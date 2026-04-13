<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverApiToken extends Model
{
    protected $guarded = [];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generar un token único para el repartidor
     */
    public static function generateToken(User $driver, string $name = 'driver_app'): self
    {
        return self::create([
            'user_id' => $driver->id,
            'token' => hash('sha256', bin2hex(random_bytes(32))),
            'name' => $name,
            'abilities' => '["*"]',
        ]);
    }

    /**
     * Encontrar token válido
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
