<?php

namespace App\Services;

use App\Models\Client;

class CreditScoreService
{
    /**
     * Calculate a credit score between 300–850 for a given client.
     *
     * Scoring Factors:
     *  - Base score: 500
     *  - On-time repayments: +5 per payment (max +200)
     *  - Overdue payments: -15 per overdue schedule (max -200)
     *  - Completed loans (no default): +50 each (max +100)
     *  - Defaulted loans: -100 each (max -200)
     *  - Active open loans (debt load penalty): -20 each (max -100)
     */
    public function calculate(Client $client): array
    {
        $client->load(['loans.schedules', 'loans.payments']);

        $score = 500;
        $notes = [];

        $totalLoans = $client->loans->count();
        $completedLoans = $client->loans->where('status', 'completed')->count();
        $defaultedLoans = $client->loans->where('status', 'defaulted')->count();
        $activeLoans = $client->loans->where('status', 'active')->count();

        // On-time payments
        $paidOnTime = 0;
        $overdue = 0;
        foreach ($client->loans as $loan) {
            foreach ($loan->schedules as $schedule) {
                if ($schedule->status === 'paid') {
                    $paidOnTime++;
                } elseif ($schedule->status === 'pending' && now() > $schedule->due_date) {
                    $overdue++;
                }
            }
        }

        $onTimeBonus = min($paidOnTime * 5, 200);
        $score += $onTimeBonus;
        if ($onTimeBonus > 0)
            $notes[] = "+{$onTimeBonus} pts for {$paidOnTime} on-time payment(s)";

        $overdueDeduction = min($overdue * 15, 200);
        $score -= $overdueDeduction;
        if ($overdueDeduction > 0)
            $notes[] = "-{$overdueDeduction} pts for {$overdue} overdue schedule(s)";

        // Loan history
        $completedBonus = min($completedLoans * 50, 100);
        $score += $completedBonus;
        if ($completedBonus > 0)
            $notes[] = "+{$completedBonus} pts for {$completedLoans} completed loan(s)";

        $defaultPenalty = min($defaultedLoans * 100, 200);
        $score -= $defaultPenalty;
        if ($defaultPenalty > 0)
            $notes[] = "-{$defaultPenalty} pts for {$defaultedLoans} defaulted loan(s)";

        // Current debt load
        $debtPenalty = min($activeLoans * 20, 100);
        $score -= $debtPenalty;
        if ($debtPenalty > 0)
            $notes[] = "-{$debtPenalty} pts for {$activeLoans} active loan(s)";

        // Clamp between 300 and 850
        $score = max(300, min(850, $score));

        return [
            'score' => $score,
            'rating' => $this->getRating($score),
            'total_loans' => $totalLoans,
            'breakdown' => [
                'paid_on_time' => $paidOnTime,
                'overdue_schedules' => $overdue,
                'completed_loans' => $completedLoans,
                'defaulted_loans' => $defaultedLoans,
                'active_loans' => $activeLoans,
            ],
            'notes' => $notes,
        ];
    }

    private function getRating(int $score): string
    {
        return match (true) {
            $score >= 750 => 'Excellent',
            $score >= 670 => 'Good',
            $score >= 580 => 'Fair',
            $score >= 500 => 'Poor',
            default => 'Very Poor',
        };
    }
}
