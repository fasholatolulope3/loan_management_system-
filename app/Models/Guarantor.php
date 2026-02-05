<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Guarantor extends Model
{
    /**
     * Mass Assignment Protection
     * Updated to include Assessment Form CF4 requirements (Employment & Business info)
     */
    protected $fillable = [
        'client_id',
        'name',
        'phone',
        'relationship',
        'address',
        
        // Identity Additions (Requirement #7 / CF4 Page 1)
        'marital_status',
        'date_of_birth',
        'type', // e.g., 'Business Owner', 'Employee', 'With Collateral'
        
        // Employment & Income Data (Requirement #7 / CF4 Section IV)
        'employer_name',
        'job_sector',
        'position',
        'net_monthly_income',
        
        // Financial Analysis Table (Requirement #7 / CF4 Section III)
        // This JSON field stores the 'Cash Flow' and 'Balance Sheet' recap of the guarantor
        'business_financials' 
    ];

    /**
     * Precision Casting for Financial Compliance
     */
    protected $casts = [
        'date_of_birth'      => 'date',
        'net_monthly_income' => 'decimal:2',
        'business_financials' => 'array', // Crucial: This handles the repeating table data as JSON
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONSHIPS                                */
    /* -------------------------------------------------------------------------- */

    /**
     * Access the client this guarantor is primarily registered under.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Links back to specific loan applications (proposals) that this person is guaranteeing.
     * This fulfills Requirement #7: Verifying a specific proposal via a specific assessment.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'guarantor_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                             BUSINESS HELPERS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Requirement Helper for presentation:
     * Check if the guarantor has a "Verified" status based on income thresholds.
     */
    public function hasCapacityFor(float $installmentAmount): bool
    {
        // Debt-Service Ratio (DSR) rule: Installment should not exceed 40% of Guarantor income
        return ($this->net_monthly_income * 0.40) >= $installmentAmount;
    }
}