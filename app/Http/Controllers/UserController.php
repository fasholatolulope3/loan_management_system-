<?php

namespace App\Http\Controllers;

use App\Models\{User, AuditLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'role' => 'required|in:admin,officer', // Only allow adding staff
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Staff member added successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'   => 'required|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update($validated);
        return back()->with('success', 'User updated.');
    }
    public function hasCompletedKyc(): bool
    {
        // A user is verified if they have a client record and the national_id is filled
        return $this->client()->exists() && !empty($this->client->national_id);
    }

    public function destroy(User $user)
    {
        if (!$user->canBeDeleted()) {
            return back()->with('error', 'This client has active financial obligations and cannot be removed.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
