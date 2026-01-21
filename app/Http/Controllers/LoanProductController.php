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
    public function index(): View
    {
        $products = LoanProduct::all();
        return view('loan_products.index', compact('products'));
    }

    public function create(): View
    {
        return view('loan_products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loan_products,name',
            'interest_rate' => 'required|numeric|min:0',
            'penalty_rate' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'duration_months' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        LoanProduct::create($validated);

        return redirect()->route('loan-products.index')
            ->with('success', 'Loan product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(LoanProduct $loanProduct): View
    {
        return view('loan_products.edit', compact('loanProduct'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, LoanProduct $loanProduct): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loan_products,name,' . $loanProduct->id,
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
            'action' => 'product_updated',
            'description' => "Updated loan product: {$loanProduct->name}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('loan-products.index')
            ->with('success', 'Loan product configuration updated.');
    }

    /**
     * Deactivate a product (optional).
     */
    public function destroy(LoanProduct $loanProduct): RedirectResponse
    {
        $loanProduct->update(['status' => 'inactive']);
        return back()->with('success', 'Product has been deactivated.');
    }
}
