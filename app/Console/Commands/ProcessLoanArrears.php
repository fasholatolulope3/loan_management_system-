<?php

namespace App\Console\Commands;

use App\Models\{Loan, LoanSchedule, Penalty, AuditLog};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessLoanArrears extends Command
{
    protected $signature = 'loans:process-arrears';
    protected $description = 'Daily job: flag overdue schedules, record penalties, and mark loans as defaulted after 90 days.';

    // After 90 days of arrears, the loan is considered defaulted
    const DAYS_TO_DEFAULT = 90;

    public function handle(): void
    {
        $this->info('Processing loan arrears...');

        // 1. Find all pending schedules that are past their due date
        $overdueSchedules = LoanSchedule::with('loan')
            ->where('status', 'pending')
            ->where('due_date', '<', now()->startOfDay())
            ->get();

        $penaltiesRecorded = 0;
        $loansDefaulted = 0;

        foreach ($overdueSchedules as $schedule) {
            $loan = $schedule->loan;

            // Skip already defaulted or non-active loans
            if (!$loan || !in_array($loan->status, ['active', 'pending'])) {
                continue;
            }

            $daysLate = now()->diffInDays($schedule->due_date);
            $penalty = $schedule->accrued_penalty;

            // 2. Record today's penalty if not already recorded today
            DB::transaction(function () use ($schedule, $loan, $penalty, $daysLate, &$penaltiesRecorded) {
                $alreadyRecorded = Penalty::where('loan_id', $loan->id)
                    ->where('schedule_id', $schedule->id)
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$alreadyRecorded && $penalty > 0) {
                    Penalty::create([
                        'loan_id' => $loan->id,
                        'schedule_id' => $schedule->id,
                        'amount' => $penalty,
                        'reason' => "Day {$daysLate} late payment penalty (0.5%/day)",
                        'status' => 'active',
                    ]);
                    $penaltiesRecorded++;
                }
            });

            // 3. Mark loan as defaulted if arrears exceed threshold
            $maxDaysLate = LoanSchedule::where('loan_id', $loan->id)
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->get()
                ->max(fn($s) => now()->diffInDays($s->due_date));

            if ($maxDaysLate >= self::DAYS_TO_DEFAULT && $loan->status !== 'defaulted') {
                DB::transaction(function () use ($loan, &$loansDefaulted) {
                    $loan->update(['status' => 'defaulted']);

                    AuditLog::create([
                        'user_id' => null,
                        'action' => 'loan_defaulted',
                        'description' => "Loan #{$loan->id} auto-marked as defaulted after " . self::DAYS_TO_DEFAULT . " days in arrears.",
                        'model_type' => Loan::class,
                        'model_id' => $loan->id,
                    ]);

                    $loansDefaulted++;
                });
            }
        }

        $this->info("Done. Penalties recorded: {$penaltiesRecorded}. Loans defaulted: {$loansDefaulted}.");
    }
}
