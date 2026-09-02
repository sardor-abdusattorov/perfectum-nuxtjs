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
        return Network::tryFrom($this->scalar($request, 'network'));
    }

    /**
     * `%` and `_` are LIKE wildcards: left as they are, a single character
     * would ask the database to scan every row of every searchable column.
     */
    private function searchTerm(Request $request): string
    {
        $search = trim(mb_substr($this->scalar($request, 'search'), 0, 100));

        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search);
    }

    /**
     * Anything that is not a positive number asks for the default page, not
     * for a page of one.
     */
    private function perPage(Request $request): int
    {
        $perPage = (int) $this->scalar($request, 'per_page');

        return min($perPage > 0 ? $perPage : self::PER_PAGE, self::MAX_PER_PAGE);
    }

    /**
     * A filter arrives in the query string from the site and in the JSON body
     * from the app; the request reads both. A body can also carry an array or
     * an object where a string was expected — that is no filter at all.
     */
    private function scalar(Request $request, string $key, string $default = ''): string
    {
        $value = $request->input($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }
}
