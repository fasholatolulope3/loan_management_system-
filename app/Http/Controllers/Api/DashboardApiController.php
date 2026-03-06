<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Loan, Payment, Client, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    /**
     * GET /api/dashboard
     * Returns stats summary scoped by role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            return $this->clientDashboard($user);
        }

        return $this->staffDashboard($user);
    }

    private function clientDashboard($user)
    {
        $client = $user->client;

        if (!$client) {
            return response()->json(['message' => 'Client profile not found.'], 404);
        }

        $loans = $client->loans();

        return response()->json([
            'total_loans' => $loans->count(),
            'active_loans' => $loans->where('status', 'active')->count(),
            'pending_loans' => $loans->where('status', 'pending')->count(),
            'total_payments' => Payment::whereIn('loan_id', $loans->pluck('id'))->count(),
            'pending_payments' => Payment::whereIn('loan_id', $loans->pluck('id'))->where('status', 'pending')->count(),
        ]);
    }

    private function staffDashboard($user)
    {
        $loanQuery = Loan::query();
        $clientQuery = Client::query();

        if ($user->role === 'officer') {
            $loanQuery->where('collation_center_id', $user->collation_center_id);
            $clientQuery->whereHas('user', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        return response()->json([
            'total_loans' => (clone $loanQuery)->count(),
            'active_loans' => (clone $loanQuery)->where('status', 'active')->count(),
            'pending_loans' => (clone $loanQuery)->where('status', 'pending')->count(),
            'defaulted_loans' => (clone $loanQuery)->where('status', 'defaulted')->count(),
            'total_clients' => $clientQuery->count(),
            'total_disbursed' => (clone $loanQuery)->where('status', 'active')->sum('amount'),
            'total_payments' => Payment::when(
                $user->role === 'officer',
                fn($q) =>
                $q->whereHas('loan', fn($q2) => $q2->where('collation_center_id', $user->collation_center_id))
            )->count(),
            'unverified_payments' => Payment::where('status', 'pending')->when(
                $user->role === 'officer',
                fn($q) =>
                $q->whereHas('loan', fn($q2) => $q2->where('collation_center_id', $user->collation_center_id))
            )->count(),
        ]);
    }
}
