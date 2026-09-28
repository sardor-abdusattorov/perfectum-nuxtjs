<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'frontend_url' => env('FRONTEND_URL', env('APP_URL')),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Display Timezone
    |--------------------------------------------------------------------------
    |
    | Даты хранятся в UTC, а в панели показываются по этому поясу. Так время
    | на экране остаётся ташкентским, а записи в базе не зависят от того, где
    | стоит сервер.
    |
    */

    'display_timezone' => env('APP_DISPLAY_TIMEZONE', 'Asia/Tashkent'),

    'locale' => env('APP_LOCALE', 'ru'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'ru'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Three lists, because they answer three different questions.
    |
    | `locales` is the content: the locale tabs of a translatable field, the
    | keys inside the JSON columns, the locale a public request may ask for by
    | name. This is the list that grows when one more language of content is
    | wanted, and content may be written long before the site shows it. Order
    | counts — the tabs are drawn in it, and Slug takes the first filled
    | translation from it.
    |
    | `panel_locales` is the language of the admin interface. A locale belongs
    | here only once lang/<code> exists, otherwise the panel turns into half
    | Russian, half English.
    |
    | `site_locales` is what the site serves under a URL prefix. It mirrors
    | i18n.locales in frontend/nuxt.config.ts and decides only one thing: the
    | locale of a request that named none. Guessing over the content list
    | instead would answer an English browser in a language the site has no
    | pages for.
    |
    | `panel_locales`, `site_locales` and `required_locales` stay subsets of
    | `locales`, and `locale` belongs to all of them.
    |
    */

    'locales' => ['ru', 'uz', 'en'],

    'panel_locales' => ['ru', 'uz'],

    'site_locales' => ['ru', 'uz'],

    'required_locales' => ['ru', 'uz'],

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
