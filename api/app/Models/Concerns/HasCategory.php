<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Network;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCategory
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A record without a category belongs to the whole site, so it stays in
     * every section.
     */
    public function scopeForNetwork(Builder $query, ?Network $network): Builder
    {
        if ($network === null) {
            return $query;
        }

        return $query->where(
            fn (Builder $builder) => $builder
                ->whereNull('category_id')
                ->orWhereHas('category', fn (Builder $category) => $category->forNetwork($network))
        );
    }

    public function scopeInCategory(Builder $query, ?string $slug, string $relation = 'category'): Builder
    {
        if (blank($slug)) {
            return $query;
        }

        return $query->whereHas($relation, fn (Builder $category) => $category->where('slug', $slug));
    }
}
