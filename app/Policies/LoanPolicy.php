<?php

// app/Policies/LoanPolicy.php
namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    // Admin & Officer can view all, Client only their own
    public function view(User $user, Loan $loan): bool
    {
        if (in_array($user->role, ['admin', 'officer'])) return true;
        return $user->id === $loan->client->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client'; // Only clients apply for loans
    }

    public function update(User $user, Loan $loan): bool
    {
        return in_array($user->role, ['admin', 'officer']); // Only staff approve/edit
    }

    public function delete(User $user, Loan $loan): bool
    {
        return $user->role === 'admin';
    }
}
