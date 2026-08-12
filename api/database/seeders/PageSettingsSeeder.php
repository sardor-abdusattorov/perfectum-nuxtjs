<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageKey;
use App\Models\PageSettings;
use Illuminate\Database\Seeder;

class PageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PageKey::cases() as $key) {
            PageSettings::firstOrCreate(['key' => $key->value], ['is_indexed' => true]);
        }
    }
}
