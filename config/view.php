<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as usual, you are free to change this value.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Relative Hash
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, a hash will be generated based on the
    | relative path of the view file. This allows for better cache busting
    | when views are moved between environments. However, it may cause
    | issues in some cases if views are referenced outside of the normal
    | application structure.
    |
    */

    'relative_hash' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Timestamps
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Laravel will check the last modified
    | timestamp of the view files to determine if they need to be recompiled.
    | This can be useful during development to ensure views are always fresh.
    |
    */

    'check_cache_timestamps' => true,

    /*
    |--------------------------------------------------------------------------
    | Compiled Extension
    |--------------------------------------------------------------------------
    |
    | This option determines the file extension for compiled Blade templates.
    | By default, Laravel uses '.php' as the extension. You may change this
    | if you prefer a different extension.
    |
    */

    'compiled_extension' => 'php',

    /*
    |--------------------------------------------------------------------------
    | View Cache
    |--------------------------------------------------------------------------
    |
    | This option determines whether compiled views should be cached. When
    | enabled, Laravel will cache compiled views to improve performance.
    |
    */

    'cache' => env('VIEW_CACHE', true),

];
