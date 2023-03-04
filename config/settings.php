<?php

return [
    'mail-to'     => explode(',', env('EMAIL_SEND_TO', [])),
    'telegram-to' => explode(',', env('TELEGRAM_BOT_TOKEN', [])),
];
