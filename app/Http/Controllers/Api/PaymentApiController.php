<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\{Payment, LoanSchedule, Loan, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class PaymentApiController extends Controller
{
    /**
     * GET /api/payments
     */
    public function index()
    {
        $user = Auth::user();

        $query = Payment::with(['loan', 'schedule'])->latest();

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client)
                return response()->json(['data' => []]);
            $query->whereIn('loan_id', $client->loans()->pluck('id'));
        } elseif ($user->role === 'officer') {
            $query->whereHas('loan', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return PaymentResource::collection($query->paginate(15));
    }

    /**
     * POST /api/payments
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
                'status' => 'pending',
                'recorded_by' => $user->id,
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'payment_recorded',
                'description' => "Payment of {$validated['amount']} recorded via API.",
                'model_type' => Payment::class,
                'model_id' => $payment->id,
            ]);

            return $payment;
        });

        return (new PaymentResource($payment->load('loan', 'schedule')))
            ->additional(['message' => 'Payment recorded. Awaiting officer verification.'])
            ->response()->setStatusCode(201);
    }

    /**
     * POST /api/payments/{payment}/verify
     */
    public function verify(Payment $payment)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($payment->status === 'verified') {
            return response()->json(['message' => 'Already verified.'], 422);
        }

        DB::transaction(function () use ($payment, $user) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $user->id,
                'verified_at' => now(),
            ]);

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

        return (new PaymentResource($payment->fresh(['loan', 'schedule'])))
            ->additional(['message' => 'Payment verified successfully.']);
    }
}
