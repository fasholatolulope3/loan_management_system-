<?php

namespace App\Http\Controllers;

use App\Models\{User, Client};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};

class ClientController extends Controller
{
    public function index()
    {
        // Eager load the 'user' relationship
        $clients = Client::with('user')->latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'national_id' => 'required|unique:clients,national_id',
            'income' => 'required|numeric|min:0',
            'address' => 'required|string',
            'date_of_birth' => 'required|date|before:-18 years',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make('password123'), // Default password
                'role' => 'client',
            ]);

            $user->client()->create([
                'national_id' => $validated['national_id'],
                'income' => $validated['income'],
                'address' => $validated['address'],
                'employment_status' => 'Employed',
                'date_of_birth' => $validated['date_of_birth'],
            ]);
        });

        return redirect()->route('clients.index')->with('success', 'Client onboarded successfully.');
    }

    public function show(Client $client)
    {
        $client->load(['user', 'loans', 'guarantors']);
        return view('clients.show', compact('client'));
    }
}
