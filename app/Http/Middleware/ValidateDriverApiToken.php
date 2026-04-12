<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDriverApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado',
                'errors' => ['authorization' => ['Token de autenticación requerido']],
            ], 401);
        }

        $apiToken = \App\Models\DriverApiToken::findValidToken($token);

        if (!$apiToken) {
            return response()->json([
                'message' => 'Token de repartidor inválido o expirado',
                'errors' => ['authorization' => ['Token inválido o expirado']],
            ], 401);
        }

        $request->setUserResolver(fn () => $apiToken->driver);
        $apiToken->update(['last_used_at' => now()]);

        return $next($request);
    }
}
