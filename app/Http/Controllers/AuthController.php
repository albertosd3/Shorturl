<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::get('authenticated')) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if ($request->password === env('ADMIN_PASSWORD', 'G666')) {
            Session::put('authenticated', true);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    public function logout()
    {
        Session::forget('authenticated');
        return redirect()->route('login');
    }
}