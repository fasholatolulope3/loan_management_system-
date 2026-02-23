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
    LoanProductController,
    CollationCenterController
};

/*
|--------------------------------------------------------------------------
| 1. Public Entrance
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| 2. Onboarding & Basic Profile (Accessible Pre-KYC)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::prefix('onboarding')->name('kyc.')->group(function () {
        Route::get('/complete-profile', [KycController::class, 'create'])->name('create');
        Route::post('/complete-profile', [KycController::class, 'store'])->name('store');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | 3. Core App - Staff & Admin Managed (Requirement #4 & #6)
    |----------------------------------------------------------------------
    */
    Route::middleware(['kyc.completed'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Shared Resources for Field Operations (Requirement #7)
        Route::resource('clients', ClientController::class);

        // Requirement #7: FORM CF4 Guarantor Assessment Registry
        Route::resource('guarantors', GuarantorController::class);

        // Requirement #7 & #8: Loan Pipeline (Proposal -> Review -> Approved)
        Route::resource('loans', LoanController::class);

        // Repayment Journal (Requirement #9)
        Route::resource('payments', PaymentController::class);
        Route::get('/loans/{loan}/print', [LoanController::class, 'print'])->name('loans.print');
        /**
         * REPORTING & COMPLIANCE (Requirement #9)
         */
        Route::prefix('reports')->name('reports.')->group(function () {
            // Search for Arrears & Print
            Route::get('/arrears', [LoanController::class, 'arrears'])->name('arrears');
            // Global Statistics
            Route::get('/global-summary', [DashboardController::class, 'reports'])->name('global');
        });

        /**
         * STAFF OPERATIONAL ACTIONS (Requirement #5 & #8)
         */
        Route::middleware(['role:admin,officer'])->group(function () {
            // Loan Management
            Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
            Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');

            // NEW: Authority verification of payment receipts
            Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        });

        /**
         * ADMIN ONLY ROUTES (Executive Infrastructure - Requirement #5)
         */
        Route::middleware(['role:admin'])->prefix('admin')->group(function () {

            // Collation Centers Registry (Mandatory Setup for Branch Credentials)
            Route::resource('centers', CollationCenterController::class)->names([
                'index' => 'admin.centers.index',
                'store' => 'admin.centers.store',
                'destroy' => 'admin.centers.destroy',
            ]);

            // User & Staff Permissions
            Route::resource('users', UserController::class);

            // Product Definition (Requirement #1, #2, #3)
            Route::resource('loan-products', LoanProductController::class);

            // Audit Trace & Configuration
            Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');

            // Back-and-forth Adjustment Loop (Requirement #8)
            Route::patch('/loans/{loan}/adjustment', [LoanController::class, 'requestAdjustment'])->name('loans.adjustment');
        });

    });
});

/*
|--------------------------------------------------------------------------
| Authentication (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| 4. Hosting Helpers (Shared Hosting / InfinityFree)
|--------------------------------------------------------------------------
*/
Route::get('/deploy/migrate', function () {
    if (request('key') !== env('APP_KEY')) {
        abort(403);
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Migration successful: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return "Migration failed: " . $e->getMessage();
    }
});