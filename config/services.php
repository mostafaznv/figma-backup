<?php

return [
    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', 'TELEGRAM_BOT_TOKEN')
    ],

    'figma' => [
        'email'    => env('FIGMA_EMAIL', 'FIGMA_EMAIL'),
        'password' => env('FIGMA_PASSWORD', 'FIGMA_PASSWORD'),
        'token'    => env('FIGMA_TOKEN', 'FIGMA_TOKEN')
    ]
];
