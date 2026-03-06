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
}
