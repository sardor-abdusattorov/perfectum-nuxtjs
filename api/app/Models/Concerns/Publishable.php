<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\PreviewToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * The same question `published()` asks of the table, asked of a row already
     * in hand. A model that narrows the scope narrows this too, so the two
     * never drift apart.
     */
    public function isPublished(): bool
    {
        return $this->getAttribute('status') === true;
    }

    /**
     * Reachable, but only by someone who was handed the address — no listing
     * links here and no search engine should keep it.
     *
     * Only the record types that were given the switch carry the column, so the
     * rest answer no rather than throwing for an attribute they never had.
     */
    public function isByLinkOnly(): bool
    {
        if (! array_key_exists('by_link', $this->getAttributes())) {
            return false;
        }

        return $this->by_link === true && ! $this->isPublished();
    }

    /**
     * A preview token names one record for one day, which is short for a story
     * that waits weeks on someone's approval. `by_link` is the other door: the
     * record opens for anyone holding the address and for as long as the switch
     * is on, while every listing still passes it by.
     *
     * Models without the column read null here and keep the token as their only
     * way in.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field ??= $this->getRouteKeyName();

        $published = static::query()->published()->where($field, $value)->first();

        if ($published !== null) {
            return $published;
        }

        $hidden = static::query()->where($field, $value)->first();

        if ($hidden === null) {
            return null;
        }

        if ($hidden->isByLinkOnly()) {
            return $hidden;
        }

        return (PreviewToken::requested() && PreviewToken::allows($hidden)) ? $hidden : null;
    }
}
