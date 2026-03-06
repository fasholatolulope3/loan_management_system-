<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileApiController extends Controller
{
    /**
     * PATCH /api/profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|max:255|unique:users,phone,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh()
        ]);
    }

    /**
     * DELETE /api/profile
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if (!$user->canBeDeleted()) {
            return response()->json(['message' => 'Cannot delete profile due to active financial obligations.'], 403);
        }

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Profile deleted successfully.']);
    }
}
