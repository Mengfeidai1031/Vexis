<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleEnabled
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $key = "modulo_{$module}";
        if (! Setting::get($key, true)) {
            abort(403, 'Este módulo está desactivado.');
        }

        return $next($request);
    }
}
