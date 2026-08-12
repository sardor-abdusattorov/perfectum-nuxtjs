<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    public function saved(Category $category): void
    {
        clear_categories_cache();
    }

    public function deleted(Category $category): void
    {
        clear_categories_cache();
    }
}
