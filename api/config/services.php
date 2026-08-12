<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

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

];
