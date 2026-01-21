<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanProduct extends Model
{
    protected $fillable = [
        'name',
        'interest_rate',
        'penalty_rate',
        'min_amount',
        'max_amount',
        'duration_months',
        'status'
    ];
    protected $casts = [
        'interest_rate' => 'decimal:2',
        'penalty_rate' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
