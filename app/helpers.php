<?php

declare(strict_types=1);

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Helper global para obtener/establecer settings desde Blade o PHP.
     * Uso: setting('clave') | setting('clave', 'default') | setting('clave', null, 'value_to_set').
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}
