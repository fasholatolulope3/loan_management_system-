<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 1. If not logged in, or not a client, just continue
        if (!$user || $user->role !== 'client') {
            return $next($request);
        }

        // 2. EXEMPTION: Allow the user to visit the KYC routes and logout
        // If we don't do this, we get a redirect loop
        if ($request->routeIs('kyc.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // 3. CHECK: If KYC is incomplete, redirect to the KYC form
        if (!$user->hasCompletedKyc()) {
            return redirect()->route('kyc.create')
                ->with('warning', 'Please complete your profile to continue.');
        }

        return $next($request);
    }
}
