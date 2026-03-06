<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Loan Statement — #{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            margin: 30px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #555;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .section-title {
            background: #1e3a5f;
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .info-grid td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .info-grid tr:nth-child(even) {
            background: #f5f7fa;
        }

        .label {
            color: #555;
            width: 40%;
        }

        table.schedule {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 11px;
        }

        table.schedule th {
            background: #1e3a5f;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
        }

        table.schedule td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.schedule tr:nth-child(even) td {
            background: #f5f7fa;
        }

        .status-paid {
            color: #16a34a;
            font-weight: bold;
        }

        .status-pending {
            color: #d97706;
            font-weight: bold;
        }

        .status-overdue {
            color: #dc2626;
            font-weight: bold;
        }

        .totals {
            margin-top: 16px;
            text-align: right;
            font-size: 12px;
        }

        .totals td {
            padding: 4px 8px;
        }

        .totals .grand {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a5f;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <h1>Loan Account Statement</h1>
    <p class="subtitle">Generated on: {{ now()->format('d M Y, H:i') }}</p>

    <div class="section-title">Borrower Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Name</td>
            <td>{{ $loan->client?->user?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td>{{ $loan->client?->user?->email ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Phone</td>
            <td>{{ $loan->client?->user?->phone ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">National ID</td>
            <td>{{ $loan->client?->national_id ?? '—' }}</td>
        </tr>
    </table>

    <div class="section-title">Loan Details</div>
    <table class="info-grid">
        <tr>
            <td class="label">Loan Reference</td>
            <td>#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="label">Loan Product</td>
            <td>{{ $loan->product?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Principal Amount</td>
            <td>₦{{ number_format((float) $loan->amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Interest Rate</td>
            <td>{{ $loan->interest_rate }}% per month</td>
        </tr>
        <tr>
            <td class="label">Installments</td>
            <td>{{ $loan->installment_count }}</td>
        </tr>
        <tr>
            <td class="label">Start Date</td>
            <td>{{ $loan->start_date?->format('d M Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">End Date</td>
            <td>{{ $loan->end_date?->format('d M Y') ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>{{ ucfirst($loan->status) }}</td>
        </tr>
        <tr>
            <td class="label">Remaining Balance</td>
            <td>₦{{ number_format($loan->remainingBalance(), 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total Arrears</td>
            <td>₦{{ number_format($loan->totalArrearsAmount(), 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Repayment Schedule</div>
    <table class="schedule">
        <thead>
            <tr>
                <th>#</th>
                <th>Due Date</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Total Due</th>
                <th>Penalty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->schedules as $i => $schedule)
                @php
                    $statusClass = match ($schedule->status) {
                        'paid' => 'status-paid',
                        'pending' => now() > $schedule->due_date ? 'status-overdue' : 'status-pending',
                        default => ''
                    };
                    $statusLabel = $schedule->status === 'pending' && now() > $schedule->due_date ? 'Overdue' : ucfirst($schedule->status);
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $schedule->due_date?->format('d M Y') }}</td>
                    <td>₦{{ number_format((float) $schedule->principal_amount, 2) }}</td>
                    <td>₦{{ number_format((float) $schedule->interest_amount, 2) }}</td>
                    <td>₦{{ number_format((float) $schedule->total_due, 2) }}</td>
                    <td>₦{{ number_format((float) $schedule->accrued_penalty, 2) }}</td>
                    <td class="{{ $statusClass }}">{{ $statusLabel }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This document is automatically generated and does not require a signature.
        For queries, contact your loan officer at your branch.
    </div>

</body>

</html>