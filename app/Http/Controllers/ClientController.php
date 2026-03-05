<?php

namespace App\Http\Controllers;

use App\Models\{User, Client, ClientDocument, Guarantor, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Auth};
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     * Logic: Officers only see clients from their own Collation Center.
     */
    public function index(): View
    {
        $user = Auth::user();
        $query = Client::with(['user', 'user.collationCenter']);

        // Requirement #5: Filter by Center for Officers
        if ($user->role === 'officer') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('collation_center_id', $user->collation_center_id);
            });
        }

        $clients = $query->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for onboarding.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Unified Onboarding: User account + KYC Profile + Initial Guarantor.
     * This ensures the client passes the KYC gate immediately.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // User Data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'date_of_birth' => 'required|date|before:-18 years',
            // Client Data
            'national_id' => 'required|string|unique:clients,national_id',
            'bvn' => 'required|digits:11|unique:clients,bvn',
            'address' => 'required|string',
            'income' => 'required|numeric|min:0',
            // Primary Guarantor Data (Requirement: Mandatory at Onboarding)
            'g_name' => 'required|string|max:255',
            'g_phone' => 'required|string|max:20',
            'g_relationship' => 'required|string',
            'g_sex' => 'required|in:M,F',
            'g_address' => 'required|string',
            'g_type' => 'required|string',
            'g_spouse_name' => 'nullable|string|max:255',
            'g_spouse_phone' => 'nullable|string|max:20',
            'g_employer' => 'nullable|string|max:255',
            'g_sector' => 'nullable|string|max:255',
            'g_position' => 'nullable|string|max:255',
            'g_net_income' => 'nullable|numeric|min:0',
            'g_biz_activity' => 'nullable|string|max:255',
            'g_biz_sales' => 'nullable|numeric|min:0',
            // Client Documents
            'nin' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'selfie' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'nepa_bill' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'shop_picture' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'house_picture' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'collateral_document' => 'required|file|mimes:pdf|max:5120',
            'statement_of_account' => 'required|file|mimes:pdf|max:5120',
            'officer_comment' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $staff = Auth::user();

            // 1. Create the User Login
            $password = 'Password123';
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($password),
                'role' => 'client',
                'status' => 'active',
                'collation_center_id' => $staff->collation_center_id, // Inherits current staff center
            ]);

            // 2. Create the Client Profile
            $client = $user->client()->create([
                'officer_id' => $staff->id,
                'national_id' => $validated['national_id'],
                'bvn' => $validated['bvn'],
                'address' => $validated['address'],
                'income' => $validated['income'],
                'date_of_birth' => $validated['date_of_birth'],
                'employment_status' => 'Verified By Staff',
                'officer_comment' => $validated['officer_comment'] ?? null,
            ]);

            // 3. Create the Primary Guarantor (Pre-links for future Loan Proposals)
            $client->guarantors()->create([
                'name' => $validated['g_name'],
                'phone' => $validated['g_phone'],
                'relationship' => $validated['g_relationship'],
                'sex' => $validated['g_sex'],
                'address' => $validated['g_address'],
                'type' => $validated['g_type'],
                'spouse_name' => $validated['g_spouse_name'],
                'spouse_phone' => $validated['g_spouse_phone'],
                'employer_name' => $validated['g_employer'],
                'job_sector' => $validated['g_sector'],
                'position' => $validated['g_position'],
                'net_monthly_income' => $validated['g_net_income'] ?? 0,
                'business_activity' => $validated['g_biz_activity'],
                'avg_monthly_sales' => $validated['g_biz_sales'] ?? 0,
            ]);

            // 4. Store Documents
            $documentTypes = [
                'nin',
                'selfie',
                'nepa_bill',
                'shop_picture',
                'house_picture',
                'collateral_document',
                'statement_of_account'
            ];

            foreach ($documentTypes as $type) {
                if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $path = $file->store('client_documents', 'public');

                    $client->documents()->create([
                        'type' => $type,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            // 5. Log the audit trace (Requirement #5)
            AuditLog::create([
                'user_id' => $staff->id,
                'action' => 'client_full_onboarding',
                'description' => "Officer registered {$user->name} and linked Guarantor {$validated['g_name']} to Center ID: {$staff->collation_center_id}",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('clients.index')
                ->with('success', "Full registration for '{$user->name}' complete. Temporary Password: {$password}");
        });
    }

    public function edit(Client $client): View
    {
        $client->load('user');
        return view('clients.edit', compact('client'));
    }

    /**
     * Transactional Update for Account & KYC profile.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->user_id,
            'phone' => 'required|string|unique:users,phone,' . $client->user_id,
            'national_id' => 'required|string|unique:clients,national_id,' . $client->id,
            'bvn' => 'required|digits:11|unique:clients,bvn,' . $client->id,
            'income' => 'required|numeric|min:0',
            'address' => 'required|string',
            'employment_status' => 'required|string',
        ]);

        return DB::transaction(function () use ($validated, $client, $request) {
            // Update User Login account
            $client->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            // Update Client KYC Profile
            $client->update([
                'national_id' => $validated['national_id'],
                'bvn' => $validated['bvn'],
                'income' => $validated['income'],
                'address' => $validated['address'],
                'employment_status' => $validated['employment_status'],
            ]);

            return redirect()->route('clients.show', $client)
                ->with('success', 'Profile modification synchronized successfully.');
        });
    }

    /**
     * Show the complete Credit Profile (360 Degree View)
     */
    public function show(Client $client): View
    {
        // Check scope security
        if (Auth::user()->role === 'officer' && $client->user->collation_center_id !== Auth::user()->collation_center_id) {
            abort(403, 'Attempting to access client outside your assigned center.');
        }

        $client->load(['user', 'loans.product', 'guarantors', 'user.collationCenter', 'documents']);
        return view('clients.show', compact('client'));
    }
}