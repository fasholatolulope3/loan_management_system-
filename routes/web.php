<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KycController,
    LoanController,
    UserController,
    ClientController,
    PaymentController,
    ProfileController,
    AuditLogController,
    DashboardController,
    GuarantorController,
    LoanProductController
};

/*
|--------------------------------------------------------------------------
| 1. Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| 2. Authenticated Routes (The "Waiting Room")
|--------------------------------------------------------------------------
| These routes are accessible as soon as a user logs in.
| We DO NOT apply 'kyc.completed' here so they can actually finish onboarding.
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // KYC / Onboarding Flow
    Route::prefix('onboarding')->name('kyc.')->group(function () {
        Route::get('/complete-profile', [KycController::class, 'create'])->name('create');
        Route::post('/complete-profile', [KycController::class, 'store'])->name('store');
    });

    // Profile Management (Basic info accessible anytime)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | 3. KYC Protected Routes (The "Core App")
    |----------------------------------------------------------------------
    | Only users who have finished KYC (or are staff) can enter here.
    */
    Route::middleware(['kyc.completed'])->group(function () {

        // Unified Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /**
         * ADMIN ONLY
         */
        Route::middleware(['role:admin'])->prefix('admin')->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('loan-products', LoanProductController::class);
            Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
            Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
        });

        /**
         * STAFF (Admin & Officer)
         */
        Route::middleware(['role:admin,officer'])->group(function () {
            Route::resource('clients', ClientController::class);

            // Loan Processing
            Route::get('/loans/pending', [LoanController::class, 'pending'])->name('loans.pending');
            Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
            Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');

            // Payments
            Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);
        });

        /**
         * CLIENT ONLY (KYC already verified)
         */
        Route::middleware(['role:client'])->group(function () {
            Route::get('/loans/apply', [LoanController::class, 'create'])->name('loans.create');
            Route::post('/loans/apply', [LoanController::class, 'store'])->name('loans.store');
            Route::get('/repayment-schedule', [LoanController::class, 'schedules'])->name('schedules');
            Route::resource('guarantors', GuarantorController::class);
        });

        /**
         * SHARED RESOURCES
         */
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    });
});

/*
|--------------------------------------------------------------------------
| 4. Auth Scaffolding (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
