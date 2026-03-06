<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\LoanApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\DashboardApiController;

// --- Public Routes ---
Route::post('/login', [ApiAuthController::class, 'login'])->middleware('throttle:6,1');

// --- Protected Routes (Bearer token required) ---
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardApiController::class, 'index']);

    // Loans
    Route::get('/loans', [LoanApiController::class, 'index']);
    Route::post('/loans', [LoanApiController::class, 'store']);
    Route::get('/loans/{loan}', [LoanApiController::class, 'show']);
    Route::post('/loans/{loan}/approve', [LoanApiController::class, 'approve']);

    // Payments
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments', [PaymentApiController::class, 'store']);
    Route::post('/payments/{payment}/verify', [PaymentApiController::class, 'verify']);

    // Clients
    Route::get('/clients', [ClientApiController::class, 'index']);
    Route::get('/clients/{client}', [ClientApiController::class, 'show']);
});


