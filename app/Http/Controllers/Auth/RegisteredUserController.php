<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // Inside the store() method
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20'],
            'national_id' => ['required', 'string', 'max:20', 'unique:clients,national_id'],
            'address' => ['required', 'string', 'max:500'],
            'income' => ['required', 'numeric', 'min:0'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'employment_status' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('Password123'),
            'role' => 'client',
        ]);

        // Create the full client profile using registration data
        $user->client()->create([
            'national_id' => $request->national_id,
            'address' => $request->address,
            'income' => $request->income,
            'employment_status' => $request->employment_status,
            'date_of_birth' => $request->date_of_birth,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
