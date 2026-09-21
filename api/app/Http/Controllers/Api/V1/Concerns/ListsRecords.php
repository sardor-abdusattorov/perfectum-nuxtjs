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
     * @param  array<int, string>  $searchable  own columns, or `relation.column`
     */
    protected function paginate(Builder $query, Request $request, array $searchable = []): LengthAwarePaginator
    {
        $search = $this->searchTerm($request);

        return $query
            ->when($searchable !== [] && $search !== '', function (Builder $builder) use ($searchable, $search): void {
                $builder->where(function (Builder $inner) use ($searchable, $search): void {
                    foreach ($searchable as $column) {
                        if (! str_contains($column, '.')) {
                            $inner->orWhere($this->searchedIn($inner, $column), 'like', "%{$search}%");

                            continue;
                        }

                        [$relation, $field] = explode('.', $column, 2);

                        $inner->orWhereHas(
                            $relation,
                            fn (Builder $related) => $related->where($this->searchedIn($related, $field), 'like', "%{$search}%"),
                        );
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
     * Переводимая колонка лежит в базе целым JSON — {"ru":"…","uz":"…"} — и
     * поиск по ней подстрокой отвечал не то: запрос «ru» совпадал с ключом и
     * возвращал вообще все записи, а русскоязычный посетитель находил записи
     * по узбекскому тексту, которого не видит. Ищем внутри своей локали.
     */
    private function searchedIn(Builder $query, string $column): string
    {
        $model = $query->getModel();

        $translatable = method_exists($model, 'getTranslatableAttributes')
            ? $model->getTranslatableAttributes()
            : [];

        return in_array($column, $translatable, true)
            ? $column.'->'.app()->getLocale()
            : $column;
    }

    private function searchTerm(Request $request): string
    {
        $search = trim(mb_substr($this->scalar($request, 'search'), 0, 100));

        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search);
    }

    private function perPage(Request $request): int
    {
        $perPage = (int) $this->scalar($request, 'per_page');

        return min($perPage > 0 ? $perPage : self::PER_PAGE, self::MAX_PER_PAGE);
    }

    private function scalar(Request $request, string $key, string $default = ''): string
    {
        $value = $request->input($key, $default);

        return is_scalar($value) ? (string) $value : $default;
    }
}
