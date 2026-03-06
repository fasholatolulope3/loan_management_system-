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
use App\Http\Controllers\Api\KycApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\ReportsApiController;
use App\Http\Controllers\Api\GuarantorApiController;
use App\Http\Controllers\Api\LoanProductApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\CollationCenterApiController;

// --- Public Routes ---
Route::post('/login', [ApiAuthController::class, 'login'])->middleware('throttle:6,1');
Route::get('/loan-products', [MiscApiController::class, 'loanProducts']);

// --- Protected Routes (Bearer token required) ---
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::patch('/profile', [ProfileApiController::class, 'update']);
    Route::delete('/profile', [ProfileApiController::class, 'destroy']);

    // KYC / Onboarding
    Route::get('/onboarding/kyc/status', [KycApiController::class, 'status']);
    Route::post('/onboarding/kyc', [KycApiController::class, 'store']);

    // Dashboard & Reports
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/reports/collections', [ReportsApiController::class, 'collections']);
    Route::get('/reports/arrears', [ReportsApiController::class, 'arrears']);
    Route::get('/reports/global', [ReportsApiController::class, 'global']);

    // Loans
    Route::get('/loans', [LoanApiController::class, 'index']);
    Route::post('/loans', [LoanApiController::class, 'store']);
    Route::get('/loans/{loan}', [LoanApiController::class, 'show']);
    Route::patch('/loans/{loan}', [LoanApiController::class, 'update']);
    Route::delete('/loans/{loan}', [LoanApiController::class, 'destroy']);
    Route::post('/loans/{loan}/approve', [LoanApiController::class, 'approve']);
    Route::post('/loans/{loan}/reject', [LoanApiController::class, 'reject']);
    Route::post('/loans/{loan}/adjustment', [LoanApiController::class, 'requestAdjustment']);
    Route::get('/loans/{loan}/statement', [LoanStatementController::class, 'download']);

    // Payments
    Route::get('/payments', [PaymentApiController::class, 'index']);
    Route::post('/payments', [PaymentApiController::class, 'store']);
    Route::post('/payments/{payment}/verify', [PaymentApiController::class, 'verify']);
    Route::post('/schedules/{schedule}/pay', [PaymentApiController::class, 'markPaid']);

    // Clients
    Route::get('/clients', [ClientApiController::class, 'index']);
    Route::post('/clients', [ClientApiController::class, 'store']);
    Route::get('/clients/{client}', [ClientApiController::class, 'show']);
    Route::patch('/clients/{client}', [ClientApiController::class, 'update']);
    Route::delete('/clients/{client}', [ClientApiController::class, 'destroy']);
    Route::get('/clients/{client}/credit-score', [CreditScoreController::class, 'show']);

    // Guarantors
    Route::get('/guarantors', [GuarantorApiController::class, 'index']);
    Route::post('/guarantors', [GuarantorApiController::class, 'store']);
    Route::get('/guarantors/{guarantor}', [GuarantorApiController::class, 'show']);
    Route::patch('/guarantors/{guarantor}', [GuarantorApiController::class, 'update']);
    Route::delete('/guarantors/{guarantor}', [GuarantorApiController::class, 'destroy']);

    // Exports (CSV)
    Route::get('/loans/export', [ExportController::class, 'loans']);
    Route::get('/payments/export', [ExportController::class, 'payments']);

    // Admin Area
    Route::prefix('admin')->group(function () {
        // Loan Products
        Route::post('/loan-products', [LoanProductApiController::class, 'store']);
        Route::put('/loan-products/{loan_product}', [LoanProductApiController::class, 'update']);
        Route::delete('/loan-products/{loan_product}', [LoanProductApiController::class, 'destroy']);

        // Users / Staff
        Route::get('/users', [UserApiController::class, 'index']);
        Route::post('/users', [UserApiController::class, 'store']);
        Route::get('/users/{user}', [UserApiController::class, 'show']);
        Route::patch('/users/{user}', [UserApiController::class, 'update']);
        Route::delete('/users/{user}', [UserApiController::class, 'destroy']);

        // Collation Centers
        Route::get('/centers', [CollationCenterApiController::class, 'index']);
        Route::post('/centers', [CollationCenterApiController::class, 'store']);
        Route::delete('/centers/{center}', [CollationCenterApiController::class, 'destroy']);
    });

    Route::get('/audit-logs', [MiscApiController::class, 'auditLogs']);
});



