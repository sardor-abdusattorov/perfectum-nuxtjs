<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Concerns;

use Illuminate\Http\Request;

trait OmitsBodyFromLists
{
    /**
     * A listing renders cards built from the title and the excerpt; the editor
     * HTML is read only on the record's own page, and it is by far the heaviest
     * field, so it ships from the show endpoint alone.
     */
    protected function body(Request $request, mixed $value): mixed
    {
        return $this->when($request->routeIs('*.show'), $value);
    }
}
