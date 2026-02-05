<?php

namespace App\Http\Controllers;

use App\Models\LoanProduct;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoanProductController extends Controller
{
    /**
     * Requirement #2: Three specific loan categories (Daily, Weekly, Monthly)
     */
    public function index(): View
    {
        $products = LoanProduct::latest()->get();
        return view('loan_products.index', compact('products'));
    }

    public function create(): View
    {
        return view('loan_products.create');
    }

    /**
     * Requirement #1, #2, #3 Implementation.
     */
    /**
     * Store a newly established Loan Product.
     * Implements Requirements #1, #2, and #3 with strict business rule validation.
     */
    public function store(Request $request): RedirectResponse
    {
        // Requirement #3: Fixed Rate Map (Daily: 10, Weekly: 20, Monthly: 30)
        $requiredRates = [
            'Daily' => 10.00,
            'Weekly' => 20.00,
            'Monthly' => 30.00
        ];

        $validated = $request->validate([
            // Req #2: Explicit Categories
            'name' => 'required|string|in:Daily,Weekly,Monthly|unique:loan_products,name',

            // Generic numeric validation (we check exact value below)
            'interest_rate' => 'required|numeric',

            // Req #1: Defaulting to 0.005 (handled in UI, enforced here)
            'penalty_rate' => 'required|numeric|min:0.005',

            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'duration_months' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        // STRICTOR CHECK: Ensure Requirement #3 is followed exactly
        if ((float) $validated['interest_rate'] !== (float) $requiredRates[$validated['name']]) {
            return back()->withErrors([
                'interest_rate' => "Per policy, a {$validated['name']} loan must carry an interest rate of exactly {$requiredRates[$validated['name']]}%."
            ])->withInput();
        }

        // 1. Atomic Persistence
        $product = LoanProduct::create($validated);

        // 2. Req #5: Log Action by Approved Authority
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'loan_product_creation',
            'description' => "Established {$product->name} category. Rules: Rate={$product->interest_rate}%, Penalty={$product->penalty_rate}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('loan-products.index')
            ->with('success', "Official Product Rules for '{$product->name}' at {$product->interest_rate}% have been established.");
    }

    public function edit(LoanProduct $loanProduct): View
    {
        return view('loan_products.edit', compact('loanProduct'));
    }

    /**
     * Updates and logs product modification.
     */
    public function update(Request $request, LoanProduct $loanProduct): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|in:Daily,Weekly,Monthly|unique:loan_products,name,' . $loanProduct->id,
            'interest_rate' => 'required|numeric|min:0',
            'penalty_rate' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'duration_months' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $loanProduct->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'loan_product_updated',
            'description' => "Re-configured product terms for: {$loanProduct->name}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('loan-products.index')
            ->with('success', 'Credit product updated in the registry.');
    }

    /**
     * Requirement #5: Soft deactivation of products.
     */
    public function destroy(LoanProduct $loanProduct): RedirectResponse
    {
        // Safety: Don't deactivate if there are pending/active loans
        $inUse = \App\Models\Loan::where('loan_product_id', $loanProduct->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($inUse) {
            return back()->with('error', 'Cannot deactivate a product currently in an active or pending cycle.');
        }

        $loanProduct->update(['status' => 'inactive']);
        return back()->with('success', 'Loan product status updated to Inactive.');
    }
}