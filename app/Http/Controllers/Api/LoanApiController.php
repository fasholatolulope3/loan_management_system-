<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Loan, Client, LoanSchedule, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApiController extends Controller
{
    /**
     * GET /api/loans
     * List loans scoped by role:
     * - Clients: see only their own loans
     * - Officers: see loans from their collation center
     * - Admins: see all loans
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Loan::with(['client.user', 'product', 'collationCenter'])
            ->latest();

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client) {
                return response()->json(['data' => []]);
            }
            $query->where('client_id', $client->id);
        } elseif ($user->role === 'officer') {
            $query->where('collation_center_id', $user->collation_center_id);
        }
        // Admin sees everything

        // Optional status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->paginate(15);

        return response()->json($loans);
    }

    /**
     * GET /api/loans/{loan}
     * View a single loan with its schedules, payments, and collaterals.
     */
    public function show(Loan $loan)
    {
        $user = Auth::user();

        // Clients can only view their own loans
        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client || $loan->client_id !== $client->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        // Officers can only view loans from their collation center
        if ($user->role === 'officer' && $loan->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $loan->load([
            'client.user',
            'product',
            'collationCenter',
            'schedules',
            'payments',
            'collaterals',
            'approver',
        ]);

        return response()->json([
            'data' => $loan,
            'summary' => [
                'remaining_balance' => $loan->remainingBalance(),
                'total_arrears' => $loan->totalArrearsAmount(),
                'next_installment' => $loan->nextInstallment(),
            ]
        ]);
    }

    /**
     * POST /api/loans/{loan}/approve
     * Approve a loan — Admin/Officer only.
     */
    public function approve(Loan $loan)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized. Only admins or officers can approve loans.'], 403);
        }

        if ($loan->status === 'active') {
            return response()->json(['message' => 'This loan is already active.'], 422);
        }

        $loan->update([
            'status' => 'active',
            'approval_status' => 'approved',
            'approved_by' => $user->id,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'loan_approved',
            'description' => "Loan #{$loan->id} approved via API.",
            'model_type' => Loan::class,
            'model_id' => $loan->id,
        ]);

        return response()->json([
            'message' => 'Loan approved successfully.',
            'data' => $loan->fresh(['client.user', 'approver']),
        ]);
    }
}
