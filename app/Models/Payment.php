<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    protected $fillable = [
        'loan_id',
        'schedule_id',
        'type',                 // Critical for distinguishing In/Out
        'amount_paid',
        'payment_date',
        'method',
        'reference',
        'receipt_path',         // Needed for file uploads
        'verification_status',  // Needed for approval flow
        'captured_by',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(LoanSchedule::class, 'schedule_id');
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by');
    }

    /* -----------------------------------------------------------------
     |  Accessors (The Logic for Dashboard Display)
     | -----------------------------------------------------------------
     */

    /**
     * Virtual Attribute: $payment->viewer_status
     * usage: {{ $payment->viewer_status['label'] }}
     */
    public function getViewerStatusAttribute()
    {
        $user = Auth::user();

        // Safety check: If no user is logged in
        if (!$user) {
            return ['label' => 'Unknown', 'color' => 'text-gray-500'];
        }

        $isClient = $user->role === 'client';

        // 1. DISBURSEMENT (Money leaving the Company -> to Client)
        if ($this->type === 'disbursement') {
            if ($isClient) {
                // Client sees: Money Received
                return ['label' => 'RECEIVED', 'color' => 'text-green-600'];
            } else {
                // Admin sees: Money Disbursed (Paid Out)
                return ['label' => 'DISBURSED', 'color' => 'text-red-600'];
            }
        }

        // 2. REPAYMENT (Money leaving Client -> to Company)
        elseif ($this->type === 'repayment') {
            if ($isClient) {
                // Client sees: Money Paid
                return ['label' => 'PAID', 'color' => 'text-red-600'];
            } else {
                // Admin sees: Money Received
                return ['label' => 'RECEIVED', 'color' => 'text-green-600'];
            }
        }

        // Fallback
        return [
            'label' => ucfirst($this->type ?? 'Transaction'),
            'color' => 'text-gray-500'
        ];
    }
}
