<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LoanProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Default Admin Account
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@lms.com',
            'phone' => '09011112222',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create the Specific Loan Products per your requirements
        // We add min_amount and max_amount to satisfy the database constraint

        LoanProduct::create([
            'name' => 'Daily',
            'interest_rate' => 10.00,
            'penalty_rate' => 0.005, // 0.5% as per your requirement
            'min_amount' => 5000,    // Reasonable minimum
            'max_amount' => 100000,  // Reasonable maximum
            'duration_months' => 1,  // Duration context
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Weekly',
            'interest_rate' => 20.00,
            'penalty_rate' => 0.005,
            'min_amount' => 10000,
            'max_amount' => 500000,
            'duration_months' => 3,
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Monthly',
            'interest_rate' => 30.00,
            'penalty_rate' => 0.005,
            'min_amount' => 20000,
            'max_amount' => 2000000,
            'duration_months' => 12,
            'status' => 'active'
        ]);
    }
}