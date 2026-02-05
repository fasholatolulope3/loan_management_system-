<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ReportController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        $query = LoanSchedule::with(['loan.client.user', 'loan.collationCenter']);

        // 1. Repayment Search
        if ($request->reference) {
            $query->whereHas('loan.payments', fn($q) => $q->where('reference', $request->reference));
        }

        // 2. Arrears logic (Repayments passed due date and not paid)
        if ($request->type === 'arrears') {
            $query->where('due_date', '<', now())->where('status', '!=', 'paid');
        }

        $reports = $query->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

}
