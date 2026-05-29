<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }


public function loginSubmit(Request $request)
{
    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    if(Auth::attempt($credentials))
    {
        if(Auth::user()->role != 'admin')
        {
            Auth::logout();

            return back()->with(
                'error',
                'Only Admin Allowed'
            );
        }

        return redirect('/admin/dashboard');
    }

    return back()->with(
        'error',
        'Invalid Login Credentials'
    );
}

    public function logout()
    {
        Auth::logout();

        return redirect('/admin/login');
    }
}