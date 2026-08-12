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

    private const TYPE_CATEGORIES = [
        'qulay-ezemesyacnye' => 'cdma',
        'qulay-polugodovye' => 'cdma',
        'specialnye-tarify' => 'cdma',
        'polugodovye-6k' => 'cdma',
        'dlya-fiziceskix-lic' => 'domasnii-internet',
        'dlya-yuridiceskix-lic' => 'domasnii-internet',
        'bez-pokupki-routera' => 'domasnii-internet',
        '5g-standalone' => 'mobilnaya-svya',
    ];

    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/taxonomies.json')), true);

        $this->seed(TariffCategory::class, $data['tariff_categories'] ?? []);
        $this->seed(TariffType::class, $this->rankDescending($data['tariff_types'] ?? []));
        $this->seed(ServiceCategory::class, $data['service_types'] ?? []);

        $categories = TariffCategory::query()->pluck('id', 'slug');

        foreach (self::TYPE_CATEGORIES as $type => $category) {
            TariffType::query()->where('slug', $type)->update(['category_id' => $categories[$category] ?? null]);
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
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function seed(string $model, array $rows): void
    {
        foreach ($rows as $row) {
            $values = [
                'name' => $row['name'],
                'sort' => $row['sort'] ?? 0,
                'status' => $row['status'] ?? true,
            ];

            if ($model !== TariffType::class) {
                $values['network'] = (self::NETWORKS[$row['slug']] ?? Network::Both)->value;
            }

            $model::updateOrCreate(['slug' => $row['slug']], $values);
        }
    }
}
