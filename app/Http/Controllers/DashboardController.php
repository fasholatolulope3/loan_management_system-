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
            'total_disbursed' => Loan::where('status', 'active')->sum('amount'),
            'total_clients'   => Client::count(),
            'pending_loans'   => Loan::where('status', 'pending')->count(),
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

        // Graceful handling if the client profile hasn't been created yet
        if (!$client) {
            return view('dashboard.complete-profile', [
                'message' => 'Your account is active, but your client profile is incomplete.'
            ]);
        }

        $stats = [
            'active_loans'     => Loan::where('client_id', $client->id)
                ->where('status', 'active')->get(),
            'total_balance'    => Loan::where('client_id', $client->id)
                ->where('status', 'active')->sum('amount'),
            'upcoming_payment' => $client->loans()
                ->with(['schedules' => fn($q) => $q->where('status', 'pending')
                    ->orderBy('due_date', 'asc')])->first(),
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
