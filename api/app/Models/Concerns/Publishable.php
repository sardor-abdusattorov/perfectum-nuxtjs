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
     * Route binding never reaches an unpublished record, so a draft is a 404
     * for the site without every controller checking for it.
     *
     * The one way past is a preview token naming that very record: the editor
     * who wrote it, or the client asked to approve it, opens the real page
     * before it is published. The lookup only runs when a token is offered, and
     * only the record the token names comes back — a listing never widens.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field ??= $this->getRouteKeyName();

        $published = static::query()->published()->where($field, $value)->first();

        if ($published !== null || ! PreviewToken::requested()) {
            return $published;
        }

        $draft = static::query()->where($field, $value)->first();

        return ($draft !== null && PreviewToken::allows($draft)) ? $draft : null;
    }
}
