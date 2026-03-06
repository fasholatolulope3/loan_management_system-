<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\{Loan, Client, LoanSchedule, LoanProduct, AuditLog};
use App\Notifications\LoanApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class LoanApiController extends Controller
{
    /**
     * GET /api/loans
     * List loans scoped by role.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Loan::with(['client.user', 'product', 'collationCenter'])->latest();

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client)
                return response()->json(['data' => []]);
            $query->where('client_id', $client->id);
        } elseif ($user->role === 'officer') {
            $query->where('collation_center_id', $user->collation_center_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return LoanResource::collection($query->paginate(15));
    }

    /**
     * GET /api/loans/{loan}
     */
    public function show(Loan $loan)
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client || $loan->client_id !== $client->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        if ($user->role === 'officer' && $loan->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $loan->load(['client.user', 'product', 'collationCenter', 'schedules', 'payments', 'collaterals', 'approver']);

        return (new LoanResource($loan))->additional([
            'summary' => [
                'remaining_balance' => $loan->remainingBalance(),
                'total_arrears' => $loan->totalArrearsAmount(),
                'next_installment' => $loan->nextInstallment(),
            ]
        ]);
    }

    /**
     * POST /api/loans
     * Submit a loan application — Client only.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Only clients can apply for loans.'], 403);
        }

        $client = $user->client;
        if (!$client) {
            return response()->json(['message' => 'Client profile not found. Please complete your KYC first.'], 422);
        }

        if (!$user->hasCompletedKyc()) {
            return response()->json(['message' => 'You must complete your KYC (upload ID and add a guarantor) before applying.'], 422);
        }

        $validated = $request->validate([
            'loan_product_id' => ['required', 'exists:loan_products,id'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'installment_count' => ['required', 'integer', 'min:1', 'max:120'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'guarantor_id' => ['nullable', 'exists:guarantors,id'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'business_location' => ['nullable', 'string', 'max:255'],
        ]);

        $product = LoanProduct::findOrFail($validated['loan_product_id']);

        $loan = DB::transaction(function () use ($validated, $client, $product, $user) {
            $loan = Loan::create([
                'client_id' => $client->id,
                'collation_center_id' => $user->collation_center_id,
                'loan_product_id' => $validated['loan_product_id'],
                'guarantor_id' => $validated['guarantor_id'] ?? null,
                'amount' => $validated['amount'],
                'interest_rate' => $product->interest_rate,
                'installment_count' => $validated['installment_count'],
                'start_date' => $validated['start_date'],
                'status' => 'pending',
                'approval_status' => 'pending',
                'business_name' => $validated['business_name'] ?? null,
                'business_location' => $validated['business_location'] ?? null,
            ]);

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'loan_application_submitted',
                'description' => "Loan application of {$validated['amount']} submitted via API.",
                'model_type' => Loan::class,
                'model_id' => $loan->id,
            ]);

            return $loan;
        });

        return (new LoanResource($loan->load('product', 'client.user')))
            ->response()
            ->setStatusCode(201)
            ->header('Content-Type', 'application/json');
    }

    /**
     * POST /api/loans/{loan}/approve
     * Approve a loan — Admin/Officer only.
     */
    public function approve(Loan $loan)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
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

        return new LoanResource($loan->fresh(['client.user', 'approver']));
    }
}
