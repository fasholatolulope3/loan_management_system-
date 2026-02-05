<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany, BelongsTo};

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * White-list for mass assignment.
     * Includes collation_center_id to support multi-branch operations.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'collation_center_id'
    ];

    /**
     * Hidden attributes for JSON serialization.
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Database casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Business Logic Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Requirement: Blocking Gate for Onboarding.
     * Validates if a client has provided identity docs and a guarantor.
     */
    public function hasCompletedKyc(): bool
    {
        // Admin and Officers bypass KYC requirements
        if ($this->role !== 'client') {
            return true;
        }

        // Check relationship exists and data is valid
        if (!$this->client) {
            return false;
        }

        return !empty($this->client->national_id) &&
            $this->client->guarantors()->exists();
    }

    /**
     * Security Check: Prevent account removal if financial ties exist.
     */
    public function canBeDeleted(): bool
    {
        if ($this->role !== 'client') {
            return true;
        }

        if ($this->client) {
            // Cannot delete if they have any non-rejected or non-completed loan
            return !$this->client->loans()
                ->whereIn('status', ['pending', 'approved', 'active', 'defaulted'])
                ->exists();
        }

        return true;
    }

    /**
     * Helper to verify if the user is a system Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * REQUIREMENT #5: The specific branch/office this staff member belongs to.
     * Resolves the "RelationNotFoundException".
     */
    public function guarantor(): BelongsTo
    {
        return $this->belongsTo(Guarantor::class, 'guarantor_id');
    }
    public function collationCenter(): BelongsTo
    {
        return $this->belongsTo(CollationCenter::class, 'collation_center_id');
    }

    /**
     * The secondary KYC profile associated with the user account.
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * For Admin/Officers: Records of loans they personally approved.
     */
    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    /**
     * Forensic trace of all system actions performed by this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}