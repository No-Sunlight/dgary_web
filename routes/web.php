<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;

// Health check - no middleware, for diagnostics
Route::get('/health', [HealthController::class, 'check'])->withoutMiddleware('web');
Route::get('/debug', [HealthController::class, 'debug'])->withoutMiddleware('web');

Route::get('/', function () {
    return view('welcome');
})->name('home');
