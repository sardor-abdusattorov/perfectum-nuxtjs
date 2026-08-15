<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function saved(Page $page): void
    {
        clear_pages_cache($page->getOriginal('slug') ?? $page->slug);
        clear_pages_cache($page->slug);
        Cache::forget(Page::redirectsCacheKey());
    }

    public function deleted(Page $page): void
    {
        clear_pages_cache($page->slug);
        Cache::forget(Page::redirectsCacheKey());
    }
}
