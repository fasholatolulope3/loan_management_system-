<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Client;
use App\Models\AuditLog;
use App\Models\LoanProduct;
use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    /**
     * Display a listing of loans based on user role.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $query = Loan::with(['client.user', 'product']);

        if ($user->role === 'client') {
            if (!$user->client) {
                return redirect()->route('dashboard')->with('error', 'Please complete your KYC profile first.');
            }
            $query->where('client_id', $user->client->id);
        }

        // Optional: Filter by status if provided in request
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest()->paginate(10);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan application.
     */
    public function create(): View
    {
        $products = LoanProduct::where('status', 'active')->get();
        return view('loans.create', compact('products'));
    }

    /**
     * Store a newly created loan application.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'amount' => 'required|numeric|min:1000',
        ]);

        $product = LoanProduct::findOrFail($request->loan_product_id);

        // 1. Validate against Product Limits
        if ($request->amount < $product->min_amount || $request->amount > $product->max_amount) {
            return back()->withErrors([
                'amount' => "Amount must be between ₦" . number_format($product->min_amount) . " and ₦" . number_format($product->max_amount)
            ])->withInput();
        }

        // 2. Prevent multiple active/pending applications (Double Dipping Check)
        $hasActiveLoan = Loan::where('client_id', $request->client_id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('loans.index')->with('error', 'You currently have an active or pending loan application.');
        }

        return DB::transaction(function () use ($request, $product) {
            $loan = Loan::create([
                'client_id' => $request->client_id,
                'loan_product_id' => $product->id,
                'amount' => $request->amount,
                'interest_rate' => $product->interest_rate,
                'duration_months' => $product->duration_months,
                'status' => 'pending',
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_applied',
                'description' => "Loan application of ₦" . number_format($request->amount) . " submitted for product: {$product->name}",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('loans.index')->with('success', 'Application submitted for review.');
        });
    }

    /**
     * Display the specified loan details.
     */
    public function show(Loan $loan): View
    {
        // Security check: Clients can only see their own loans
        if (Auth::user()->role === 'client' && $loan->client_id !== Auth::user()->client->id) {
            abort(403, 'Unauthorized access to loan record.');
        }

        $loan->load(['schedules', 'payments', 'client.user', 'product']);

        return view('loans.show', compact('loan'));
    }

    /**
     * Approve a loan and generate the repayment schedule.
     */
    public function approve(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'active',
                'approved_by' => Auth::id(),
                'start_date' => now(),
                'end_date' => now()->addMonths($loan->duration_months),
            ]);

            // Amortization Logic: Simple Interest
            $principal = $loan->amount;
            $totalInterest = $principal * ($loan->interest_rate / 100);
            $totalPayable = $principal + $totalInterest;
            $monthlyInstallment = $totalPayable / $loan->duration_months;

            for ($i = 1; $i <= $loan->duration_months; $i++) {
                LoanSchedule::create([
                    'loan_id' => $loan->id,
                    'due_date' => Carbon::now()->addMonths($i),
                    'principal_amount' => $principal / $loan->duration_months,
                    'interest_amount' => $totalInterest / $loan->duration_months,
                    'total_due' => $monthlyInstallment,
                    'status' => 'pending',
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_approved',
                'description' => "Approved loan #{$loan->id} and generated {$loan->duration_months} schedules.",
                'ip_address' => request()->ip()
            ]);
        });

        return back()->with('success', 'Loan approved and funds disbursed to schedule.');
    }

    /**
     * Reject a loan application.
     */
    public function reject(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be rejected.');
        }

        $loan->update(['status' => 'rejected']);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'loan_rejected',
            'description' => "Rejected loan application #{$loan->id}",
            'ip_address' => request()->ip()
        ]);

        return back()->with('warning', 'Loan application has been rejected.');
    }

    /**
     * Display the repayment schedule for the logged-in client.
     */
    public function schedules(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user->client) {
            return redirect()->route('dashboard')->with('error', 'Client profile not found.');
        }

        $schedules = LoanSchedule::whereHas('loan', function ($query) use ($user) {
            $query->where('client_id', $user->client->id);
        })
            ->with(['loan.product'])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('loans.schedules', compact('schedules'));
    }
}
