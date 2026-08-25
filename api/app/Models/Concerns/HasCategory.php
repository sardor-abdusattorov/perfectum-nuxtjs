<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Network;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasCategory
{
    /**
     * @return class-string
     */
    abstract public static function categoryModel(): string;

    public function category(): BelongsTo
    {
        return $this->belongsTo(static::categoryModel());
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

    /**
     * A listing is filtered by the slug in the address — that is what a link
     * carries and what survives a reseed. An id still works: it is what the
     * panel and older links pass.
     */
    public function scopeInCategory(Builder $query, mixed $category, string $relation = 'category'): Builder
    {
        if (blank($category)) {
            return $query;
        }

        if (is_numeric($category)) {
            return $query->where($this->{$relation}()->getForeignKeyName(), $category);
        }

        return $query->whereHas($relation, fn (Builder $builder) => $builder->where('slug', $category));
    }
}
