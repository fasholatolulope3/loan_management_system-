<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Payment, LoanSchedule, Loan, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class PaymentApiController extends Controller
{
    /**
     * GET /api/payments
     * List payments scoped by role.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Payment::with(['loan.client.user', 'schedule'])->latest();

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client) {
                return response()->json(['data' => []]);
            }
            $loanIds = $client->loans()->pluck('id');
            $query->whereIn('loan_id', $loanIds);
        } elseif ($user->role === 'officer') {
            $query->whereHas('loan', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return response()->json($query->paginate(15));
    }

    /**
     * POST /api/payments
     * Record a new repayment. Clients submit this for a pending schedule.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'schedule_id' => ['required', 'exists:loan_schedules,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'in:cash,transfer,bank'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $loan = Loan::findOrFail($validated['loan_id']);

        // Ensure client is only recording their own payment
        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client || $loan->client_id !== $client->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        $payment = DB::transaction(function () use ($validated, $user) {
            $payment = Payment::create([
                'loan_id' => $validated['loan_id'],
                'loan_schedule_id' => $validated['schedule_id'],
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'paid_at' => $validated['paid_at'] ?? now(),
                'status' => 'pending', // pending officer verification
                'recorded_by' => $user->id,
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'payment_recorded',
                'description' => "Payment of {$validated['amount']} recorded via API for Loan #{$validated['loan_id']}.",
                'model_type' => Payment::class,
                'model_id' => $payment->id,
            ]);

            return $payment;
        });

        return response()->json([
            'message' => 'Payment recorded successfully. Awaiting officer verification.',
            'data' => $payment->load('loan', 'schedule'),
        ], 201);
    }

    /**
     * POST /api/payments/{payment}/verify
     * Verify a payment — Officer/Admin only.
     */
    public function verify(Payment $payment)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized. Only officers or admins can verify payments.'], 403);
        }

        if ($payment->status === 'verified') {
            return response()->json(['message' => 'This payment has already been verified.'], 422);
        }

        DB::transaction(function () use ($payment, $user) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $user->id,
                'verified_at' => now(),
            ]);

            // Mark the associated schedule as paid
            if ($payment->schedule) {
                $payment->schedule->update(['status' => 'paid']);
            }

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'payment_verified',
                'description' => "Payment #{$payment->id} verified via API.",
                'model_type' => Payment::class,
                'model_id' => $payment->id,
            ]);
        });

        return response()->json([
            'message' => 'Payment verified successfully.',
            'data' => $payment->fresh(['loan', 'schedule']),
        ]);
    }
}
