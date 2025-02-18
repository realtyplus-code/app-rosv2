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
            'login' => ['required'],
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user';
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        // Convertir el login a minúsculas para permitir autenticación sin importar mayúsculas o minúsculas
        $credentials[$loginType] = strtolower($credentials[$loginType]);

        // Intentar autenticación para usuarios
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            switch (Auth::user()->getRoleNames()[0]) {
                case 'owner':
                case 'tenant':
                case 'ros_client':
                case 'ros_client_manager':
                case 'global_manager':
                    return redirect()->intended('/home');
                case 'admin':
                    return redirect()->intended('/home');
                default:
                    return;
            }
        }

        // Intentar autenticación para providers
        if (Auth::guard('providers')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
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

        if (Auth::guard('providers')->check()) {
            Auth::guard('providers')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
