<?php

namespace App\Console\Commands;

use App\Models\{LoanSchedule};
use App\Notifications\{RepaymentDueNotification, PaymentOverdueNotification};
use Illuminate\Console\Command;

class SendRepaymentReminders extends Command
{
    protected $signature = 'loans:send-reminders';
    protected $description = 'Send email reminders to clients with upcoming or overdue repayments.';

    public function handle(): void
    {
        $this->info('Sending repayment reminders...');

        $due_count = 0;
        $overdue_count = 0;

        // 1. Due in exactly 3 days — send "due soon" reminder
        $dueSoon = LoanSchedule::with(['loan.client.user'])
            ->where('status', 'pending')
            ->whereDate('due_date', now()->addDays(3)->toDateString())
            ->get();

        foreach ($dueSoon as $schedule) {
            $user = $schedule->loan?->client?->user;
            if ($user?->email) {
                $user->notify(new RepaymentDueNotification($schedule));
                $due_count++;
            }
        }

        // 2. Overdue today — send "overdue" warning
        $overdue = LoanSchedule::with(['loan.client.user'])
            ->where('status', 'pending')
            ->where('due_date', '<', now()->startOfDay())
            ->get();

        foreach ($overdue as $schedule) {
            $user = $schedule->loan?->client?->user;
            if ($user?->email) {
                $user->notify(new PaymentOverdueNotification($schedule));
                $overdue_count++;
            }
        }

        $this->info("Done. Reminders sent — Due Soon: {$due_count}, Overdue Warnings: {$overdue_count}.");
    }
}
