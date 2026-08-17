<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Concerns;

use App\Enums\Network;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ListsRecords
{
    private const PER_PAGE = 12;

    private const MAX_PER_PAGE = 100;

    /**
     * @param  array<int, string>  $searchable
     */
    protected function paginate(Builder $query, Request $request, array $searchable = []): LengthAwarePaginator
    {
        $search = $this->searchTerm($request);

        return $query
            ->when($searchable !== [] && $search !== '', function (Builder $builder) use ($searchable, $search): void {
                $builder->where(function (Builder $inner) use ($searchable, $search): void {
                    foreach ($searchable as $column) {
                        $inner->orWhere($column, 'like', "%{$search}%");
                    }
                });
            })
            ->paginate($this->perPage($request))
            ->withQueryString();
    }

    protected function network(Request $request): ?Network
    {
        return Network::tryFrom((string) $request->query('network', ''));
    }

    /**
     * `%` and `_` are LIKE wildcards: left as they are, a single character
     * would ask the database to scan every row of every searchable column.
     */
    private function searchTerm(Request $request): string
    {
        $search = trim(mb_substr((string) $request->query('search', ''), 0, 100));

        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search);
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', (string) self::PER_PAGE);

        return max(1, min($perPage, self::MAX_PER_PAGE));
    }
}
