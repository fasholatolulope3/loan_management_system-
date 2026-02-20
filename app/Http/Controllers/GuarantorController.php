<?php

namespace App\Http\Controllers;

use App\Models\{Guarantor, Client, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GuarantorController extends Controller
{
    /**
     * Requirement #4: Interface for staff only.
     * Requirement #9: Searchable records.
     */
    public function index(Request $request): View
    {
        $query = Guarantor::with('client.user');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%");
        }

        $guarantors = $query->latest()->paginate(15);
        return view('guarantors.index', compact('guarantors'));
    }

    /**
     * Display Form CF4 for a specific client onboarding.
     */
    public function create(Request $request): View
    {
        $client = Client::with('user')->findOrFail($request->client_id);
        return view('guarantors.create', compact('client'));
    }

    /**
     * Requirement #7: Business cash flow and balance sheet information.
     * Stores the complete Form CF4 Assessment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:Business Owner,Employee,With Collateral',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'relationship' => 'required|string',
            'address' => 'required|string',

            // PDF Section II: Identity
            'sex' => 'required|in:M,F',
            'marital_status' => 'required|string',
            'date_of_birth' => 'required|date|before:-18 years',
            'dependent_persons' => 'required|integer|min:0',
            'job_sector' => 'nullable|string',
            'date_of_visit_business' => 'nullable|date',
            'date_of_visit_residence' => 'nullable|date',

            // Conditional Validation based on Type (Section III vs IV)
            'monthly_sales' => 'required_if:type,Business Owner|numeric|nullable',
            'cost_of_sales' => 'required_if:type,Business Owner|numeric|nullable',
            'operational_expenses' => 'required_if:type,Business Owner|numeric|nullable',
            'net_monthly_income' => 'required_if:type,Employee|numeric|nullable',
            'employer_name' => 'required_if:type,Employee|string|nullable',
            'employer_address' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $validated) {

            // Logic: Perform Assessment Calculations automatically
            $businessFinancials = null;
            if ($request->type === 'Business Owner') {
                $grossProfit = $request->monthly_sales - $request->cost_of_sales;
                $netProfit = $grossProfit - $request->operational_expenses;

                $businessFinancials = [
                    'monthly_sales' => $request->monthly_sales,
                    'cost_of_sales' => $request->cost_of_sales,
                    'gross_profit' => $grossProfit,
                    'net_profit' => $netProfit,
                    'total_assets' => $request->total_assets ?? 0,
                    'total_liabilities' => $request->total_liabilities ?? 0,
                ];
            }

            $guarantor = Guarantor::create(array_merge($validated, [
                'business_financials' => $businessFinancials,
            ]));

            // Req #5: Log the verification action
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'guarantor_assessment_completed',
                'description' => "Completed CF4 Assessment for {$request->name} (Guarantor for Client ID: {$request->client_id})",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('clients.show', $request->client_id)
                ->with('success', 'Guarantor Assessment Form CF4 finalized and verified.');
        });
    }

    /**
     * Requirement #8: Show Assessment for review or adjustment.
     */
    public function show(Guarantor $guarantor): View
    {
        $guarantor->load('client.user');
        return view('guarantors.show', compact('guarantor'));
    }

    public function edit(Guarantor $guarantor): View
    {
        return view('guarantors.edit', compact('guarantor'));
    }

    public function update(Request $request, Guarantor $guarantor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'net_monthly_income' => 'numeric',
        ]);

        $guarantor->update($validated);

        return redirect()->route('clients.show', $guarantor->client_id)
            ->with('success', 'Guarantor assessment records updated.');
    }

    public function destroy(Guarantor $guarantor): RedirectResponse
    {
        // Safety check: Don't allow delete if linked to active loan
        $hasActiveLoan = \App\Models\Loan::where('guarantor_id', $guarantor->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($hasActiveLoan) {
            return back()->with('error', 'Cannot remove guarantor while an active loan proposal is pending.');
        }

        $guarantor->delete();
        return back()->with('success', 'Guarantor record archived.');
    }
}