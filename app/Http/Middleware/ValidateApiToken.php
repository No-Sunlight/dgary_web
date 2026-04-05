<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Buscar el token en el header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token no proporcionado',
                'errors' => ['authorization' => ['Token de autenticación requerido']]
            ], 401);
        }

        // Validar que el token existe y es válido
        $apiToken = \App\Models\CustomerApiToken::findValidToken($token);

        if (!$apiToken) {
            return response()->json([
                'message' => 'Token inválido o expirado',
                'errors' => ['authorization' => ['Token inválido o expirado']]
            ], 401);
        }

        // Cargar el cliente en el request
        $request->setUserResolver(fn () => $apiToken->customer);
        $apiToken->update(['last_used_at' => now()]);

        return $next($request);
    }
}
