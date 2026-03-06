<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\{Artisan, Schedule};

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run every day at midnight: flag overdue schedules, record penalties, auto-default loans
Schedule::command('loans:process-arrears')->dailyAt('00:00');

// Run every morning at 8am: send repayment due / overdue emails to clients
Schedule::command('loans:send-reminders')->dailyAt('08:00');

