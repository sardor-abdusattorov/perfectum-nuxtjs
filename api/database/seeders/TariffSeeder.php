<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tariff;
use App\Models\TariffCategory;
use App\Models\TariffFile;
use App\Models\TariffType;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/taxonomies.json')), true);
        $tariffs = json_decode((string) file_get_contents(database_path('data/tariffs.json')), true);

        $categories = $this->map(TariffCategory::class, $data['tariff_categories'] ?? []);
        $types = $this->map(TariffType::class, $data['tariff_types'] ?? []);

        foreach ($tariffs as $sort => $row) {
            if (blank($row['slug'])) {
                continue;
            }

            Tariff::updateOrCreate(['slug' => $row['slug']], [
                'category_id' => $categories[$row['category_id']] ?? null,
                'type_id' => $types[$row['type_id']] ?? null,
                'name' => $row['name'],
                'price' => $row['price'],
                'price_currency' => $row['price_currency'],
                'price_period' => $row['price_period'],
                'connection_cost' => $row['connection_cost'],
                'features' => $row['features'],
                'descriptions' => $row['descriptions'],
                'image' => $row['image'],
                'ussd' => $row['ussd'],
                'modal_image' => $row['modal_image'],
                'buttons' => $row['buttons'],
                'is_featured' => $row['is_featured'],
                'is_archived' => $row['is_archived'],
                'sort' => $sort + 1,
                'status' => $row['status'],
            ]);
        }

        TariffFile::updateOrCreate(
            ['file' => 'files/uRsM6pVwPFGmwSZpLOLv9VrDtX3Dbki92ppKbJes.pdf'],
            ['name' => '#архивные ТП 2025.pdf', 'sort' => 1],
        );
    }

    /**
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, int>
     */
    private function map(string $model, array $rows): array
    {
        $bySlug = $model::query()->pluck('id', 'slug')->all();

        return collect($rows)
            ->mapWithKeys(fn (array $row): array => [$row['id'] => $bySlug[$row['slug']] ?? null])
            ->filter()
            ->all();
    }
}
