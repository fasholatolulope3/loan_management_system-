<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanProduct extends Model
{
    /**
     * Requirement Fix: Mass assignment protection for Product Configuration.
     * These are the "Rules" that the Approved Authority (Admin) sets.
     */
    protected $fillable = [
        'name',             // Daily, Weekly, Monthly
        'interest_rate',    // 10, 20, or 30
        'penalty_rate',     // 0.005 (Requirement #1)
        'min_amount',
        'max_amount',
        'duration_months',  // The number of installments (Daily/Weekly/Monthly)
        'status'            // active, inactive
    ];

    /**
     * Requirement #1: Precision for the 0.005 rate.
     */
    protected $casts = [
        'interest_rate' => 'decimal:2',
        'penalty_rate' => 'decimal:4', // We use 4 decimal places to handle 0.005 correctly
        'max_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONSHIPS                                */
    /* -------------------------------------------------------------------------- */

    /**
     * One product (e.g. 'Daily') can be assigned to many individual loan applications.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}