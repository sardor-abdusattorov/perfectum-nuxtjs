<?php

declare(strict_types=1);

use App\Models\CoverageLayer;
use Database\Seeders\CoverageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function coverageZip(): string
{
    $ring = [[69.1, 41.2], [69.4, 41.2], [69.4, 41.4], [69.1, 41.2]];

    $body = pack('V', 5);
    $body .= pack('d4', 69.1, 41.2, 69.4, 41.4);
    $body .= pack('V2', 1, count($ring));
    $body .= pack('V', 0);

    foreach ($ring as [$x, $y]) {
        $body .= pack('d2', $x, $y);
    }

    $record = pack('N2', 1, strlen($body) / 2).$body;
    $header = pack('N', 9994).str_repeat("\0", 20).pack('N', (100 + strlen($record)) / 2);
    $header .= pack('V2', 1000, 5).pack('d8', 0, 0, 0, 0, 0, 0, 0, 0);

    $path = tempnam(sys_get_temp_dir(), 'cov').'.zip';
    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('coverage.shp', $header.$record);
    $zip->addFromString('coverage.prj', 'GEOGCS["GCS_WGS_1984"]');
    $zip->close();

    return $path;
}

function coverageLayer(array $attributes = []): CoverageLayer
{
    return CoverageLayer::create(array_merge([
        'key' => '5g',
        'name' => ['ru' => '5G Standalone', 'uz' => '5G Standalone'],
        'color' => '#e60000',
        'sort' => 1,
    ], $attributes));
}

it('reads the archive into geojson when it is attached', function (): void {
    Storage::fake('public');

    $path = Storage::disk('public')->putFile(
        'uploads/coverage',
        new UploadedFile(coverageZip(), 'coverage.zip', 'application/zip', null, true)
    );

    $layer = coverageLayer();
    $layer->update(['file' => $path]);

    expect($layer->fresh()->features)->toBe(1);
});

it('lists a published layer that has shapes without its collection', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $this->getJson(route('api.v1.coverage'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.key', '5g')
        ->assertJsonPath('data.0.color', '#e60000')
        ->assertJsonPath('data.0.features', 1)
        ->assertJsonMissingPath('data.0.geojson');
});

it('serves the collection of one layer', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $this->getJson(route('api.v1.coverage.show', '5g'))
        ->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonCount(1, 'features');
});

it('has nothing to draw for an unknown or unread layer', function (): void {
    coverageLayer(['key' => 'empty']);

    $this->getJson(route('api.v1.coverage.show', 'empty'))->assertNotFound();
    $this->getJson(route('api.v1.coverage.show', 'lte'))->assertNotFound();
});

it('leaves out a layer with no archive read and an unpublished one', function (): void {
    coverageLayer(['key' => 'empty']);
    coverageLayer([
        'key' => 'hidden',
        'status' => false,
        'geojson' => ['type' => 'FeatureCollection', 'features' => []],
    ]);
    coverageLayer(['key' => 'shown', 'geojson' => ['type' => 'FeatureCollection', 'features' => []]]);

    $this->getJson(route('api.v1.coverage'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.key', 'shown');
});

it('names the layer in the requested locale', function (): void {
    coverageLayer([
        'name' => ['ru' => 'Голосовая связь', 'uz' => 'Ovozli aloqa'],
        'geojson' => ['type' => 'FeatureCollection', 'features' => []],
    ]);

    $this->getJson(route('api.v1.coverage'), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Ovozli aloqa');
});

it('keeps the layer usable when the archive cannot be read', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('uploads/coverage/broken.zip', 'not an archive');

    $layer = coverageLayer();
    $layer->update(['file' => 'uploads/coverage/broken.zip']);

    expect($layer->fresh()->geojson)->toBeNull();

    $this->getJson(route('api.v1.coverage'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('serves the shipped 5g export inside tashkent', function (): void {
    Storage::fake('public');

    $this->seed(CoverageSeeder::class);

    $this->getJson(route('api.v1.coverage'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.key', '5g');

    $geometry = $this->getJson(route('api.v1.coverage.show', ['layer' => '5g']))
        ->assertOk()
        ->json('features.0.geometry');

    expect($geometry['type'])->toBe('MultiPolygon');

    [$lon, $lat] = $geometry['coordinates'][0][0][0];

    expect($lon)->toBeGreaterThan(68.5)->toBeLessThan(70.5)
        ->and($lat)->toBeGreaterThan(40.5)->toBeLessThan(42.0);
});
