<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Guarantor, Client, AuditLog, Loan};
use Illuminate\Support\Facades\Auth;

class GuarantorApiController extends Controller
{
    /**
     * GET /api/guarantors
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $query = Guarantor::with('client.user')->latest();

        if ($user->role === 'officer') {
            // Scope by collation center of the client
            $query->whereHas('client.user', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return response()->json($query->paginate(15));
    }

    /**
     * POST /api/guarantors
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:Business Owner,Employee,With Collateral',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'relationship' => 'required|string',
            'address' => 'required|string',
            'sex' => 'required|in:M,F',
            'marital_status' => 'required|string',
            'date_of_birth' => 'required|date|before:-18 years',
            'dependent_persons' => 'required|integer|min:0',
            'job_sector' => 'nullable|string',
            'monthly_sales' => 'required_if:type,Business Owner|numeric|nullable',
            'cost_of_sales' => 'required_if:type,Business Owner|numeric|nullable',
            'operational_expenses' => 'required_if:type,Business Owner|numeric|nullable',
            'net_monthly_income' => 'required_if:type,Employee|numeric|nullable',
            'employer_name' => 'required_if:type,Employee|string|nullable',
        ]);

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

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'guarantor_assessment_completed',
            'description' => "Completed CF4 Assessment via API for {$request->name} (Guarantor for Client ID: {$request->client_id})",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'message' => 'Guarantor Assessment Form CF4 finalized.',
            'guarantor' => $guarantor
        ], 201);
    }

    /**
     * GET /api/guarantors/{guarantor}
     */
    public function show(Guarantor $guarantor)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role === 'officer' && $guarantor->client->user->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($guarantor->load('client.user'));
    }

    /**
     * PATCH /api/guarantors/{guarantor}
     */
    public function update(Request $request, Guarantor $guarantor)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role === 'officer' && $guarantor->client->user->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'net_monthly_income' => 'numeric',
        ]);

        $guarantor->update($validated);

        return response()->json([
            'message' => 'Guarantor records updated.',
            'guarantor' => $guarantor
        ]);
    }

    /**
     * DELETE /api/guarantors/{guarantor}
     */
    public function destroy(Guarantor $guarantor)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role === 'officer' && $guarantor->client->user->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $hasActiveLoan = Loan::where('guarantor_id', $guarantor->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($hasActiveLoan) {
            return response()->json(['message' => 'Cannot remove guarantor while an active loan proposal is pending.'], 422);
        }

        $guarantor->delete();

        return response()->json(['message' => 'Guarantor record archived.']);
    }
}
