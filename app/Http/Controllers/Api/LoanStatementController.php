<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LoanStatementController extends Controller
{
    /**
     * GET /api/loans/{loan}/statement
     * Download a PDF loan statement.
     */
    public function download(Loan $loan)
    {
        $user = Auth::user();

        // Clients can only download their own statements
        if ($user->role === 'client') {
            $client = $user->client;
            if (!$client || $loan->client_id !== $client->id) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
        }

        // Officers scoped to their collation center
        if ($user->role === 'officer' && $loan->collation_center_id !== $user->collation_center_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $loan->load(['client.user', 'product', 'schedules', 'payments', 'approver']);

        $pdf = Pdf::loadView('pdf.loan-statement', compact('loan'))
            ->setPaper('a4', 'portrait');

        $filename = 'loan-statement-' . str_pad($loan->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
