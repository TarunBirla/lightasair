<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to access your vendor dashboard.');
        }

        if (!Auth::user()->isVendor()) {
            return redirect('/')->with('error', 'Access restricted to vendors only.');
        }

        // Check if vendor profile is approved
        $profile = Auth::user()->vendorProfile;

        if (!$profile || !$profile->isApproved()) {
            return redirect('/vendor/pending')->with('info', 'Your vendor account is pending admin approval.');
        }

        if (Auth::user()->status === 'suspended') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account has been suspended. Please contact support.');
        }

        return $next($request);
    }
}
