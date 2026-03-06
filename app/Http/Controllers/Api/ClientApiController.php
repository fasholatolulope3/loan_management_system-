<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientApiController extends Controller
{
    /**
     * GET /api/clients
     */
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $query = Client::with(['user', 'loans', 'guarantors'])->latest();

        if ($user->role === 'officer') {
            $query->whereHas('user', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return ClientResource::collection($query->paginate(15));
    }

    /**
     * GET /api/clients/{client}
     */
    public function show(Client $client)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role === 'officer' && $client->user?->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $client->load(['user', 'loans.product', 'loans.schedules', 'loans.payments', 'guarantors', 'documents']);

        return new ClientResource($client);
    }
    /**
     * POST /api/clients
     * Officer/Admin creates a client profile.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:clients,user_id',
            'national_id' => 'required|string|unique:clients,national_id',
            'bvn' => 'required|digits:11|unique:clients,bvn',
            'address' => 'required|string',
            'income' => 'required|numeric',
            'date_of_birth' => 'required|date|before:-18 years',
            'employment_status' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'message' => 'Client profile recorded successfully.',
            'client' => new ClientResource($client->load('user'))
        ], 201);
    }

    /**
     * PATCH /api/clients/{client}
     */
    public function update(Request $request, Client $client)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role === 'officer' && $client->user->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'national_id' => 'sometimes|string|unique:clients,national_id,' . $client->id,
            'bvn' => 'sometimes|digits:11|unique:clients,bvn,' . $client->id,
            'address' => 'sometimes|string',
            'income' => 'sometimes|numeric',
            'employment_status' => 'sometimes|string',
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Client profile updated.',
            'client' => new ClientResource($client->fresh('user'))
        ]);
    }

    /**
     * DELETE /api/clients/{client}
     */
    public function destroy(Client $client)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $hasActiveLoan = \App\Models\Loan::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($hasActiveLoan) {
            return response()->json(['message' => 'Cannot remove a client with active or pending loans.'], 422);
        }

        $client->delete();

        return response()->json(['message' => 'Client profile archived.']);
    }
}
