<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Provider\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function resetPassword($user, $password)
    {
        // Actualizar la contraseña
        $user->password = Hash::make($password);
        $user->save();
        
        // Iniciar sesión automáticamente después del reset
        auth()->guard($this->getGuard($user))->login($user);

        // Redirigir según el tipo de usuario
        $redirectPath = '/home';

        // Redirigir al usuario después del reset
        return redirect($redirectPath)->with('status', __('Tu contraseña ha sido restablecida con éxito.'));
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        // Buscar el email en ambas tablas
        $user = User::where('email', $request->email)->first();
        $broker = 'users';

        if (!$user) {
            $provider = Provider::where('email', $request->email)->first();
            if ($provider) {
                $user = $provider;
                $broker = 'providers';
            } else {
                return back()->withErrors(['email' => __('No encontramos un usuario con ese email.')]);
            }
        }

        // Intentar resetear la contraseña con el broker correcto
        $response = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }

    protected function getGuard($user)
    {
        return $user instanceof Provider ? 'providers' : 'web';
    }
}
