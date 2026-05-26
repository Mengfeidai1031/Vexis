<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloquea acceso al sistema cuando setting `modo_mantenimiento` está activo.
 * Excepciones: usuarios Super Admin y rutas de auth (para que puedan entrar).
 */
class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Setting::get('modo_mantenimiento', false)) {
            return $next($request);
        }

        // Permitir rutas de auth (login/logout) y assets
        $allowed = ['login', 'logout', 'register', 'password.*'];
        $routeName = $request->route()?->getName();
        foreach ($allowed as $pattern) {
            if ($routeName && fnmatch($pattern, $routeName)) {
                return $next($request);
            }
        }

        // Super Admin siempre puede pasar
        if (Auth::check() && Auth::user()->hasRole('Super Admin')) {
            return $next($request);
        }

        return response()->view('errors.maintenance', [], 503);
    }
}
