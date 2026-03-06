<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AuditLog, LoanProduct};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiscApiController extends Controller
{
    /**
     * GET /api/audit-logs
     * Audit trail — Admin only.
     */
    public function auditLogs(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }

        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(25);

        return response()->json($logs);
    }

    /**
     * GET /api/loan-products
     * Public listing of available loan products.
     */
    public function loanProducts()
    {
        $products = LoanProduct::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'interest_rate', 'min_amount', 'max_amount', 'max_duration']);

        return response()->json(['data' => $products]);
    }
}
