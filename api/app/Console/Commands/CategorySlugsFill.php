<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ActionCategory;
use App\Models\DeviceCategory;
use App\Models\FaqCategory;
use App\Models\NewsCategory;
use App\Models\ServiceCategory;
use App\Models\TariffCategory;
use App\Models\TariffType;
use App\Support\Slug;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * The site addresses a filtered listing by the category's slug, so the
 * taxonomies grew a slug column. A fresh install gets it from the create
 * migration; a site already carrying content cannot be rebuilt, so this fills
 * it in place.
 *
 * The slug it writes is the one the seeders expect: the data files name every
 * seeded row, and only a row an editor added later falls back to its name. Get
 * that wrong and the next seeding forks a second copy of the category instead
 * of updating it.
 *
 * Safe to run twice — a filled slug is left alone.
 */
final class CategorySlugsFill extends Command
{
    protected $signature = 'categories:fill-slugs {--pretend : only show what would be written}';

    protected $description = 'Add and fill the slug of every category the site filters by';

    public function handle(): int
    {
        foreach ($this->sources() as $model => $slugs) {
            $this->fill($model, $slugs);
        }

        return self::SUCCESS;
    }

    /**
     * @param  class-string<Model>  $model
     * @param  array<string, string>  $slugs
     */
    private function fill(string $model, array $slugs): void
    {
        $table = (new $model)->getTable();
        $pretend = (bool) $this->option('pretend');

        if (! Schema::hasColumn($table, 'slug')) {
            if ($pretend) {
                $this->line("{$table}: колонки slug нет, будет добавлена");
            } else {
                Schema::table($table, fn (Blueprint $blueprint) => $blueprint->string('slug', 96)->nullable()->after('name'));
                $this->info("{$table}: колонка добавлена");
            }
        }

        if ($pretend && ! Schema::hasColumn($table, 'slug')) {
            foreach ($model::query()->get() as $row) {
                $this->line("  {$this->name($row)} -> ".($slugs[$this->name($row)] ?? '(из названия)'));
            }

            return;
        }

        $rows = $model::query()
            ->where(fn ($query) => $query->whereNull('slug')->orWhere('slug', ''))
            ->get();

        foreach ($rows as $row) {
            $name = $this->name($row);
            $slug = $slugs[$name] ?? Slug::make($model, $name, $row->getKey());

            $this->line("  {$name} -> {$slug}");

            if (! $pretend) {
                $row->slug = $slug;
                $row->save();
            }
        }

        if ($rows->isEmpty()) {
            $this->line("{$table}: уже заполнена");
        }

        if (! $pretend) {
            $this->tighten($table);
        }
    }

    /**
     * The create migration spells the column out as required and unique, so a
     * site filled in place ends up with the same shape. Both are refused when
     * they are already in place, which is the ordinary case on a second run.
     */
    private function tighten(string $table): void
    {
        foreach ([
            fn (Blueprint $blueprint) => $blueprint->string('slug', 96)->nullable(false)->change(),
            fn (Blueprint $blueprint) => $blueprint->unique('slug'),
        ] as $step) {
            try {
                Schema::table($table, $step);
            } catch (Throwable) {
                // already required, or already unique
            }
        }
    }

    private function name(Model $row): string
    {
        return (string) $row->getTranslation('name', 'ru');
    }

    /**
     * @return array<class-string<Model>, array<string, string>>
     */
    private function sources(): array
    {
        $taxonomies = $this->data('taxonomies.json');
        $content = $this->data('content.json');

        return [
            TariffCategory::class => $this->byName($taxonomies['tariff_categories'] ?? []),
            TariffType::class => $this->byName($taxonomies['tariff_types'] ?? []),
            ServiceCategory::class => $this->byName($taxonomies['service_types'] ?? []),
            NewsCategory::class => $this->byName($content['news_categories'] ?? []),
            ActionCategory::class => $this->byName($content['action_categories'] ?? []),
            FaqCategory::class => $this->byName($this->data('faqs.json')['categories'] ?? []),
            DeviceCategory::class => [
                'Устройства CDMA' => 'cdma',
                'Устройства 5G SA' => '5g',
                'Роутеры' => 'routers',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function data(string $file): array
    {
        return json_decode((string) file_get_contents(database_path("data/{$file}")), true) ?? [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<string, string>
     */
    private function byName(array $rows): array
    {
        return collect($rows)
            ->mapWithKeys(fn (array $row): array => [$row['name']['ru'] => $row['slug']])
            ->all();
    }
}
