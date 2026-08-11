<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Settings;

class SettingsObserver
{
    public function saved(Settings $setting): void
    {
        clear_settings_cache();
    }

    public function deleted(Settings $setting): void
    {
        clear_settings_cache();
    }
}
