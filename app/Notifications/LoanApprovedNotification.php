<?php

namespace App\Notifications;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoanApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Loan $loan)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $amount = number_format((float) $this->loan->amount, 2);
        $installments = $this->loan->installment_count;
        $startDate = $this->loan->start_date
            ? Carbon::parse($this->loan->start_date)->format('d M Y')
            : 'TBD';

        return (new MailMessage)
            ->subject('✅ Your Loan Has Been Approved!')
            ->greeting("Congratulations, {$notifiable->name}!")
            ->line("We are pleased to inform you that your loan application has been **approved**.")
            ->line("**Loan Amount:** ₦{$amount}")
            ->line("**Repayment Period:** {$installments} installments starting {$startDate}")
            ->line("**Interest Rate:** {$this->loan->interest_rate}% per month")
            ->action('View Loan Details', url('/loans/' . $this->loan->id))
            ->line('Please review your repayment schedule and ensure timely payments.');
    }
}
