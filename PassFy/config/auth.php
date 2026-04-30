<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => 'usuarios',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        // ADMIN / USUÁRIOS DO SISTEMA
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Mantém o padrão do Laravel
        ],

        'usuario' => [
            'driver' => 'session',
            'provider' => 'usuarios',
        ],

        // CLIENTES
        'cliente' => [
            'driver' => 'session',
            'provider' => 'clientes',
        ],

        // ORGANIZADORAS
        'organizadora' => [
            'driver' => 'session',
            'provider' => 'organizadoras',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [

        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'usuarios' => [
            'driver' => 'eloquent',
            'model' => App\Models\Usuario::class,
        ],

        'clientes' => [
            'driver' => 'eloquent',
            'model' => App\Models\Cliente::class,
        ],

        'organizadoras' => [
            'driver' => 'eloquent',
            'model' => App\Models\Organizadora::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */
    'passwords' => [

        'usuarios' => [
            'provider' => 'usuarios',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'clientes' => [
            'provider' => 'clientes',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'organizadoras' => [
            'provider' => 'organizadoras',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,

];