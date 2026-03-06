<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Loan, Payment, AuditLog, LoanProduct};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * GET /api/loans/export
     * Export loans as CSV.
     */
    public function loans(Request $request): StreamedResponse|JsonResponse
    {
        $user = Auth::user();

        $query = Loan::with(['client.user', 'product'])->latest();

        if ($user->role === 'officer') {
            $query->where('collation_center_id', $user->collation_center_id);
        } elseif ($user->role === 'client') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="loans-export-' . now()->format('Ymd') . '.csv"',
        ];

        return response()->stream(function () use ($loans) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Client', 'Email', 'Product', 'Amount', 'Interest Rate', 'Installments', 'Start Date', 'Status', 'Approved By']);

            foreach ($loans as $loan) {
                fputcsv($handle, [
                    str_pad($loan->id, 6, '0', STR_PAD_LEFT),
                    $loan->client?->user?->name,
                    $loan->client?->user?->email,
                    $loan->product?->name,
                    number_format((float) $loan->amount, 2),
                    $loan->interest_rate . '%',
                    $loan->installment_count,
                    $loan->start_date?->format('Y-m-d'),
                    $loan->status,
                    $loan->approver?->name ?? '—',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * GET /api/payments/export
     * Export payments as CSV.
     */
    public function payments(Request $request): StreamedResponse|JsonResponse
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $query = Payment::with(['loan.client.user'])->latest();

        if ($user->role === 'officer') {
            $query->whereHas('loan', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        $payments = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payments-export-' . now()->format('Ymd') . '.csv"',
        ];

        return response()->stream(function () use ($payments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Loan ID', 'Client', 'Amount', 'Method', 'Status', 'Paid At', 'Verified At']);

            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->id,
                    str_pad($payment->loan_id, 6, '0', STR_PAD_LEFT),
                    $payment->loan?->client?->user?->name,
                    number_format((float) $payment->amount, 2),
                    $payment->method,
                    $payment->status,
                    $payment->paid_at,
                    $payment->verified_at ?? '—',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
