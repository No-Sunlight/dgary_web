<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Health check endpoint - no middleware to bypass session issues
     */
    public function check(): JsonResponse
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            $dbStatus = 'connected';
        } catch (\Exception $e) {
            $dbStatus = 'failed: ' . $e->getMessage();
        }

        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'database' => $dbStatus,
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
        ]);
    }

    /**
     * Debug endpoint - shows actual database configuration being used
     */
    public function debug(): JsonResponse
    {
        return response()->json([
            'DB_CONNECTION' => config('database.default'),
            'DB_HOST' => config('database.connections.mysql.host'),
            'DB_PORT' => config('database.connections.mysql.port'),
            'DB_DATABASE' => config('database.connections.mysql.database'),
            'DB_USERNAME' => config('database.connections.mysql.username'),
            'DB_PASSWORD' => config('database.connections.mysql.password') ? '***' : 'empty',
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
        ]);
    }
}
