<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Driver\DriverLoginRequest;
use App\Models\User;
use App\Models\DriverApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\V1\Driver\DriverProfileResource;

class DriverAuthController extends Controller
{
    /**
     * Login para drivers/repartidores
     * 
     * @route POST /api/v1/driver/auth/login
     */
    public function login(DriverLoginRequest $request)
    {
        try {
            $validated = $request->validated();

            $driver = User::where('email', $validated['email'])
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Repartidor');
                })
                ->first();

            if (!$driver || !Hash::check($validated['password'], $driver->password)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Credenciales inválidas',
                    'errors' => ['credentials' => ['Email o contraseña incorrectos']],
                ], 401);
            }

            // Generar token para driver
            $token = DriverApiToken::generateToken($driver, 'driver_app');

            return response()->json([
                'success' => true,
                'data' => [
                    'driver' => new DriverProfileResource($driver),
                    'token' => $token->token,
                    'expires_at' => $token->expires_at,
                ],
                'message' => 'Sesión iniciada exitosamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al iniciar sesión',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Logout para drivers
     * 
     * @route POST /api/v1/driver/auth/logout
     */
    public function logout(Request $request)
    {
        try {
            $driver = $request->user();
            
            // Invalidar token actual
            DriverApiToken::where('user_id', $driver->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Obtener datos del driver autenticado
     * 
     * @route GET /api/v1/driver/auth/me
     */
    public function me(Request $request)
    {
        try {
            $driver = $request->user();

            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'No autenticado',
                ], 401);
            }

            $driver->deliveries_count = $driver->deliveries()
                ->where('status', 'completed')
                ->count();

            return response()->json([
                'success' => true,
                'data' => new DriverProfileResource($driver),
                'message' => 'Datos del driver',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener datos',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }
}
