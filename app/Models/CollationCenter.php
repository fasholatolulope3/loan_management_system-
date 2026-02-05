<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollationCenter extends Model
{
    /**
     * Requirement #5: Branch credentials and access control.
     */
    protected $fillable = [
        'name', 
        'center_code', 
        'address'
    ];

    /* -------------------------------------------------------------------------- */
    /*                                RELATIONSHIPS                                */
    /* -------------------------------------------------------------------------- */

    /**
     * A center is comprised of many staff members (Admins and Officers).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Requirement #5: A center tracks and collates all loan proposals
     * initiated within its branch.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /* -------------------------------------------------------------------------- */
    /*                             BUSINESS HELPERS                               */
    /* -------------------------------------------------------------------------- */

    /**
     * Helper to show a summarized identity in the UI.
     */
    public function getFullIdentityAttribute()
    {
        return "[{$this->center_code}] {$this->name}";
    }
}