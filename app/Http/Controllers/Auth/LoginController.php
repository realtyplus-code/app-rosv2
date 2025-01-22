<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required_without:user', 'email'],
            'user' => ['required_without:email'],
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user';
        $credentials = [
            $loginType => $request->input($loginType),
            'password' => $request->input('password'),
        ];

        // Intentar autenticación para usuarios
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/users');
        }

        // Intentar autenticación para proveedores
        if (Auth::guard('proveedores')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/providers');
        }

        return back()->withErrors([
            'login' => 'The credentials do not match.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('proveedores')->check()) {
            Auth::guard('proveedores')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
