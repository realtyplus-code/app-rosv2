<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Buscar el email en ambas tablas
        $user = User::where('email', $request->email)->first();
        $provider = Provider::where('email', $request->email)->first();

        if ($user) {
            $broker = 'users';
        } elseif ($provider) {
            $broker = 'providers';
        } else {
            return back()->withErrors(['email' => __('No encontramos un usuario con ese email.')]);
        }

        // Enviar el enlace de recuperación
        $response = Password::broker($broker)->sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with(['status' => __($response)])
            : back()->withErrors(['email' => __($response)]);
    }
}
