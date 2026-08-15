<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContentBlockKey;
use App\Enums\PageKey;
use App\Models\ContentBlock;
use App\Models\CoverageLayer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CoverageSeeder extends Seeder
{
    /**
     * The archives themselves are uploaded from the admin; the rows carry the
     * layers the map switches between.
     *
     * @var array<int, array<string, mixed>>
     */
    private const LAYERS = [
        ['key' => '5g', 'name' => ['ru' => '5G Standalone', 'uz' => '5G Standalone'], 'color' => '#e60000'],
        ['key' => '4g', 'name' => ['ru' => '4G LTE', 'uz' => '4G LTE'], 'color' => '#f2994a'],
        ['key' => 'voice', 'name' => ['ru' => 'Голосовая связь', 'uz' => 'Ovozli aloqa'], 'color' => '#2f80ed'],
    ];

    public function run(): void
    {
        foreach (self::LAYERS as $sort => $layer) {
            CoverageLayer::updateOrCreate(['key' => $layer['key']], [
                'name' => $layer['name'],
                'color' => $layer['color'],
                'sort' => $sort + 1,
            ]);
        }

        $this->attachExport('5g', 'coverage-5g.zip');

        ContentBlock::write(PageKey::CoverageArea, ContentBlockKey::PageHero, [
            'eyebrow' => ['ru' => '5G Standalone', 'uz' => '5G Standalone'],
            'title' => [
                'ru' => 'Карта<br /><span class="color" data-color="accent">покрытия</span>',
                'uz' => 'Qamrov<br /><span class="color" data-color="accent">xaritasi</span>',
            ],
            'subtitle' => [
                'ru' => 'Проверьте доступность сети Perfectum 5G SA в вашем городе — в зоне покрытия работает и мобильная связь, и домашний интернет.',
                'uz' => 'Shahringizda Perfectum 5G SA tarmogʻi mavjudligini tekshiring — qamrov hududida mobil aloqa ham, uy interneti ham ishlaydi.',
            ],
        ]);
    }

    /**
     * The network team's shapefile export ships with the repo, so a fresh
     * database serves the real 5G outline at once; a layer that already has
     * an upload keeps it.
     */
    private function attachExport(string $key, string $file): void
    {
        $layer = CoverageLayer::query()->where('key', $key)->first();

        if ($layer === null || filled($layer->file)) {
            return;
        }

        $path = "uploads/coverage/{$file}";

        Storage::disk('public')->put($path, (string) file_get_contents(database_path("data/{$file}")));

        $layer->update(['file' => $path]);
    }
}
