<?php

namespace App\Http\Controllers;

use App\Models\{Payment, LoanSchedule, Loan, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments received.
     */
    public function index()
    {
        $payments = Payment::with(['loan.client.user', 'collector'])
            ->latest()
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        // We expect loan_id and schedule_id to be passed from the Loan Details page
        $loan = Loan::with(['client.user', 'schedules'])->findOrFail($request->loan_id);
        $schedule = LoanSchedule::findOrFail($request->schedule_id);

        return view('payments.create', compact('loan', 'schedule'));
    }

    /**
     * Store a newly created payment, update schedule, and log the action.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'schedule_id' => 'required|exists:loan_schedules,id',
            'amount_paid' => 'required|numeric|min:1',
            'method' => 'required|in:cash,transfer,card',
            'reference' => 'required|unique:payments,reference'
        ]);

        DB::transaction(function () use ($validated, $request) {
            // 1. Record the Payment
            Payment::create($validated + [
                'payment_date' => now(),
                'captured_by' => auth()->id()
            ]);

            // 2. Update the Schedule Status
            $schedule = LoanSchedule::findOrFail($validated['schedule_id']);

            // If the amount paid covers the total due, mark as paid
            if ($validated['amount_paid'] >= $schedule->total_due) {
                $schedule->update(['status' => 'paid']);
            }

            // 3. Check if the entire Loan is now completed
            $loan = Loan::find($validated['loan_id']);
            $unpaidCount = $loan->schedules()->where('status', '!=', 'paid')->count();

            if ($unpaidCount === 0) {
                $loan->update(['status' => 'completed']);
            }

            // 4. Create Audit Log for financial transparency
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'payment_received',
                'description' => "Recorded payment of ₦" . number_format($validated['amount_paid'], 2) . " for Loan #{$loan->id}",
                'ip_address' => $request->ip()
            ]);
        });

        return redirect()->route('loans.show', $request->loan_id)
            ->with('success', 'Payment recorded and schedule updated successfully.');
    }
}
