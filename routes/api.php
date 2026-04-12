<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\WebhookController;
use App\Http\Controllers\Api\V1\Driver\DriverAuthController;
use App\Http\Controllers\Api\V1\Driver\DriverDeliveryController;
use App\Http\Controllers\Api\V1\Driver\DriverProfileController;

/**
 * API v1 - Rutas públicas (sin autenticación)
 */
Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Webhooks (sin autenticación - validados por firma Stripe)
    Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripeWebhook']);

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
        Route::get('/orders/{orderId}/tracking', [DeliveryController::class, 'tracking']);

        // Cupones
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::get('/coupons/{id}', [CouponController::class, 'show']);
        Route::post('/validate', [CouponController::class, 'validateCoupon']);
        Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);

        // Pagos
        Route::post('/orders/{orderId}/payment/intent', [PaymentController::class, 'createPaymentIntent']);
        Route::post('/orders/{orderId}/payment/confirm', [PaymentController::class, 'confirmPayment']);
        Route::post('/orders/{orderId}/payment/use-saved', [PaymentController::class, 'payWithSavedCard']);
        Route::get('/orders/{orderId}/payment/status', [PaymentController::class, 'getPaymentStatus']);
        Route::post('/orders/{orderId}/payment/refund', [PaymentController::class, 'refundPayment']);
        
        // Métodos de pago guardados (billetera)
        Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods']);
        Route::post('/payment-methods/cleanup', [PaymentController::class, 'cleanupLegacyPaymentMethods']);
    });
});

/**
 * API v1 - Rutas para APP DRIVER/REPARTIDOR
 * Separadas de las rutas de cliente para diferenciación clara
 */
Route::prefix('v1/driver')->group(function () {
    /**
     * Autenticación del driver (sin protección)
     */
    Route::post('/auth/login', [DriverAuthController::class, 'login']);

    /**
     * Rutas protegidas para drivers (requieren token de driver)
     */
    Route::middleware('api.driver.token')->group(function () {
        // Autenticación
        Route::post('/auth/logout', [DriverAuthController::class, 'logout']);
        Route::get('/auth/me', [DriverAuthController::class, 'me']);

        // Entregas (Pedidos activos y realizados)
        Route::get('/deliveries', [DriverDeliveryController::class, 'index']);
        Route::get('/deliveries/completed', [DriverDeliveryController::class, 'completed']);
        Route::get('/deliveries/{id}', [DriverDeliveryController::class, 'show']);
        Route::put('/deliveries/{id}/status', [DriverDeliveryController::class, 'updateStatus']);
        Route::put('/deliveries/{id}/location', [DriverDeliveryController::class, 'updateLocation']);
        Route::get('/deliveries/stats', [DriverDeliveryController::class, 'stats']);

        // Perfil del driver
        Route::get('/profile', [DriverProfileController::class, 'show']);
        Route::put('/profile', [DriverProfileController::class, 'update']);
    });
});


