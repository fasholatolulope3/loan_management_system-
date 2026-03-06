<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, AuditLog};
use Illuminate\Support\Facades\{Hash, Auth};
use Illuminate\Validation\Rules\Password;

class UserApiController extends Controller
{
    /**
     * GET /api/admin/users
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $query = User::with('collationCenter')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        return response()->json($query->paginate(15));
    }

    /**
     * POST /api/admin/users
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'role' => 'required|in:admin,officer',
            'collation_center_id' => 'required|exists:collation_centers,id',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'collation_center_id' => $validated['collation_center_id'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'staff_created',
            'description' => "Admin created {$newUser->role} ({$newUser->name}) via API.",
            'ip_address' => $request->ip()
        ]);

        return response()->json(['message' => 'Staff profile created.', 'user' => $newUser->load('collationCenter')], 201);
    }

    /**
     * GET /api/admin/users/{user}
     */
    public function show(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        return response()->json($user->load(['collationCenter', 'auditLogs' => fn($q) => $q->latest()->limit(50)]));
    }

    /**
     * PATCH /api/admin/users/{user}
     */
    public function update(Request $request, User $user)
    {
        $adminUser = Auth::user();
        if ($adminUser->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,inactive,suspended',
            'collation_center_id' => 'nullable|exists:collation_centers,id'
        ]);

        $user->update($validated);

        AuditLog::create([
            'user_id' => $adminUser->id,
            'action' => 'user_updated',
            'description' => "Updated status/details for API User #{$user->id}",
            'ip_address' => $request->ip()
        ]);

        return response()->json(['message' => 'User profile updated.', 'user' => $user->fresh('collationCenter')]);
    }

    /**
     * DELETE /api/admin/users/{user}
     */
    public function destroy(User $user)
    {
        $adminUser = Auth::user();
        if ($adminUser->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        if ($adminUser->id === $user->id) {
            return response()->json(['message' => 'Security Error: You cannot delete your own account.'], 422);
        }

        if (!$user->canBeDeleted()) {
            return response()->json(['message' => 'Transaction Safety Error: User has active financial obligations and cannot be removed.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Credentials and access revoked.']);
    }
}
