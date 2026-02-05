<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Auth};
use App\Models\{Loan, Client, Payment, AuditLog, LoanProduct, LoanSchedule, Collateral};
 

class LoanController extends Controller
{
    /**
     * Requirement #6: Separate interfaces. 
     * Requirement #5: Scoped by Collation Center.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Loan::with(['client.user', 'product', 'collationCenter']);

        // Data Isolation: Staff only see their center's loans
        if ($user->role === 'officer') {
            $query->where('collation_center_id', $user->collation_center_id);
        }

        // Requirement #9: Repayment/Arrears search logic
        if ($request->search) {
            $query->whereHas('client.user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                ->orWhere('id', 'like', "%{$request->search}%")
                ->orWhere('business_name', 'like', "%{$request->search}%");
        }

        $loans = $query->latest()->paginate(15);
        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        $products = LoanProduct::where('status', 'active')->get();
        // Requirements #4: Interface for Admin/Staff only
        $clients = Client::with('user')->get();
        return view('loans.create', compact('products', 'clients'));
    }

    /**
     * Requirement #7: Step-by-Step Loan Order.
     * Processes Personal Info -> Business -> Cash Flow -> Balance Sheet -> Proposal -> Guarantor
     */
    public function store(Request $request): RedirectResponse
    {
        $staff = Auth::user();

        // 🚩 SENIOR FIX: Validate Staff Assignment
        if (!$staff->collation_center_id) {
            return back()->with('error', 'FATAL: Your account is not assigned to any Collation Center. Please contact the System Admin to assign you to a branch before initiating loans.')->withInput();
        }
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'amount' => 'required|numeric|min:1000',

            // Applicant Business Info (Part II)
            'business_name' => 'required|string|max:255',
            'business_location' => 'required|string',
            'business_premise_type' => 'required|in:own,rent',
            'business_start_date' => 'required|date',

            // Cash Flow Metrics (From Form Tables)
            'monthly_sales' => 'required|numeric',
            'cost_of_sales' => 'required|numeric',
            'operational_expenses' => 'required|numeric',
            'family_expenses' => 'required|numeric',
            'other_net_income' => 'nullable|numeric',

            // Asset & Liability Metrics
            'current_assets' => 'required|numeric',
            'fixed_assets' => 'required|numeric',
            'total_liabilities' => 'required|numeric',

            // JSON data structures for repeating form tables
            'daily_sales_logs' => 'nullable|array',
            'inventory_details' => 'nullable|array',
            'business_references' => 'nullable|array',
            'risk_mitigation' => 'nullable|array',
            'collaterals' => 'required|array|min:1',

            // Proposal & Guarantor Info (Part III & IV)
            'proposal_summary' => 'required|string|min:20',
            'guarantor_id' => 'required|exists:guarantors,id', // From pre-filled registry
            'guarantor_business_info' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $product = LoanProduct::find($request->loan_product_id);

            // Automated Calculations (Payment Capacity & Equity)
            $grossProfit = $request->monthly_sales - $request->cost_of_sales;
            $netProfit = $grossProfit - $request->operational_expenses;
            $paymentCapacity = $netProfit + ($request->other_net_income ?? 0) - $request->family_expenses;
            $equity = ($request->current_assets + $request->fixed_assets) - $request->total_liabilities;

            $loan = Loan::create([
                'collation_center_id' => Auth::user()->collation_center_id,
                'client_id' => $request->client_id,
                'loan_product_id' => $product->id,
                'amount' => $request->amount,
                'interest_rate' => $product->interest_rate,
                'duration_months' => $product->duration_months,
                'status' => 'pending',
                'approval_status' => 'pending_review',

                'business_name' => $request->business_name,
                'business_location' => $request->business_location,
                'business_start_date' => $request->business_start_date,

                'monthly_sales' => $request->monthly_sales,
                'cost_of_sales' => $request->cost_of_sales,
                'gross_profit' => $grossProfit,
                'operational_expenses' => $request->operational_expenses,
                'net_profit' => $netProfit,
                'payment_capacity' => $paymentCapacity,
                'equity_value' => $equity,

                'proposal_summary' => $request->proposal_summary,
                'daily_sales_logs' => $request->daily_sales_logs,
                'inventory_details' => $request->inventory_details,
                'business_references' => $request->business_references,
            ]);

            // Save Collateral (Requirement #7 - From Form CF5)
            foreach ($request->collaterals as $c) {
                $loan->collaterals()->create([
                    'type' => $c['type'],
                    'description' => $c['description'],
                    'market_value' => $c['market_value'],
                    'liquidation_value' => $c['market_value'] * 0.70, // Standard 70% rule
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_proposal_submitted',
                'description' => "Officer submitted loan proposal for {$request->business_name}. Capacity: ₦{$paymentCapacity}",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('loans.index')->with('success', 'Credit File submitted for Admin review.');
        });
    }

    /**
     * Requirement #2, #3 & #8: Interval Logic & Re-approvals.
     */
    public function approve(Loan $loan): RedirectResponse
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        return DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'active',
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'start_date' => now(),
            ]);

            // 1. Mark capital as Disbursed (Payment type: Outflow)
            Payment::create([
                'loan_id' => $loan->id,
                'type' => 'disbursement',
                'amount_paid' => $loan->amount,
                'method' => 'transfer',
                'reference' => 'DISB-' . strtoupper(Str::random(8)),
                'verification_status' => 'verified',
                'payment_date' => now(),
                'captured_by' => Auth::id()
            ]);

            // 2. Interval Amortization (Requirement #2 & #3)
            $totalPayable = $loan->amount + ($loan->amount * ($loan->interest_rate / 100));
            $installment = $totalPayable / $loan->duration_months;
            $interval = strtolower($loan->product->name);

            for ($i = 1; $i <= $loan->duration_months; $i++) {
                $dueDate = match ($interval) {
                    'daily' => now()->addDays($i),
                    'weekly' => now()->addWeeks($i),
                    default => now()->addMonths($i),
                };

                LoanSchedule::create([
                    'loan_id' => $loan->id,
                    'due_date' => $dueDate,
                    'principal_amount' => $loan->amount / $loan->duration_months,
                    'interest_amount' => ($loan->amount * ($loan->interest_rate / 100)) / $loan->duration_months,
                    'total_due' => $installment,
                    'status' => 'pending'
                ]);
            }

            return back()->with('success', "Disbursement authorized via " . ucfirst($interval) . " Repayment Schedule.");
        });
    }

    /**
     * Requirement #8: The "Adjustment" Loop back to Staff
     */
    public function requestAdjustment(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate(['notes' => 'required|string']);

        $loan->update([
            'approval_status' => 'adjustment_needed',
            'review_notes' => $request->notes
        ]);

        return back()->with('warning', 'Loan File sent back to field officer for adjustments.');
    }

    /**
     * Requirement #9: Arrears & Penalty Search
     */
    public function arrears(Request $request): View
    {
        $query = LoanSchedule::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->with(['loan.client.user', 'loan.collationCenter']);

        // Requirement #1: Dynamic 0.005 Penalty calculation logic applied in the view via accessor

        $arrears = $query->latest('due_date')->paginate(20);
        return view('reports.arrears', compact('arrears'));
    }

    /**
     * Detailed View showing Balance Sheet & Assessment Ratios
     */
    public function show(Loan $loan): View
    {
        $loan->load(['client.user', 'product', 'collationCenter', 'collaterals', 'payments', 'schedules']);

        // Financial Underwriting Ratios
        $ratios = [
            'drs' => ($loan->amount / $loan->duration_months) / max($loan->payment_capacity, 1),
            'margin' => ($loan->gross_profit / max($loan->monthly_sales, 1)) * 100
        ];

        return view('loans.show', compact('loan', 'ratios'));
    }

    public function print(Loan $loan): View
    {
        // Eager load everything needed for the print forms (CF2, CF4, CF5)
        $loan->load([
            'client.user',
            'guarantor',
            'collaterals',
            'collationCenter',
            'product',
            'schedules'
        ]);

        // Safety: ensure staff only print from their own center (unless Admin)
        if (auth()->user()->role === 'officer' && $loan->collation_center_id !== auth()->user()->collation_center_id) {
            abort(403, 'Unauthorized print access.');
        }

        return view('loans.print', compact('loan'));
    }
}