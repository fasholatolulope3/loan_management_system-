<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientApiController extends Controller
{
    /**
     * GET /api/clients
     * List clients — Admin/Officer only.
     * Officers see only clients from their collation center.
     */
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized. Only admins or officers can view client lists.'], 403);
        }

        $query = Client::with(['user', 'loans', 'guarantors'])->latest();

        if ($user->role === 'officer') {
            $query->whereHas('user', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return response()->json($query->paginate(15));
    }

    /**
     * GET /api/clients/{client}
     * View a client's full profile — Admin/Officer only.
     */
    public function show(Client $client)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Officers can only view clients from their center
        if ($user->role === 'officer' && $client->user?->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $client->load([
            'user',
            'loans.product',
            'loans.schedules',
            'loans.payments',
            'guarantors',
            'documents',
        ]);

        return response()->json(['data' => $client]);
    }
}
