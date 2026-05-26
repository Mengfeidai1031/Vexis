<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        // Compatibilidad con configuración antigua (clave única)
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', null),
        'api_version' => env('GEMINI_API_VERSION', null),

        // Claves separadas por uso (Fase 5)
        'chatbot' => [
            'api_key' => env('GEMINI_CHATBOT_API_KEY', env('GEMINI_API_KEY')),
            'project' => env('GEMINI_CHATBOT_PROJECT', 'projects/264757639401'),
        ],
        'pretasacion' => [
            'api_key' => env('GEMINI_PRETASACION_API_KEY', env('GEMINI_API_KEY')),
            'project' => env('GEMINI_PRETASACION_PROJECT', 'projects/1036016335421'),
        ],
    ],

];
