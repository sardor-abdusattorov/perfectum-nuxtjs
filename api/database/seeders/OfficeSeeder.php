<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Region;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode((string) file_get_contents(database_path('data/offices.json')), true);

        foreach ($data['regions'] ?? [] as $region) {
            Region::updateOrCreate(['slug' => $region['slug']], [
                'name' => $region['name'],
                'sort' => $region['sort'],
            ]);
        }

        $regions = Region::query()->pluck('id', 'slug')->all();

        foreach ($data['offices'] ?? [] as $index => $row) {
            Office::updateOrCreate(['type' => $row['type'], 'sort' => $index + 1], [
                'region_id' => $regions[$row['region']] ?? null,
                'name' => $row['name'] ?? null,
                'district' => $row['district'] ?? null,
                'address' => $row['address'],
                'lat' => $row['lat'] ?? null,
                'lng' => $row['lng'] ?? null,
            ]);
        }
    }
}
