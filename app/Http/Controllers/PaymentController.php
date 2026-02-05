<?php

namespace App\Http\Controllers;

use App\Models\{Payment, LoanSchedule, Loan, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     * Incorporates Two-Way View Logic:
     * - Clients see their repayments (Money Out) and Disbursements (Money In).
     * - Admins see all flows.
     */
    public function index()
    {
        $user = Auth::user();

        // Eager load relationships for performance
        $query = Payment::with(['loan.client.user', 'collector']);

        // 1. Client View Restriction
        if ($user->role === 'client') {
            if (!$user->client) {
                abort(403, 'Client profile not found.');
            }
            // Filter payments belonging to this client's loans
            $query->whereHas('loan', function ($q) use ($user) {
                $q->where('client_id', $user->client->id);
            });
        }

        $payments = $query->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment (Repayment).
     */
    public function create(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'schedule_id' => 'required|exists:loan_schedules,id',
        ]);

        $loan = Loan::with('product')->findOrFail($request->loan_id);
        $schedule = LoanSchedule::findOrFail($request->schedule_id);

        // Security: Clients can only pay for their own loans
        if (auth()->user()->role === 'client' && $loan->client_id !== auth()->user()->client->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('payments.create', compact('loan', 'schedule'));
    }

    /**
     * Store a newly created payment (Repayment).
     */
    public function store(Request $request)
    {
        $rules = [
            'loan_id' => 'required|exists:loans,id',
            'schedule_id' => 'required|exists:loan_schedules,id',
            'amount_paid' => 'required|numeric|min:1',
            'method' => 'required|in:cash,transfer,card',
            'reference' => 'required|unique:payments,reference',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048', // 2MB Max
        ];

        $validated = $request->validate($rules);

        // Handle File Upload
        $path = null;
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
        }

        // DB Transaction to ensure data integrity
        DB::transaction(function () use ($validated, $path) {
            Payment::create([
                'loan_id' => $validated['loan_id'],
                'schedule_id' => $validated['schedule_id'],
                'type' => 'repayment', // <--- CRITICAL: Marks this as money from Client -> Admin
                'amount_paid' => $validated['amount_paid'],
                'method' => $validated['method'],
                'reference' => $validated['reference'],
                'receipt_path' => $path,
                'verification_status' => 'pending', // Stays pending until officer checks it
                'payment_date' => now(),
                'captured_by' => auth()->id(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'payment_submitted',
                'description' => "Submitted repayment of " . $validated['amount_paid'] . " for verification.",
                'ip_address' => request()->ip()
            ]);
        });

        return redirect()->route('loans.show', $request->loan_id)
            ->with('success', 'Payment proof uploaded! Awaiting verification by an officer.');
    }

    /**
     * Senior logic: Only when an officer verifies, the money "counts"
     */
    /**
     * Senior logic: Only when an officer verifies, the money "counts"
     */
    public function verify(Payment $payment)
    {
        // Security check: Clients cannot verify their own payments
        if (Auth::user()->role === 'client') {
            abort(403, 'Unauthorized.');
        }

        return DB::transaction(function () use ($payment) {
            // 1. Mark Payment as Verified
            $payment->update([
                'verification_status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // 2. Update the specific Schedule (ONLY IF IT EXISTS)
            // FIX: This prevents the "read property on null" error for Disbursements
            if ($payment->schedule) {
                $schedule = $payment->schedule;

                if ($payment->amount_paid >= $schedule->total_due) {
                    $schedule->update(['status' => 'paid']);
                } else {
                    $schedule->update(['status' => 'partial']);
                }
            }

            // 3. Check if the WHOLE loan is complete
            $loan = $payment->loan;

            // Count how many schedules are NOT paid yet
            $remainingSchedules = $loan->schedules()
                ->where('status', '!=', 'paid')
                ->count();

            // If 0 schedules remain, mark loan as completed
            if ($remainingSchedules === 0) {
                $loan->update(['status' => 'completed']);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'payment_verified',
                'description' => "Verified payment {$payment->reference} for Loan #{$loan->id}",
                'ip_address' => request()->ip()
            ]);

            return back()->with('success', 'Transaction verified successfully.');
        });
    }
}
