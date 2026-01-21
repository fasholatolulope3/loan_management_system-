<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
    User::create([
            'name' => 'System Admin',
            'email' => 'admin@loan.com',
            'phone' => '09000000001',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Officer
        User::create([
            'name' => 'Loan Officer',
            'email' => 'officer@loan.com',
            'phone' => '09000000002',
            'password' => bcrypt('password'),
            'role' => 'officer',
        ]);
    }
}
