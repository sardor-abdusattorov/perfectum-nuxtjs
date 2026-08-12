<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SettingsSeeder::class,
            SiteSettingsSeeder::class,
            SiteTranslationSeeder::class,
            SocialSeeder::class,
            PageSettingsSeeder::class,
            TaxonomySeeder::class,
            TariffSeeder::class,
            OfficeSeeder::class,
            MenuSeeder::class,
            HomepageSeeder::class,
        ]);
    }
}
