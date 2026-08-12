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
        'mobilnaya-svya' => Network::FiveG,
        'domasnii-internet' => Network::FiveG,
    ];

    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/taxonomies.json')), true);

        $this->seed(TariffCategory::class, $data['tariff_categories'] ?? []);
        $this->seed(TariffType::class, $this->rankDescending($data['tariff_types'] ?? []));
        $this->seed(ServiceCategory::class, $data['service_types'] ?? []);
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
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function seed(string $model, array $rows): void
    {
        foreach ($rows as $row) {
            $values = [
                'name' => $row['name'],
                'network' => (self::NETWORKS[$row['slug']] ?? Network::Both)->value,
                'sort' => $row['sort'] ?? 0,
                'status' => $row['status'] ?? true,
            ];

            $model::updateOrCreate(['slug' => $row['slug']], $values);
        }
    }
}
