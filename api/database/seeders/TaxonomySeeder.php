<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Network;
use App\Models\ServiceCategory;
use App\Models\TariffCategory;
use App\Models\TariffType;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    private const NETWORKS = [
        'cdma' => Network::Cdma,
        'mobilnaya-svyaz' => Network::FiveG,
        'domashniy-internet' => Network::FiveG,
    ];

    private const TYPE_CATEGORIES = [
        'qulay-ezhemesyachnye' => 'cdma',
        'qulay-polugodovye' => 'cdma',
        'spetsialnye-tarify' => 'cdma',
        'polugodovye-6k' => 'cdma',
        'dlya-fizicheskih-lits' => 'domashniy-internet',
        'dlya-yuridicheskih-lits' => 'domashniy-internet',
        'bez-pokupki-routera' => 'domashniy-internet',
        '5g-standalone' => 'mobilnaya-svyaz',
    ];

    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/taxonomies.json')), true);

        $categories = $this->seed(TariffCategory::class, $data['tariff_categories'] ?? []);
        $types = $this->seed(TariffType::class, $this->rankDescending($data['tariff_types'] ?? []));
        $this->seed(ServiceCategory::class, $data['service_types'] ?? []);

        foreach (self::TYPE_CATEGORIES as $type => $category) {
            TariffType::query()
                ->whereKey($types[$type] ?? null)
                ->update(['category_id' => $categories[$category] ?? null]);
        }
    }

    /**
     * The old site listed tariff types in descending order, so the chip row
     * started with the newest line. The new site sorts ascending everywhere;
     * re-ranking here keeps the row identical.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function rankDescending(array $rows): array
    {
        return collect($rows)
            ->sortByDesc(fn (array $row): array => [$row['sort'] ?? 0, $row['id'] ?? 0])
            ->values()
            ->map(fn (array $row, int $index): array => [...$row, 'sort' => $index + 1])
            ->all();
    }

    /**
     * The dump identifies a row by its slug, and so does the site: it is the
     * address a filtered listing carries. That makes it the match key here too
     * — renaming a category in the panel no longer forks a second row.
     *
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<string, int>
     */
    private function seed(string $model, array $rows): array
    {
        $ids = [];

        foreach ($rows as $row) {
            $values = [
                'name' => $row['name'],
                'sort' => $row['sort'] ?? 0,
                'status' => $row['status'] ?? true,
            ];

            if ($model !== TariffType::class) {
                $values['network'] = (self::NETWORKS[$row['slug']] ?? Network::Both)->value;
            }

            if ($model === TariffCategory::class) {
                $values['in_catalog'] = $row['in_catalog'] ?? true;
            }

            $ids[$row['slug']] = $model::updateOrCreate(['slug' => $row['slug']], $values)->getKey();
        }

        return $ids;
    }
}
