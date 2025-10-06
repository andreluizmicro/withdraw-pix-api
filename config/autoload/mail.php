<?php

declare(strict_types=1);

use function Hyperf\Support\env;

return [
    'default' => env('MAIL_MAILER', 'mailers.smtp'),


    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 25),
            'encryption' => null,
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', null),
            'scheme' => 'smtp',
        ],

        'log' => [
            'transport' => 'log',
            'group' => env('MAIL_LOG_GROUP', 'default'),
            'name' => env('MAIL_LOG_NAME', 'mail'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'name' => env('MAIL_FROM_NAME', 'My App'),
    ],

    'markdown' => [
        'theme' => env('MAIL_MARKDOWN_THEME', 'default'),
        'paths' => [
            BASE_PATH . '/storage/views/mail',
        ],
    ],
];
