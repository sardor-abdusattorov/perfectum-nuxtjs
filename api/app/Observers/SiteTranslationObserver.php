<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\SiteTranslation;

class SiteTranslationObserver
{
    public function saved(SiteTranslation $translation): void
    {
        clear_translator_cache($translation->category, $translation->key);
    }

    public function deleted(SiteTranslation $translation): void
    {
        clear_translator_cache($translation->category, $translation->key);
    }
}
