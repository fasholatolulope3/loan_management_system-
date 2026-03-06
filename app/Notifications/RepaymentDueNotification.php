<?php

namespace App\Notifications;

use App\Models\LoanSchedule;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RepaymentDueNotification extends Notification
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

        return (new MailMessage)
            ->subject("Repayment Reminder — Due {$dueDate}")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a friendly reminder that your loan repayment of **₦{$amount}** is due on **{$dueDate}**.")
            ->line("Loan Reference: #" . str_pad($loan->id, 6, '0', STR_PAD_LEFT))
            ->action('View Loan', url('/loans/' . $loan->id))
            ->line('Please ensure payment is made on time to avoid late penalties.');
    }
}
