<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar cliente nuevo
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:10|regex:/^[0-9]{10}$/',
                'address' => 'nullable|string',
            ]);

            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? 'Sin dirección',
                'points' => 0,
            ]);

            $token = CustomerApiToken::generateToken($customer, 'mobile_app');

            return response()->json([
                'data' => [
                    'customer' => $customer,
                    'token' => $token->token,
                    'expires_at' => $token->expires_at,
                ],
                'message' => 'Cliente registrado exitosamente',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Login cliente
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $customer = Customer::where('email', $validated['email'])->first();

            if (!$customer || !Hash::check($validated['password'], $customer->password)) {
                return response()->json([
                    'message' => 'Credenciales inválidas',
                    'errors' => ['credentials' => ['Email o contraseña incorrectos']],
                ], 401);
            }

            $token = CustomerApiToken::generateToken($customer, 'mobile_app');

            return response()->json([
                'data' => [
                    'customer' => $customer,
                    'token' => $token->token,
                    'expires_at' => $token->expires_at,
                ],
                'message' => 'Sesión iniciada',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Obtener datos del cliente autenticado
     */
    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
            'message' => 'Datos del cliente',
        ], 200);
    }

    /**
     * Logout cliente
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            $apiToken = CustomerApiToken::where('token', $token)->first();
            if ($apiToken) {
                $apiToken->delete();
            }
        }

        return response()->json([
            'message' => 'Sesión cerrada',
        ], 200);
    }

    /**
     * Actualizar perfil del cliente
     */
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:10|regex:/^[0-9]{10}$/',
                'address' => 'sometimes|string',
                'password' => 'sometimes|string|min:8|confirmed',
            ]);

            $customer = $request->user();

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $customer->update($validated);

            return response()->json([
                'data' => $customer,
                'message' => 'Perfil actualizado',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
