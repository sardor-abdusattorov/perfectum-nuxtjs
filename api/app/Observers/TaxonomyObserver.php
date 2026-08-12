<?php

declare(strict_types=1);

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class TaxonomyObserver
{
    public function saved(Model $taxonomy): void
    {
        clear_taxonomy_cache($taxonomy::class);
    }

    public function deleted(Model $taxonomy): void
    {
        clear_taxonomy_cache($taxonomy::class);
    }
}
