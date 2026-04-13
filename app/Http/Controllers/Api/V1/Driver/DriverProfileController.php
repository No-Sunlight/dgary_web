<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Driver\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Driver\DriverProfileResource;
use Illuminate\Http\Request;

class DriverProfileController extends Controller
{
    /**
     * Obtener perfil del driver
     * 
     * @route GET /api/v1/driver/profile
     */
    public function show(Request $request)
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

            // Contar entregas completadas
            $driver->deliveries_count = $driver->deliveries()
                ->where('status', 'completed')
                ->count();

            return response()->json([
                'success' => true,
                'data' => new DriverProfileResource($driver),
                'message' => 'Perfil del driver',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al obtener perfil',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }

    /**
     * Actualizar perfil del driver
     * 
     * @route PUT /api/v1/driver/profile
     */
    public function update(UpdateProfileRequest $request)
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

            $validated = $request->validated();

            // Actualizar campos
            $driver->name = $validated['name'] ?? $driver->name;
            $driver->vehicle = $validated['vehicle'] ?? $driver->vehicle;
            $driver->license_plate = $validated['license_plate'] ?? $driver->license_plate;

            $driver->save();

            // Contar entregas completadas
            $driver->deliveries_count = $driver->deliveries()
                ->where('status', 'completed')
                ->count();

            return response()->json([
                'success' => true,
                'data' => new DriverProfileResource($driver),
                'message' => 'Perfil actualizado exitosamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Error al actualizar perfil',
                'errors' => ['server' => [$e->getMessage()]],
            ], 500);
        }
    }
}
