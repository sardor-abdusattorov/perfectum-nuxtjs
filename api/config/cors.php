<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    /*
     * Read from other sites on purpose — the old site published both.
     */
    'public_paths' => [
        'api/v1/documents',
        'api/v1/coverage',
        'api/v1/coverage/*',
    ],

    'allowed_methods' => ['*'],

    /*
     * Only the site may read the API from a browser; the paths above are the
     * exception, App\Http\Middleware\HandleApiCors reopens them.
     *
     * The fallback is the Nuxt dev server, never APP_URL — the API's own
     * address is never the origin a browser asks from, so defaulting to it
     * blocked every request the site made.
     */
    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('FRONTEND_URL', 'http://localhost:3000,http://127.0.0.1:3000'))
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
