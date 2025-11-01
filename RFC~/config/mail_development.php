<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail Configuration for Development
    |--------------------------------------------------------------------------
    |
    | This file contains the mail configuration for development environment.
    | Use this when you don't have a mail server set up.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    'mailers' => [
        'log' => [
            'transport' => 'log',
            'channel' => env('LOG_CHANNEL', 'stack'),
        ],

        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@government-frc.local'),
        'name' => env('MAIL_FROM_NAME', 'Government FRC System'),
    ],
];
