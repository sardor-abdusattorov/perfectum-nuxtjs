<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\PageSettings;
use Illuminate\Support\Facades\Cache;

class PageSettingsObserver
{
    public function saved(PageSettings $settings): void
    {
        $this->forget();
    }

    public function deleted(PageSettings $settings): void
    {
        $this->forget();
    }

    private function forget(): void
    {
        foreach (app_locales() as $locale) {
            Cache::forget(PageSettings::cacheKey($locale));
        }
    }
}
