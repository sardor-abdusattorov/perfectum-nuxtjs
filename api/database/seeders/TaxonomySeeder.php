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

        $this->seed(TariffCategory::class, $data['tariff_categories'] ?? [], withCode: true);
        $this->seed(TariffType::class, $data['tariff_types'] ?? []);
        $this->seed(ServiceCategory::class, $data['service_types'] ?? []);
    }

    /**
     * @param  class-string  $model
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function seed(string $model, array $rows, bool $withCode = false): void
    {
        foreach ($rows as $row) {
            $values = [
                'name' => $row['name'],
                'network' => (self::NETWORKS[$row['slug']] ?? Network::Both)->value,
                'sort' => $row['sort'] ?? 0,
                'status' => $row['status'] ?? true,
            ];

            if ($withCode) {
                $values['code'] = $row['code'] ?? null;
            }

            $model::updateOrCreate(['slug' => $row['slug']], $values);
        }
    }
}
