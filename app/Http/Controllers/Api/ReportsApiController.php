<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Payment, LoanSchedule, Loan};
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\{PaymentResource, LoanResource, LoanScheduleResource};

class ReportsApiController extends Controller
{
    /**
     * GET /api/reports/collections
     * Admin/Officer daily collections report.
     */
    public function collections(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $query = Payment::with(['loan.client.user', 'schedule'])
            ->where('type', 'repayment')
            ->latest('paid_at');

        if ($user->role === 'officer') {
            $query->whereHas('loan', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        // Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('paid_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('paid_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('date')) { // Specific day e.g. today
            $query->whereDate('paid_at', $request->date);
        } else {
            $query->whereDate('paid_at', today());
        }

        $collections = $query->paginate(20);
        $totalCollected = $query->sum('amount_paid');

        return PaymentResource::collection($collections)->additional([
            'summary' => [
                'total_collected' => (float) $totalCollected,
                'date_range' => $request->date ?? ($request->start_date . ' to ' . $request->end_date ?? 'today')
            ]
        ]);
    }

    /**
     * GET /api/reports/arrears
     * Admin/Officer arrears report.
     */
    public function arrears(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'officer'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $query = LoanSchedule::with('loan.client.user')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc');

        if ($user->role === 'officer') {
            $query->whereHas('loan', fn($q) => $q->where('collation_center_id', $user->collation_center_id));
        }

        $arrears = $query->paginate(20);
        $totalArrears = $query->sum('total_due');

        return LoanScheduleResource::collection($arrears)->additional([
            'summary' => [
                'total_arrears_amount' => (float) $totalArrears,
                'overdue_schedules_count' => $arrears->total()
            ]
        ]);
    }

    /**
     * GET /api/reports/global
     * Admin global summary reports.
     */
    public function global(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $stats = [
            'total_disbursed' => (float) Payment::where('type', 'disbursement')->sum('amount_paid'),
            'total_collected' => (float) Payment::where('type', 'repayment')->sum('amount_paid'),
            'active_loans' => Loan::where('status', 'active')->count(),
            'pending_reviews' => Loan::where('status', 'pending')->count(),
            'arrears_count' => LoanSchedule::where('status', '!=', 'paid')->where('due_date', '<', now())->count(),
        ];

        return response()->json(['data' => $stats]);
    }
}
