<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/RolesSeeder.php
    public function run(): void
    {
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@loan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Loan Officer',
            'email' => 'officer@loan.com',
            'password' => Hash::make('password'),
            'role' => 'officer',
        ]);

        $clientUser = User::create([
            'name' => 'John Client',
            'email' => 'client@loan.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Link to client profile
        \App\Models\Client::create([
            'user_id' => $clientUser->id,
            'national_id' => 'ID12345',
            'income' => 50000,
            'address' => '123 Loan St',
            'employment_status' => 'Employed',
            'date_of_birth' => '1990-01-01'
        ]);
    }
}
