<?php

return [
    'bytes-in-mb' => 1048576,
    'bytes-in-kb' => 1024,

    'file-expiry-days'       => 10,
    'telegram-max-file-size' => env('TELEGRAM_MAX_FILE_SIZE', 49), // MB

    'mail-to'     => explode(',', env('SEND_WARNING_EMAIL_TO', [])),
    'telegram-to' => [
        'backups'  => explode(',', env('TELEGRAM_BACKUPS_SEND_TO', [])),
        'warnings' => explode(',', env('TELEGRAM_WARNINGS_SEND_TO', [])),
    ],
];
