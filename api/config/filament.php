<?php

return [

    'broadcasting' => [

    ],

    'default_filesystem_disk' => env('FILESYSTEM_DISK', 'local'),

    'temporary_file_url_expiry_minutes' => 30,

    'assets_path' => 'vendor',

    'cache_path' => base_path('bootstrap/cache/filament'),

    'livewire_loading_delay' => 'default',

    'file_generation' => [
        'flags' => [],
    ],

    'system_route_prefix' => 'filament',

];
