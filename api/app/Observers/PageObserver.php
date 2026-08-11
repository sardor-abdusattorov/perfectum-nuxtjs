<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Page;

class PageObserver
{
    public function saved(Page $page): void
    {
        clear_pages_cache($page->getOriginal('slug') ?? $page->slug);
        clear_pages_cache($page->slug);
    }

    public function deleted(Page $page): void
    {
        clear_pages_cache($page->slug);
    }
}
