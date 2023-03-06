<?php

return [
    'server' => 'Nginx',

    'base-path' => env('X_SENDFILE_BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../'),

    'cache'                 => true,
    'cache-control-max-age' => 2592000
];
