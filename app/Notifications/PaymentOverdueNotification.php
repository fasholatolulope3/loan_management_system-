<?php

namespace App\Notifications;

use App\Models\LoanSchedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(public LoanSchedule $schedule)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $loan = $this->schedule->loan;
        $dueDate = Carbon::parse($this->schedule->due_date)->format('d M Y');
        $amount = number_format((float) $this->schedule->total_due, 2);
        $daysLate = (int) now()->diffInDays($this->schedule->due_date);
        $penalty = number_format((float) $this->schedule->accrued_penalty, 2);

        return (new MailMessage)
            ->subject("⚠️ Overdue Payment — Action Required")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your loan repayment of **₦{$amount}** was due on **{$dueDate}** and is now **{$daysLate} day(s) overdue**.")
            ->line("Accrued Penalty: **₦{$penalty}**")
            ->line("Loan Reference: #" . str_pad($loan->id, 6, '0', STR_PAD_LEFT))
            ->action('Make Payment Now', url('/loans/' . $loan->id))
            ->line('Please contact us immediately to avoid further penalties or loan default.')
            ->salutation('Loan Management Team');
    }
}
