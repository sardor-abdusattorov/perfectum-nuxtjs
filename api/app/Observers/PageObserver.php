<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    /**
     * The whole table is forgotten rather than the one row: a page also appears
     * as a card on its parent, and the parent's payload is a separate cache
     * entry that nothing else would invalidate. There are a few dozen pages, so
     * the difference is a handful of `forget` calls on a rare admin action.
     */
    public function saved(Page $page): void
    {
        $this->forget($page);
    }

    public function deleted(Page $page): void
    {
        $this->forget($page);
    }

    private function forget(Page $page): void
    {
        clear_pages_cache($page->getOriginal('slug') ?? $page->slug);
        clear_pages_cache();
        Cache::forget(Page::redirectsCacheKey());
    }
}
