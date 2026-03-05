<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanReviewLog extends Model
{
    protected $fillable = [
        'loan_id',
        'user_id',
        'category',
        'priority',
        'comment',
        'is_addressed',
        'addressed_at',
    ];

    protected $casts = [
        'is_addressed' => 'boolean',
        'addressed_at' => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
