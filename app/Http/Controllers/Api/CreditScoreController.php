<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\CreditScoreService;
use Illuminate\Support\Facades\Auth;

class CreditScoreController extends Controller
{
    public function __construct(private CreditScoreService $scorer)
    {
    }

    /**
     * GET /api/clients/{client}/credit-score
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

        $result = $this->scorer->calculate($client);

        return response()->json([
            'client_id' => $client->id,
            'client' => $client->user?->name,
            'data' => $result,
        ]);
    }
}
