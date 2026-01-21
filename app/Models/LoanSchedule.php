<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSchedule extends Model
{
    protected $fillable = ['loan_id', 'due_date', 'principal_amount', 'interest_amount', 'total_due', 'status'];
    protected $casts = ['due_date' => 'date'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'schedule_id');
    }
}
