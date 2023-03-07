<?php

return [
    'bytes-in-mb' => 1048576,
    'bytes-in-kb' => 1024,

    'file-expiry-days' => 10,

    'mail-to'     => explode(',', env('EMAIL_SEND_TO', [])),
    'telegram-to' => explode(',', env('TELEGRAM_SEND_TO', [])),
];
