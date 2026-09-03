<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'billing' => [
        'url' => env('BILLING_API_URL', 'https://api-dmz.perfectum.uz/api/bss/v1'),
        'token' => env('BILLING_API_TOKEN'),
        'timeout' => env('BILLING_API_TIMEOUT', 30),
        'client_ip_header' => env('BILLING_CLIENT_IP_HEADER', 'X-Forwarded-For'),

        'endpoints' => [
            'msisdns' => '/msisdns',
            'msisdns_categories' => '/msisdns-category',
        ],
    ],

    'cdma' => [
        'url' => env('CDMA_API_URL', 'http://pk.cdma.uz'),
        'timeout' => env('CDMA_API_TIMEOUT', 20),
        'connect_timeout' => env('CDMA_API_CONNECT_TIMEOUT', 5),

        'endpoints' => [
            'numbers' => '/PC_PERF/load_function.php',
            'prices' => '/PC/load_function.php',
        ],
    ],

];
