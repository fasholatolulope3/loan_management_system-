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
        // 1. Create Collation Centers (Requirement #5)
        $center = \App\Models\CollationCenter::create([
            'name' => 'Main Collation Center',
            'center_code' => 'HQ-001',
            'address' => 'No 1, Empowerment Way, PDEI City',
        ]);

        // 2. Create the Default Admin Account (Requirement #6)
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@pdei.com',
            'phone' => '09011112222',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'collation_center_id' => $center->id,
        ]);

        // 3. Create a Staff Account (Requirement #6)
        User::create([
            'name' => 'Field Officer',
            'email' => 'staff@pdei.com',
            'phone' => '09033334444',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'collation_center_id' => $center->id,
        ]);

        // 4. Create the Specific Loan Products per your requirements (Refined Req)

        // DAILY
        LoanProduct::create([
            'name' => 'Daily - 20 Days',
            'interest_rate' => 10.00,
            'penalty_rate' => 0.005,
            'min_amount' => 5000,
            'max_amount' => 100000,
            'duration_months' => 20, // 20 working days
            'status' => 'active'
        ]);

        // WEEKLY TIERS
        LoanProduct::create([
            'name' => 'Weekly - 4 Weeks',
            'interest_rate' => 10.00,
            'penalty_rate' => 0.005,
            'min_amount' => 10000,
            'max_amount' => 500000,
            'duration_months' => 4,
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Weekly - 8 Weeks',
            'interest_rate' => 20.00,
            'penalty_rate' => 0.005,
            'min_amount' => 10000,
            'max_amount' => 500000,
            'duration_months' => 8,
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Weekly - 12 Weeks',
            'interest_rate' => 30.00,
            'penalty_rate' => 0.005,
            'min_amount' => 10000,
            'max_amount' => 500000,
            'duration_months' => 12,
            'status' => 'active'
        ]);

        // MONTHLY TIERS
        LoanProduct::create([
            'name' => 'Monthly - 1 Month',
            'interest_rate' => 10.00,
            'penalty_rate' => 0.005,
            'min_amount' => 20000,
            'max_amount' => 2000000,
            'duration_months' => 1,
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Monthly - 2 Months',
            'interest_rate' => 20.00,
            'penalty_rate' => 0.005,
            'min_amount' => 20000,
            'max_amount' => 2000000,
            'duration_months' => 2,
            'status' => 'active'
        ]);

        LoanProduct::create([
            'name' => 'Monthly - 3 Months',
            'interest_rate' => 30.00,
            'penalty_rate' => 0.005,
            'min_amount' => 20000,
            'max_amount' => 2000000,
            'duration_months' => 3,
            'status' => 'active'
        ]);

        // EMPLOYEE LOANS
        LoanProduct::create([
            'name' => 'Employee Loan',
            'interest_rate' => 5.00,
            'penalty_rate' => 0.005,
            'min_amount' => 50000,
            'max_amount' => 5000000,
            'duration_months' => 12,
            'status' => 'active'
        ]);
    }
}