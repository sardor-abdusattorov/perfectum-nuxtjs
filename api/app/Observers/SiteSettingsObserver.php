<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SiteSettings;

class SiteSettingsObserver
{
    public function saved(SiteSettings $setting): void
    {
        clear_site_settings_cache();
    }

    public function deleted(SiteSettings $setting): void
    {
        clear_site_settings_cache();
    }
}
