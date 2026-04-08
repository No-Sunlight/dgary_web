<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\CouponController;

/**
 * API v1 - Rutas públicas (sin autenticación)
 */
Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    /**
     * Rutas protegidas (requieren token)
     */
    Route::middleware('api.token')->group(function () {
        // Autenticación
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

        // Catálogo (lectura)
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);

        // Órdenes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/active', [OrderController::class, 'active']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders/preview', [OrderController::class, 'preview']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

        // Entregas y seguimiento
        Route::get('/deliveries', [DeliveryController::class, 'index']);
        Route::get('/orders/{orderId}/delivery', [DeliveryController::class, 'show']);

        // Cupones
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::get('/coupons/{id}', [CouponController::class, 'show']);
        Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);
    });
});


