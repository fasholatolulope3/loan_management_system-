<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{LoanProduct, AuditLog, Loan};
use Illuminate\Support\Facades\Auth;

class LoanProductApiController extends Controller
{
    /**
     * POST /api/admin/loan-products
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:loan_products,name',
            'interest_rate' => 'required|numeric|min:0',
            'penalty_rate' => 'required|numeric|min:0.005',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'installment_count' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $product = LoanProduct::create($validated);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'loan_product_creation',
            'description' => "Established {$product->name} via API.",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'message' => "Official Product Rules for '{$product->name}' established.",
            'product' => $product
        ], 201);
    }

    /**
     * PUT /api/admin/loan-products/{loan_product}
     */
    public function update(Request $request, LoanProduct $loanProduct)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:loan_products,name,' . $loanProduct->id,
            'interest_rate' => 'required|numeric|min:0',
            'penalty_rate' => 'required|numeric|min:0',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'installment_count' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $loanProduct->update($validated);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'loan_product_updated',
            'description' => "Re-configured product terms for: {$loanProduct->name} via API.",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Credit product updated.',
            'product' => $loanProduct
        ]);
    }

    /**
     * DELETE /api/admin/loan-products/{loan_product}
     */
    public function destroy(LoanProduct $loanProduct)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $inUse = Loan::where('loan_product_id', $loanProduct->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($inUse) {
            return response()->json(['message' => 'Cannot deactivate a product currently in an active or pending cycle.'], 422);
        }

        $loanProduct->update(['status' => 'inactive']);

        return response()->json(['message' => 'Loan product status updated to Inactive.']);
    }
}
