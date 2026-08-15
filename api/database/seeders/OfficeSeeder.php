<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Network;
use App\Enums\OfficeType;
use App\Models\Office;
use App\Models\Region;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/offices.json')), true);

        $regions = [];

        foreach ($data['regions'] ?? [] as $region) {
            $regions[$region['slug']] = Region::updateOrCreate(
                ['name->ru' => $region['name']['ru']],
                ['name' => $region['name'], 'sort' => $region['sort']],
            )->getKey();
        }

        foreach ($data['offices'] ?? [] as $index => $row) {
            Office::updateOrCreate(['type' => $row['type'], 'sort' => $index + 1, 'network' => Network::FiveG], [
                'region_id' => $regions[$row['region']] ?? null,
                'name' => $row['name'] ?? null,
                'district' => $row['district'] ?? null,
                'address' => $row['address'],
                'lat' => $row['lat'] ?? null,
                'lng' => $row['lng'] ?? null,
            ]);
        }

        $this->seedCdmaDealers();
    }

    /**
     * The CDMA side lists no points: each entry is a region card — the name,
     * a hand-kept dealer count, and the dealer table as editor content — the
     * way the layout draws /cdma/dealers.
     */
    private function seedCdmaDealers(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/cdma_dealers.json')), true);

        $regions = Region::query()
            ->get()
            ->mapWithKeys(fn (Region $region): array => [$region->getTranslation('name', 'ru') => $region->getKey()]);

        foreach ($data['dealers'] ?? [] as $index => $row) {
            $region = $regions[$row['region']] ?? null;

            if ($region === null) {
                continue;
            }

            Office::updateOrCreate(['network' => Network::Cdma, 'region_id' => $region], [
                'type' => OfficeType::Dealer,
                'dealers_count' => $row['dealers_count'],
                'content' => $row['content'] ?? null,
                'sort' => $index + 1,
            ]);
        }
    }
}
