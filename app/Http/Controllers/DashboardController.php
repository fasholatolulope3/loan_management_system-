<?php

namespace App\Http\Controllers;

use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\{Loan, Client, User, Payment};

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'officer' => $this->officerDashboard(),
            'client' => $this->clientDashboard(),
            default => abort(403),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'total_disbursed' => (float) Payment::where('type', 'disbursement')->sum('amount_paid'),
            'total_repayments' => (float) Payment::where('type', 'repayment')->where('verification_status', 'verified')->sum('amount_paid'),
            'pending_verifications' => Payment::where('type', 'repayment')->where('verification_status', 'pending')->count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'pending_loans' => Loan::where('status', 'pending')->count(),
            'arrears_count' => LoanSchedule::where('status', '!=', 'paid')->where('due_date', '<', now())->count(),
            'recent_loans' => Loan::with('client.user', 'product')->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }

    private function officerDashboard()
    {
        $user = Auth::user();
        $centerId = $user->collation_center_id;

        $stats = [
            'total_disbursed' => (float) Payment::where('type', 'disbursement')
                ->whereHas('loan', fn($q) => $q->where('collation_center_id', $centerId))
                ->sum('amount_paid'),
            'total_repayments' => (float) Payment::where('type', 'repayment')
                ->where('verification_status', 'verified')
                ->whereHas('loan', fn($q) => $q->where('collation_center_id', $centerId))
                ->sum('amount_paid'),
            'active_loans' => Loan::where('status', 'active')->where('collation_center_id', $centerId)->count(),
            'pending_loans' => Loan::where('status', 'pending')->where('collation_center_id', $centerId)->count(),
            'arrears_count' => LoanSchedule::where('status', '!=', 'paid')
                ->where('due_date', '<', now())
                ->whereHas('loan', fn($q) => $q->where('collation_center_id', $centerId))
                ->count(),
            'recent_loans' => Loan::with('client.user', 'product')->where('collation_center_id', $centerId)->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }



    private function clientDashboard()
    {
        $user = auth()->user();
        $client = $user->client;

        if (!$client) {
            return view('dashboard.complete-profile', ['message' => 'Profile incomplete.']);
        }

        // 1. Funds Received (Total Principal disbursed to the client)
        $fundsReceived = Payment::where('type', 'disbursement')
            ->whereHas('loan', fn($q) => $q->where('client_id', $client->id))
            ->sum('amount_paid');

        // 2. Total Repaid (Verified payments the client has sent back)
        $totalRepaid = Payment::where('type', 'repayment')
            ->where('verification_status', 'verified')
            ->whereHas('loan', fn($q) => $q->where('client_id', $client->id))
            ->sum('amount_paid');

        // 3. Outstanding Balance (The amount remaining in their active schedules)
        $totalBalance = LoanSchedule::whereHas('loan', function ($q) use ($client) {
            $q->where('client_id', $client->id)->where('status', 'active');
        })
            ->where('status', 'pending')
            ->sum('total_due');

        // 4. Next Upcoming Installment
        $nextInstallment = LoanSchedule::whereHas('loan', function ($q) use ($client) {
            $q->where('client_id', $client->id)->where('status', 'active');
        })
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->first();

        // The key "total_balance" is now explicitly added to this array
        $stats = [
            'funds_received' => $fundsReceived,
            'total_repaid' => $totalRepaid,
            'total_balance' => $totalBalance, // This was missing
            'upcoming_payment' => $nextInstallment,
        ];

        return view('dashboard.client', compact('stats'));
    }


    /**
     * Display system settings (Admin only)
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Display financial reports (Admin only)
     */
    public function reports(): View
    {
        $user = Auth::user();
        $isStaff = $user->role === 'officer';
        $centerId = $user->collation_center_id;

        // Base Queries scoped by role/center
        $loanQuery = Loan::query();
        $paymentQuery = Payment::query();
        $arrearsQuery = LoanSchedule::where('status', '!=', 'paid')->where('due_date', '<', now());

        if ($isStaff && $centerId) {
            $loanQuery->where('collation_center_id', $centerId);
            $paymentQuery->whereHas('loan', fn($q) => $q->where('collation_center_id', $centerId));
            $arrearsQuery->whereHas('loan', fn($q) => $q->where('collation_center_id', $centerId));
        }

        $stats = [
            'total_disbursed' => (float) $paymentQuery->where('type', 'disbursement')->sum('amount_paid'),
            'total_collected' => (float) $paymentQuery->where('type', 'repayment')->sum('amount_paid'),
            'active_loans' => $loanQuery->where('status', 'active')->count(),
            'pending_reviews' => $loanQuery->where('status', 'pending')->count(),
            'arrears_count' => $arrearsQuery->count(),
            'recent_loans' => $loanQuery->with('client.user', 'product')->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }
}
