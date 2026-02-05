<?php

namespace App\Http\Controllers;

use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use App\Models\{Loan, Client, User, Payment};

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin'   => $this->adminDashboard(),
            'officer' => $this->officerDashboard(),
            'client'  => $this->clientDashboard(),
            default   => abort(403),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            // 1. Capital Deployment (Money leaving the company)
            'total_disbursed' => Payment::where('type', 'disbursement')
                ->sum('amount_paid'),

            // 2. Revenue Collection (Money successfully returned)
            'total_repayments' => Payment::where('type', 'repayment')
                ->where('verification_status', 'verified')
                ->sum('amount_paid'),

            // 3. Unverified Revenue (Money clients claim to have paid, awaiting staff check)
            'pending_reviews' => Payment::where('type', 'repayment')
                ->where('verification_status', 'pending')
                ->count(),

            // 4. Operational Portfolio (Total unique borrowers)
            'total_clients'   => Client::count(),

            // 5. Workload Queue (The missing key that caused the crash)
            'pending_loans'   => Loan::where('status', 'pending')->count(),

            // 6. User Activity Feed (For the table at the bottom)
            'recent_users'    => User::latest()->take(5)->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function officerDashboard()

    {
        $stats = [
            'my_pending_approvals' => Loan::where('status', 'pending')->count(),
            'active_loans'         => Loan::where('status', 'active')->count(),
            'today_payments'       => Payment::whereDate('payment_date', now())->sum('amount_paid'),
        ];
        return view('dashboard.officer', compact('stats'));
    }

    // app/Http/Controllers/DashboardController.php

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
            'funds_received'   => $fundsReceived,
            'total_repaid'     => $totalRepaid,
            'total_balance'    => $totalBalance, // This was missing
            'upcoming_payment' => $nextInstallment,
        ];

        return view('dashboard.client', compact('stats'));
    }
    // app/Http/Controllers/DashboardController.php

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
    public function reports()
    {
        $reportData = [
            'total_loans' => Loan::count(),
            'active_principal' => Loan::where('status', 'active')->sum('amount'),
            'total_collected' => Payment::sum('amount_paid'),
            'repayment_rate' => LoanSchedule::count() > 0
                ? (LoanSchedule::where('status', 'paid')->count() / LoanSchedule::count()) * 100
                : 0,
        ];

        return view('admin.reports', compact('reportData'));
    }
}
