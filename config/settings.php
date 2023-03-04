<?php

return [
    'bytes-in-mb' => 1048576,

    'mail-to'     => explode(',', env('EMAIL_SEND_TO', [])),
    'telegram-to' => explode(',', env('TELEGRAM_BOT_TOKEN', [])),
];
