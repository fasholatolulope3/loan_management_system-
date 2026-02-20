<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    public function create()
    {
        return view('kyc.create');
    }

    public function store(Request $request)
    {
        // UNCOMMENT the line below to test if data reaches here. 
        // If you see a black screen with data after clicking submit, the form IS working.
        // dd($request->all()); 

        $request->validate([
            'national_id' => 'required|string|unique:clients,national_id',
            'bvn' => 'required|digits:11|unique:clients,bvn',
            'address' => 'required|string',
            'income' => 'required|numeric',
            'date_of_birth' => 'required|date|before:-18 years',
            'g_name' => 'required|string',
            'g_phone' => 'required|string',
            'g_relationship' => 'required|string',
            'g_address' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = auth()->user();

            // 1. Create or Update Client Profile
            $client = \App\Models\Client::updateOrCreate(
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
                'sex' => $request->g_sex ?? 'F', // Default or add to form
                'marital_status' => $request->g_marital_status ?? 'Single',
                'date_of_birth' => $request->g_dob ?? now()->subYears(20),
                'type' => $request->g_type ?? 'Employee',
                'net_monthly_income' => $request->g_income ?? 0,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Onboarding complete!');
    }
}
