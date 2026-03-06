<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;

class KycApiController extends Controller
{
    /**
     * GET /api/onboarding/kyc/status
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $isCompleted = $user->hasCompletedKyc();

        return response()->json([
            'kyc_completed' => $isCompleted,
            'client_profile_exists' => $user->client()->exists(),
            'guarantors_count' => $user->client ? $user->client->guarantors()->count() : 0,
        ]);
    }

    /**
     * POST /api/onboarding/kyc
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->hasCompletedKyc()) {
            return response()->json(['message' => 'KYC is already fully completed.'], 400);
        }

        $request->validate([
            'national_id' => 'required|string|unique:clients,national_id',
            'bvn' => 'required|digits:11|unique:clients,bvn',
            'address' => 'required|string',
            'income' => 'required|numeric',
            'date_of_birth' => 'required|date|before:-18 years',
            'employment_status' => 'nullable|string',
            // Default Guarantor info if provided during onboarding
            'g_name' => 'required|string',
            'g_phone' => 'required|string',
            'g_relationship' => 'required|string',
            'g_address' => 'required|string',
            'g_sex' => 'nullable|in:M,F',
            'g_marital_status' => 'nullable|string',
            'g_dob' => 'nullable|date|before:-18 years',
            'g_type' => 'nullable|in:Business Owner,Employee,With Collateral',
            'g_income' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request, $user) {
            // 1. Create or Update Client Profile
            $client = Client::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'national_id' => $request->national_id,
                    'bvn' => $request->bvn,
                    'address' => $request->address,
                    'income' => $request->income,
                    'date_of_birth' => $request->date_of_birth,
                    'employment_status' => $request->employment_status ?? 'Self-Employed',
                ]
            );

            // 2. Create the Guarantor
            $client->guarantors()->create([
                'name' => $request->g_name,
                'phone' => $request->g_phone,
                'relationship' => $request->g_relationship,
                'address' => $request->g_address,
                'sex' => $request->g_sex ?? 'M',
                'marital_status' => $request->g_marital_status ?? 'Single',
                'date_of_birth' => $request->g_dob ?? now()->subYears(20),
                'type' => $request->g_type ?? 'Employee',
                'net_monthly_income' => $request->g_income ?? 0,
            ]);
        });

        return response()->json([
            'message' => 'KYC Onboarding completed successfully.',
            'kyc_completed' => true
        ], 201);
    }
}
