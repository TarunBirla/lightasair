<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ─── Customer Pages ───────────────────────────────────────────────────────

    public function login()
    {
        return view('front.auth.login');
    }

    public function register()
    {
        return view('front.auth.register');
    }

    public function registerSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:customer,vendor',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'active',
        ]);

        // If registering as vendor, create a pending vendor profile
        if ($request->role === 'vendor') {
            VendorProfile::create([
                'user_id'         => $user->id,
                'business_name'   => $request->company_name ?? $request->name,
                'approval_status' => 'pending',
            ]);

            Auth::login($user);
            return redirect('/vendor/pending')->with('success', 'Welcome! Your vendor account is pending admin approval.');
        }

        Auth::login($user);
        return redirect('/')->with('success', 'Welcome to Light As Air Marketplace!');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->with('error', 'Your account has been suspended. Please contact support.');
            }

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            }

            if ($user->isVendor()) {
                $profile = $user->vendorProfile;
                if ($profile && $profile->isApproved()) {
                    return redirect('/vendor/dashboard');
                }
                return redirect('/vendor/pending');
            }

            return redirect('/');
        }

        return back()->with('error', 'Invalid email or password. Please try again.');
    }

    public function profile()
    {
        return view('front.auth.profile', ['user' => Auth::user()]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'You have been logged out.');
    }

    // ─── Vendor Pending Page ──────────────────────────────────────────────────

    public function vendorPending()
    {
        $user = Auth::user();
        if (!$user || !$user->isVendor()) {
            return redirect('/');
        }
        return view('front.auth.vendor-pending', ['user' => $user]);
    }
}