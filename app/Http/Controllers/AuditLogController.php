<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter by user if requested
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action (e.g., 'loan_approved')
        if ($request->action) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest()->paginate(25);

        return view('admin.audit_logs.index', compact('logs'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.audit_logs.show', compact('auditLog'));
    }
}
