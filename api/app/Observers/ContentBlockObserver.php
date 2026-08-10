<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ContentBlock;

class ContentBlockObserver
{
    public function saved(ContentBlock $block): void
    {
        clear_content_blocks_cache($block->page);
    }

    public function deleted(ContentBlock $block): void
    {
        clear_content_blocks_cache($block->page);
    }
}
