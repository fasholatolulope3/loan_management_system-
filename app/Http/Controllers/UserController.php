<?php

namespace App\Http\Controllers;

use App\Models\{User, CollationCenter, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth, DB};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Requirement #6: Separate Admin interface for staff management.
     * Requirement #5: Access to Collation Center data.
     */
    public function index(Request $request): View
    {
        $query = User::with('collationCenter');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // Eager load collationCenter to show branch names in the table
        $users = $query->latest()->paginate(15);
        $centers = CollationCenter::all();

        return view('admin.users.index', compact('users', 'centers'));
    }

    /**
     * Requirement #5: Admin assigning Collation Centers to new officers.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'role' => 'required|in:admin,officer',
            'collation_center_id' => 'required|exists:collation_centers,id',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'collation_center_id' => $validated['collation_center_id'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        // Req #5: Log the authority trail
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'staff_created',
            'description' => "Admin created {$user->role} ({$user->name}) and assigned to branch: " . $user->collationCenter->name,
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('users.index')->with('success', "Staff member '{$user->name}' deployed successfully.");
    }

    /**
     * Requirement #8: Status update and review cycle.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'collation_center_id' => 'nullable|exists:collation_centers,id'
        ]);

        $user->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'user_updated',
            'description' => "Updated credentials/status for User #{$user->id}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'User profile updated successfully.');
    }

    /**
     * Requirement: Block deletion of clients with active debt.
     */
    public function destroy(User $user): RedirectResponse
    {
        // 1. Safety Guard: Do not delete yourself
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Security Policy Error: You cannot delete your own account.');
        }

        // 2. Logic: canBeDeleted check (Uses the method in User.php model)
        if (!$user->canBeDeleted()) {
            return back()->with('error', 'Transaction Safety Error: This client has active financial obligations and cannot be removed.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Credentials and access revoked.');
    }
}