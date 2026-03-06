<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\LoanApiController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\LoanStatementController;
use App\Http\Controllers\Api\CreditScoreController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\MiscApiController;

// --- Public Routes ---
Route::post('/login', [ApiAuthController::class, 'login'])->middleware('throttle:6,1');
Route::get('/loan-products', [MiscApiController::class, 'loanProducts']);

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
    Route::get('/loans/{loan}/statement', [LoanStatementController::class, 'download']);

    // Payments
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments', [PaymentApiController::class, 'store']);
    Route::post('/payments/{payment}/verify', [PaymentApiController::class, 'verify']);

    // Clients
    Route::get('/clients', [ClientApiController::class, 'index']);
    Route::get('/clients/{client}', [ClientApiController::class, 'show']);
    Route::get('/clients/{client}/credit-score', [CreditScoreController::class, 'show']);

    // Exports (CSV)
    Route::get('/loans/export', [ExportController::class, 'loans']);
    Route::get('/payments/export', [ExportController::class, 'payments']);

    // Admin
    Route::get('/audit-logs', [MiscApiController::class, 'auditLogs']);
});



