<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SiteTranslation;

class SiteTranslationObserver
{
    public function saved(SiteTranslation $translation): void
    {
        clear_translator_cache();
    }

    public function deleted(SiteTranslation $translation): void
    {
        clear_translator_cache();
    }
}
