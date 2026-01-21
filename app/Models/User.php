<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\{HasOne, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'status'];
    protected $hidden = ['password', 'remember_token'];

    public function hasCompletedKyc(): bool
    {
        // If user is admin/officer, they don't need KYC
        if ($this->role !== 'client') {
            return true;
        }

        // Load the relation
        $this->loadMissing('client.guarantors');

        // A client is complete if:
        // 1. They have a client record
        // 2. They have a National ID
        // 3. They have at least one guarantor
        return $this->client &&
            $this->client->national_id &&
            $this->client->guarantors->count() > 0;
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function approvedLoans(): HasMany
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
