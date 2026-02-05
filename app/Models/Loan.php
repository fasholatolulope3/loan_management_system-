<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 1. Organizational & Basic Data
        'collation_center_id',
        'client_id',
        'loan_product_id',
        'guarantor_id',         // MISSING FIX: Required to link the guarantor to this specific proposal
        'amount',
        'interest_rate',
        'duration_months',
        'start_date',
        'end_date',
        'status',

        // 2. Information About Applicant (Form Part I)
        'residence_since',
        'dependent_count',
        'home_ownership',
        'next_rent_amount',
        'next_rent_date',

        // 3. Information About Business (Form Part II)
        'business_name',
        'business_location',
        'business_start_date',
        'business_premise_type', // SYNC FIX: Matches migration name for own/rent
        'point_of_sale_count',
        'has_co_owners',
        'employee_count',

        // 4. Financial Summary (From Assessment Tables)
        'monthly_sales',
        'cost_of_sales',
        'gross_profit',
        'operational_expenses',
        'other_net_income',
        'family_expenses',
        'payment_capacity',
        'applied_margin',

        // SYNC FIX: Removed '_value' suffix to match the names used in the Blade Form & Controller
        'current_assets',
        'fixed_assets',
        'total_liabilities',
        'equity_value',

        // 5. Dynamic Form Data (JSON arrays)
        'daily_sales_logs',
        'purchase_history',
        'inventory_details',
        'risk_mitigation',
        'business_references',

        // 6. Guarantor Details (CF4 Snapshot)
        // Note: Most of these live on the Guarantor model, but including these here 
        // allows a "Snapshot" of the guarantor's status at the moment of application.
        'guarantor_type',
        'guarantor_business_financials',
        'guarantor_employment_details',

        // 7. Review & Adjustment (Requirement #8)
        'approval_status',
        'review_notes',
        'approved_by'
    ];

    protected $casts = [
        // 1. Core Timeline
        'start_date' => 'date',
        'end_date' => 'date',
        'business_start_date' => 'date',
        'next_rent_date' => 'date',

        // 2. Financial Metrics (Precise Calculations for Req #7)
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_sales' => 'decimal:2',
        'cost_of_sales' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'operational_expenses' => 'decimal:2',
        'other_net_income' => 'decimal:2',
        'family_expenses' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'payment_capacity' => 'decimal:2',
        'applied_margin' => 'decimal:2',
        'next_rent_amount' => 'decimal:2',

        // 3. Balance Sheet (Sync Fix: Removed '_value' to match Form Names)
        'current_assets' => 'decimal:2',
        'fixed_assets' => 'decimal:2',
        'total_liabilities' => 'decimal:2',
        'equity_value' => 'decimal:2',

        // 4. Requirement #7: Assessment Form Tables (JSON Casting)
        'daily_sales_logs' => 'array',       // PDF CF2 Page 2: 3-Day sales history
        'purchase_history' => 'array',      // PDF CF2 Page 2: Past 3 purchases
        'inventory_details' => 'array',     // PDF CF2 Page 2: Inventory breakdown
        'risk_mitigation' => 'array',       // PDF CF2 Page 1: Risk Table
        'business_references' => 'array',   // PDF CF2 Page 2: Reference checks

        // 5. Requirement #7: Guarantor Analysis (JSON Casting)
        'guarantor_business_financials' => 'array', // PDF CF4 Section III
        'guarantor_employment_details' => 'array',  // PDF CF4 Section IV
    ];

    /*
    |--------------------------------------------------------------------------
    | Financial Business Logic Helpers
    |--------------------------------------------------------------------------
    */
    public function totalArrearsAmount()
    {
        return $this->schedules()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->sum('total_due');
    }
    /**
     * Requirement #1 & #9: Calculate Total Penalty Accrued
     * Logic: Overdue installments * 0.005 per day.
     */
    public function currentPenaltyAccrued()
    {
        $overdueSchedules = $this->schedules()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->get();

        $totalPenalty = 0;
        foreach ($overdueSchedules as $schedule) {
            $daysPast = now()->diffInDays($schedule->due_date);
            $totalPenalty += ($schedule->principal_amount * 0.005 * $daysPast);
        }
        return $totalPenalty;
    }


    /**
     * Requirement #7: Assessment Helper
     * Calculated Liquidation Value of all Collaterals (Sum of 50-80% value)
     */
    public function totalCollateralValue()
    {
        return $this->collaterals()->sum('liquidation_value');
    }

    public function remainingBalance()
    {
        return $this->schedules()->where('status', 'pending')->sum('total_due');
    }

    public function nextInstallment()
    {
        return $this->schedules()
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(Guarantor::class, 'guarantor_id');
    }

    /**
     * Link to the Collation Center (Requirement #5)
     */
    public function collationCenter(): BelongsTo
    {
        return $this->belongsTo(CollationCenter::class, 'collation_center_id');
    }

    /**
     * Link to the Borrower Profile
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Link to the Product Template (Requirement #2)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    /**
     * Link to multiple Collateral Evaluation Items (Form CF5)
     */


    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(LoanSchedule::class)->orderBy('due_date', 'asc');
    }

    public function collaterals(): HasMany
    {
        return $this->hasMany(Collateral::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}