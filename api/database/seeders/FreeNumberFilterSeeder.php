<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FreeNumberFilter;
use Illuminate\Database\Seeder;

class FreeNumberFilterSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode((string) file_get_contents(database_path('data/free_number_filters.json')), true);

        foreach ($rows as $row) {
            FreeNumberFilter::updateOrCreate(
                ['type' => $row['type'], 'value' => $row['value']],
                ['name' => $row['name']]
            );
        }
    }
}
