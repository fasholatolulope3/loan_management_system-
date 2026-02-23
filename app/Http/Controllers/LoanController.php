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

        // 🚩 AUTHORITY VERIFICATION: Validate Staff Assignment
        if (!$staff->collation_center_id && $staff->role !== 'admin') {
            return back()->with('error', 'FATAL: Account is not assigned to a Collation Center branch. Engagement blocked.')->withInput();
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'amount' => 'required|numeric|min:1000',

            // Applicant Demographics (NEW)
            'residence_since' => 'required|string',
            'dependent_count' => 'required|integer|min:0',
            'home_ownership' => 'required|string',
            'next_rent_amount' => 'nullable|numeric|required_if:home_ownership,renting',
            'next_rent_date' => 'nullable|date|required_if:home_ownership,renting',

            // Applicant Business Profile (Part I)
            'business_name' => 'required|string|max:255',
            'business_location' => 'required|string',
            'business_premise_type' => 'required|in:own,rent',
            'business_start_date' => 'required|date',
            'point_of_sale_count' => 'required|integer|min:0',
            'employee_count' => 'required|integer|min:0',
            'has_co_owners' => 'nullable|boolean',

            // Applicant Financials (Part II)
            'monthly_sales' => 'required|numeric',
            'cost_of_sales' => 'required|numeric',
            'operational_expenses' => 'required|numeric',
            'family_expenses' => 'required|numeric',
            'current_assets' => 'required|numeric',
            'fixed_assets' => 'required|numeric',
            'total_liabilities' => 'required|numeric',

            // Evaluation Logs (Part III - JSON)
            'daily_sales_logs' => 'required|array|size:3',
            'inventory_details' => 'required|array|min:1',
            'business_references' => 'required|array|min:1',
            'risk_mitigation' => 'required|array|min:1',

            // Guarantor Section (Part IV)
            'guarantor_id' => 'nullable|exists:guarantors,id',
            'g_name' => 'nullable|string|required_without:guarantor_id',
            'g_relationship' => 'nullable|string|required_without:guarantor_id',
            'g_monthly_income' => 'nullable|numeric',
            'g_monthly_expenses' => 'nullable|numeric',
            'g_net_worth' => 'nullable|numeric',
            'g_income_source' => 'nullable|string',

            // Final Evaluation
            'collaterals' => 'required|array|min:1',
            'proposal_summary' => 'required|string|min:40',
        ]);

        return DB::transaction(function () use ($request, $staff) {
            $product = LoanProduct::findOrFail($request->loan_product_id);

            // 1. Resolve Guarantor Identity
            $guarantorId = $request->guarantor_id;
            if (!$guarantorId && $request->g_name) {
                $newGuarantor = \App\Models\Guarantor::create([
                    'name' => $request->g_name,
                    'phone' => '0000000000', // To be updated by staff later
                    'address' => 'Pending Confirmation',
                    'occupation' => $request->g_income_source ?? 'Unknown',
                ]);
                $guarantorId = $newGuarantor->id;
            }

            // 2. Automated Underwriting Metrics
            $grossProfit = $request->monthly_sales - $request->cost_of_sales;
            $netProfit = $grossProfit - $request->operational_expenses;
            $paymentCapacity = $netProfit - $request->family_expenses;
            $equity = ($request->current_assets + $request->fixed_assets) - $request->total_liabilities;

            // 3. Persistent Credit File Creation
            $loan = Loan::create([
                'collation_center_id' => $staff->collation_center_id ?? Loan::first()?->collation_center_id, // Fallback for testing
                'client_id' => $request->client_id,
                'guarantor_id' => $guarantorId,
                'loan_product_id' => $product->id,
                'amount' => $request->amount,
                'interest_rate' => $product->interest_rate,
                'duration_months' => $product->duration_months,
                'status' => 'pending',
                'approval_status' => 'pending_review',

                // Applicant Demographics
                'residence_since' => $request->residence_since,
                'dependent_count' => $request->dependent_count,
                'home_ownership' => $request->home_ownership,
                'next_rent_amount' => $request->next_rent_amount ?? 0,
                'next_rent_date' => $request->next_rent_date,

                // Business Metadata
                'business_name' => $request->business_name,
                'business_location' => $request->business_location,
                'business_start_date' => $request->business_start_date,
                'point_of_sale_count' => $request->point_of_sale_count,
                'employee_count' => $request->employee_count,
                'has_co_owners' => $request->has_boolean('has_co_owners'),

                // Financial Snapshots
                'monthly_sales' => $request->monthly_sales,
                'cost_of_sales' => $request->cost_of_sales,
                'gross_profit' => $grossProfit,
                'operational_expenses' => $request->operational_expenses,
                'net_profit' => $netProfit,
                'payment_capacity' => $paymentCapacity,
                'equity_value' => $equity,
                'current_assets' => $request->current_assets,
                'fixed_assets' => $request->fixed_assets,
                'total_liabilities' => $request->total_liabilities,

                // JSON Assessment Tables (NEW)
                'daily_sales_logs' => $request->daily_sales_logs,
                'inventory_details' => $request->inventory_details,
                'business_references' => $request->business_references,
                'risk_mitigation' => $request->risk_mitigation,

                // Guarantor Snapshot (Req #7 - Part III)
                'guarantor_business_financials' => [
                    'income' => $request->g_monthly_income,
                    'expenses' => $request->g_monthly_expenses,
                    'net_worth' => $request->g_net_worth,
                    'source' => $request->g_income_source,
                    'relationship' => $request->g_relationship
                ],

                'proposal_summary' => $request->proposal_summary,
            ]);

            // 4. Detailed Asset Registry (Req #7 - Part IV)
            foreach ($request->collaterals as $c) {
                $loan->collaterals()->create([
                    'type' => $c['type'],
                    'description' => $c['description'],
                    'market_value' => $c['market_value'],
                    'liquidation_value' => $c['market_value'] * 0.70,
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_proposal_submitted',
                'description' => "Officer initiated Credit File for {$request->business_name}. Capacity: ₦" . number_format($paymentCapacity, 2),
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('loans.index')->with('success', 'Credit File submitted for Executive Review.');
        });
    }

    public function edit(Loan $loan): View
    {
        if (!in_array($loan->approval_status, ['pending_review', 'adjustment_needed'])) {
            abort(403, 'Loan is not in an editable state.');
        }

        $products = LoanProduct::where('status', 'active')->get();
        $clients = Client::with('user')->get();
        $loan->load(['collaterals', 'guarantor']);

        return view('loans.edit', compact('loan', 'products', 'clients'));
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        if (!in_array($loan->approval_status, ['pending_review', 'adjustment_needed'])) {
            abort(403, 'Loan is not in an editable state.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000',
            'business_name' => 'required|string|max:255',
            'business_location' => 'required|string',
            'monthly_sales' => 'required|numeric',
            'cost_of_sales' => 'required|numeric',
            'operational_expenses' => 'required|numeric',
            'family_expenses' => 'required|numeric',
            'current_assets' => 'required|numeric',
            'fixed_assets' => 'required|numeric',
            'total_liabilities' => 'required|numeric',
            'proposal_summary' => 'required|string|min:40',
        ]);

        return DB::transaction(function () use ($request, $loan) {
            $grossProfit = $request->monthly_sales - $request->cost_of_sales;
            $netProfit = $grossProfit - $request->operational_expenses;
            $paymentCapacity = $netProfit - $request->family_expenses;
            $equity = ($request->current_assets + $request->fixed_assets) - $request->total_liabilities;

            $loan->update([
                'amount' => $request->amount,
                'business_name' => $request->business_name,
                'business_location' => $request->business_location,
                'monthly_sales' => $request->monthly_sales,
                'cost_of_sales' => $request->cost_of_sales,
                'gross_profit' => $grossProfit,
                'operational_expenses' => $request->operational_expenses,
                'net_profit' => $netProfit,
                'payment_capacity' => $paymentCapacity,
                'equity_value' => $equity,
                'current_assets' => $request->current_assets,
                'fixed_assets' => $request->fixed_assets,
                'total_liabilities' => $request->total_liabilities,
                'proposal_summary' => $request->proposal_summary,
                'approval_status' => 'pending_review',
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_proposal_adjusted',
                'description' => "Officer adjusted Credit File for {$loan->business_name} after review feedback.",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('loans.show', $loan)->with('success', 'Credit File has been updated and re-submitted for review.');
        });
    }

    /**
     * Requirement #2, #3 & #8: Interval Logic & Re-approvals.
     */
    public function approve(Loan $loan): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Executive Authorization Required.');
        }

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
                'reference' => 'DISB-' . strtoupper(Str::random(10)),
                'verification_status' => 'verified',
                'payment_date' => now(),
                'captured_by' => Auth::id()
            ]);

            // 2. Amortization Computation (Req #1, #2, #3)
            $interestTotal = $loan->amount * ($loan->interest_rate / 100);
            $totalPayable = $loan->amount + $interestTotal;
            $duration = $loan->duration_months;
            $installmentAmount = $totalPayable / $duration;

            $productName = strtolower($loan->product->name);
            $intervalType = 'monthly'; // default

            if (str_starts_with($productName, 'daily'))
                $intervalType = 'daily';
            elseif (str_starts_with($productName, 'weekly'))
                $intervalType = 'weekly';
            elseif (str_starts_with($productName, 'monthly'))
                $intervalType = 'monthly';

            $currentDate = now();
            for ($i = 1; $i <= $duration; $i++) {
                if ($intervalType === 'daily') {
                    // Logic for Daily "Working Days" (Skip Sat/Sun)
                    $currentDate->addDay();
                    while ($currentDate->isWeekend()) {
                        $currentDate->addDay();
                    }
                    $dueDate = $currentDate->copy();
                } else {
                    $dueDate = match ($intervalType) {
                        'weekly' => now()->addWeeks($i),
                        'monthly' => now()->addMonths($i),
                        default => now()->addMonths($i),
                    };
                }

                LoanSchedule::create([
                    'loan_id' => $loan->id,
                    'due_date' => $dueDate,
                    'principal_amount' => $loan->amount / $duration,
                    'interest_amount' => $interestTotal / $duration,
                    'total_due' => $installmentAmount,
                    'status' => 'pending'
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'loan_approved',
                'description' => "Admin authorized disbursement of ₦" . number_format((float) $loan->amount, 2) . " for {$loan->business_name}.",
                'ip_address' => request()->ip()
            ]);

            return back()->with('success', "Executive Authorization granted. " . ucfirst($intervalType) . " Repayment Schedule generated.");
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

    public function arrears(Request $request): View
    {
        $user = Auth::user();
        $query = LoanSchedule::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->with(['loan.client.user', 'loan.collationCenter.officers']);

        // 1. ROLE-BASED SCOPING (Req #9)
        if ($user->role === 'officer' && $user->collation_center_id) {
            $query->whereHas('loan', function ($q) use ($user) {
                $q->where('collation_center_id', $user->collation_center_id);
            });
        }

        // 2. SEARCH & TRACING (Req #9)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('loan.client.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })->orWhereHas('loan.collationCenter', function ($q) use ($search) {
                $q->where('center_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $arrears = $query->latest('due_date')->paginate(30);

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