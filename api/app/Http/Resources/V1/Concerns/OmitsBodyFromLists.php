<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Concerns;

use Illuminate\Http\Request;

trait OmitsBodyFromLists
{
    protected function body(Request $request, mixed $value): mixed
    {
        return $this->when($request->routeIs('*.show'), $value);
    }
}
