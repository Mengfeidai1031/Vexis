<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = strtolower((string) $request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::channel('security')->warning('auth.login.throttled', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'retry_after_seconds' => $seconds,
            ]);
            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Inténtalo de nuevo en {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            Log::channel('security')->info('auth.login.success', [
                'user_id' => Auth::id(),
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);

            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        Log::channel('security')->warning('auth.login.failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro
     */
    public function register(Request $request)
    {
        $throttleKey = 'register|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::channel('security')->warning('auth.register.throttled', [
                'ip' => $request->ip(),
                'retry_after_seconds' => $seconds,
            ]);
            throw ValidationException::withMessages([
                'email' => "Demasiados registros desde esta IP. Inténtalo de nuevo en {$seconds} segundos.",
            ]);
        }
        RateLimiter::hit($throttleKey, 600);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()],
        ]);

        $user = User::create([
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'empresa_id' => Empresa::first()?->id,
            'departamento_id' => Departamento::first()?->id,
            'centro_id' => Centro::first()?->id,
        ]);

        // Registro público: SIEMPRE rol Cliente (solo accede a módulo /cliente).
        $user->assignRole('Cliente');

        Auth::login($user);
        $request->session()->regenerate();
        Log::channel('security')->info('auth.register.success', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('cliente.inicio')->with('success', 'Cuenta creada correctamente. Bienvenido a VEXIS.');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::channel('security')->info('auth.logout', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect('/');
    }
}
