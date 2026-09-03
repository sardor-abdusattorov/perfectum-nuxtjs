<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\PreviewToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait CountsViews
{
    private const VIEW_COOLDOWN = 3600;

    /**
     * One reader is one view for the hour that follows, so a reload, a step
     * back and the second render a client-side navigation asks for do not each
     * add one. An editor checking a draft through a preview link is not a
     * reader at all and is left out.
     */
    public function registerView(Request $request): void
    {
        if (PreviewToken::requested()) {
            return;
        }

        $key = 'views.'.$this->getMorphClass().'.'.$this->getKey().'.'.($request->ip() ?? 'unknown');

        if (! Cache::add($key, true, self::VIEW_COOLDOWN)) {
            return;
        }

        $this->newQuery()->whereKey($this->getKey())->increment('views');

        $this->views = (int) $this->views + 1;
    }

    public function scopeMostViewed(Builder $query): Builder
    {
        return $query->orderByDesc('views');
    }
}
