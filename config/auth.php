<?php

use App\Models\User;

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
    ],

    // O projeto usa apenas autenticacao web por sessao.
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    // O provider informa ao Auth qual model representa os usuarios.
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],
    ],
];
