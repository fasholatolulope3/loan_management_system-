<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LoanSchedule extends Model
{
    protected $fillable = [
        'loan_id',
        'due_date',
        'principal_amount', // <--- REQUIRED HERE
        'interest_amount',  // <--- REQUIRED HERE
        'total_due',
        'status'
    ];

    protected $casts = [
        'due_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_due' => 'decimal:2',
    ];


    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Requirement #1: Dynamic 0.005 Penalty calculation logic
     */
    public function getAccruedPenaltyAttribute(): float
    {
        if ($this->status === 'paid' || now() <= $this->due_date) {
            return 0;
        }

        $daysLate = now()->diffInDays($this->due_date);
        return (float) $this->principal_amount * 0.005 * $daysLate;
    }

    public function getTotalDueWithPenaltyAttribute(): float
    {
        return (float) $this->total_due + $this->accrued_penalty;
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'schedule_id');
    }
}
