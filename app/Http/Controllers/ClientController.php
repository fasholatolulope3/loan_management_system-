<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('user')->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client (Staff side).
     */
    public function create()
    {
        return view('clients.create');
    }

    public function edit(Client $client): \Illuminate\View\View
    {
        // Eager load the user to ensure we have the login details
        $client->load('user');
        return view('clients.edit', compact('client'));
    }
    /**
     * Store a staff-created client and user account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // User Table Fields
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            // Client Table Fields
            'national_id' => 'required|string|unique:clients,national_id',
            'bvn' => 'required|digits:11|unique:clients,bvn',
            'address' => 'required|string',
            'income' => 'required|numeric|min:0',
            'date_of_birth' => 'required|date|before:-18 years',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // 1. Create the User Account
            // We set a default password. In production, you'd send an email to the client to set theirs.
            $password = 'Password123';

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($password),
                'role' => 'client',
                'status' => 'active',
            ]);

            // 2. Create the Client Profile
            $client = Client::create([
                'user_id' => $user->id,
                'national_id' => $validated['national_id'],
                'bvn' => $validated['bvn'],
                'address' => $validated['address'],
                'income' => $validated['income'],
                'date_of_birth' => $validated['date_of_birth'],
                'employment_status' => 'Verified By Staff',
            ]);

            // 3. Log the Activity
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'client_onboarded',
                'description' => "Staff onboarded new client: {$user->name} (ID: {$client->id})",
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('clients.index')
                ->with('success', "Client '{$user->name}' onboarded successfully. Default Password: {$password}");
        });
    }
    public function update(Request $request, Client $client)
    {
        // 1. Advanced Validation with "Ignore current ID"
        $validated = $request->validate([
            // USER TABLE FIELDS: check uniqueness but ignore this user's current ID
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->user_id,
            'phone' => 'required|string|unique:users,phone,' . $client->user_id,

            // CLIENT TABLE FIELDS: check uniqueness but ignore this client record's current ID
            'national_id'       => 'required|string|unique:clients,national_id,' . $client->id,
            'bvn'               => 'required|digits:11|unique:clients,bvn,' . $client->id,
            'income'            => 'required|numeric|min:0',
            'address'           => 'required|string',
            'employment_status' => 'required|string',
        ]);

        // 2. Perform the update in a Transaction for safety
        return DB::transaction(function () use ($validated, $client) {

            // Update the User Account
            $client->user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            // Update the Client Profile
            $client->update([
                'national_id'       => $validated['national_id'],
                'bvn'               => $validated['bvn'],
                'income'            => $validated['income'],
                'address'           => $validated['address'],
                'employment_status' => $validated['employment_status'],
            ]);

            return redirect()->route('clients.show', $client)
                ->with('success', 'Client profile updated successfully.');
        });
    }
    public function show(Client $client)
    {
        $client->load(['user', 'loans.product', 'guarantors']);
        return view('clients.show', compact('client'));
    }
}
