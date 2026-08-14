<?php

declare(strict_types=1);

use App\Models\Tariff;
use App\Models\TariffCategory;
use App\Models\TariffFile;
use App\Models\TariffType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function tariff(array $attributes = []): Tariff
{
    return Tariff::create(array_merge([
        'name' => ['ru' => 'Qulay 1', 'uz' => 'Qulay 1'],
        'slug' => 'qulay-1',
        'price' => '25 000',
        'price_currency' => ['ru' => 'сум', 'uz' => 'so`m'],
        'price_period' => ['ru' => 'мес.', 'uz' => 'oy'],
        'features' => [
            ['icon' => 'phone', 'title' => ['ru' => '400 минут', 'uz' => '400 daqiqa'], 'note' => ['ru' => '(Исходящие)', 'uz' => '(Chiquvchi)']],
        ],
        'descriptions' => [
            ['name' => ['ru' => 'Подробнее', 'uz' => 'Batafsil'], 'content' => ['ru' => '<p>Условия</p>', 'uz' => '<p>Shartlar</p>']],
        ],
        'buttons' => [
            ['icon' => null, 'name' => ['ru' => 'Наберите 7*1*1', 'uz' => 'Chaqiruv 7*1*1'], 'url' => 'tel:7*1*1', 'type' => 'tel'],
        ],
        'status' => true,
    ], $attributes));
}

it('serves a tariff with translated repeater rows', function (): void {
    tariff();

    $this->getJson(route('api.v1.tariffs.show', ['tariff' => 'qulay-1']))
        ->assertOk()
        ->assertJsonPath('data.features.0.title', '400 минут')
        ->assertJsonPath('data.features.0.note', '(Исходящие)')
        ->assertJsonPath('data.descriptions.0.name', 'Подробнее')
        ->assertJsonPath('data.descriptions.0.content', '<p>Условия</p>')
        ->assertJsonPath('data.buttons.0.name', 'Наберите 7*1*1');
});

it('serves the uz locale of every repeater row', function (): void {
    tariff();

    $this->getJson(route('api.v1.tariffs.show', ['tariff' => 'qulay-1']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.features.0.title', '400 daqiqa')
        ->assertJsonPath('data.descriptions.0.name', 'Batafsil')
        ->assertJsonPath('data.buttons.0.name', 'Chaqiruv 7*1*1');
});

it('filters the list by category and type', function (): void {
    $category = TariffCategory::create(['name' => ['ru' => 'CDMA'], 'status' => true]);
    $type = TariffType::create(['name' => ['ru' => 'Месячные'], 'status' => true]);

    tariff(['category_id' => $category->id, 'type_id' => $type->id]);
    tariff(['slug' => 'other']);

    $this->getJson(route('api.v1.tariffs.index', ['category' => $category->id]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'qulay-1');

    $this->getJson(route('api.v1.tariffs.index', ['type' => $type->id]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'qulay-1');
});

it('lists the published archive documents', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('files/archive.pdf', 'pdf');
    Storage::disk('public')->put('files/draft.pdf', 'pdf');

    TariffFile::create(['name' => '#архивные ТП 2025.pdf', 'file' => 'files/archive.pdf', 'sort' => 1]);
    TariffFile::create(['name' => 'Черновик', 'file' => 'files/draft.pdf', 'status' => false]);

    $this->getJson(route('api.v1.tariffs.files'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', '#архивные ТП 2025.pdf');
});

it('leaves out an archive document whose file is gone', function (): void {
    Storage::fake('public');

    TariffFile::create(['name' => 'Пропавший', 'file' => 'files/missing.pdf', 'sort' => 1]);

    $this->getJson(route('api.v1.tariffs.files'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
