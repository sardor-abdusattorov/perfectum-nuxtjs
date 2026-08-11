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

    private const MAX_PER_PAGE = 48;

    /**
     * @param  array<int, string>  $searchable
     */
    protected function paginate(Builder $query, Request $request, array $searchable = []): LengthAwarePaginator
    {
        $search = trim((string) $request->query('search', ''));

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

    private function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', (string) self::PER_PAGE);

        return max(1, min($perPage, self::MAX_PER_PAGE));
    }
}
