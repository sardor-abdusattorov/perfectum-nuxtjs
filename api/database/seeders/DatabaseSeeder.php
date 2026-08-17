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
            ServiceSeeder::class,
            OfficeSeeder::class,
            FaqSeeder::class,
            FreeNumberFilterSeeder::class,
            ApplicationThemeSeeder::class,
            ApplicationSeeder::class,
            ContentSeeder::class,
            MenuSeeder::class,
            HomepageSeeder::class,
            AboutCompanySeeder::class,
            ContactsSeeder::class,
            CdmaConnectSeeder::class,
            CdmaSeeder::class,
            DocumentSeeder::class,
            CoverageSeeder::class,
            DeviceSeeder::class,
        ]);
    }
}
