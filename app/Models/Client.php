<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'national_id',
        'bvn',
        'address',
        'income',
        'date_of_birth',
        'employment_status'
    ];

    /**
     * The attributes that should be cast.
     * 
     * This is the "Magic Fix" for the format() error.
     * It tells Laravel to convert the DB string into a Carbon Date Object.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'income' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function guarantors(): HasMany
    {
        return $this->hasMany(Guarantor::class);
    }
}
