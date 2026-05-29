<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active'
        ]);

        return redirect('/login');
    }

    public function loginSubmit(Request $request)
    {
        if(Auth::attempt([
            'email'=>$request->email,
            'password'=>$request->password,
            'role'=>'user'
        ]))
        {
            return redirect('/');
        }

        return back()->with(
            'error',
            'Invalid Credentials'
        );
    }

    public function profile()
    {
        return view('front.auth.profile');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}