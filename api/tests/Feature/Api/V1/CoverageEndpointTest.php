<?php

declare(strict_types=1);

use App\Models\CoverageLayer;
use App\Models\NewsCategory;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

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

function storedLayer(array $ring): CoverageLayer
{
    Storage::fake('public');

    $path = Storage::disk('public')->putFile(
        'uploads/coverage',
        new UploadedFile(coverageZip($ring), 'coverage.zip', 'application/zip', null, true)
    );

    $layer = coverageLayer();
    $layer->update(['file' => $path]);

    return $layer->fresh();
}

function storedRing(CoverageLayer $layer): array
{
    return $layer->geojson['features'][0]['geometry']['coordinates'][0];
}

/**
 * The contours are traced off a twenty-metre grid, so the fifth and sixth
 * decimals are reprojection remainder, not measurement — and being close to
 * random they are what gzip cannot pack. Four decimals is eleven metres.
 */
it('trims the shapes to the precision the map can use', function (): void {
    $layer = storedLayer([
        [69.65770612, 40.79734212],
        [69.65794212, 40.79732912],
        [69.65796098, 40.79750843],
        [69.65770612, 40.79734212],
    ]);

    expect(storedRing($layer))->toBe([
        [69.6577, 40.7973],
        [69.6579, 40.7973],
        [69.658, 40.7975],
        [69.6577, 40.7973],
    ]);
});

it('drops a point that rounding has moved onto its neighbour', function (): void {
    $layer = storedLayer([
        [69.657701, 40.797341],
        [69.657702, 40.797342],
        [69.658912, 40.797508],
        [69.657701, 40.797341],
    ]);

    expect(storedRing($layer))->toBe([
        [69.6577, 40.7973],
        [69.6589, 40.7975],
        [69.6577, 40.7973],
    ]);
});

it('reads the archive again on command, so a layer stored before keeps up', function (): void {
    $layer = storedLayer([
        [69.65770612, 40.79734212],
        [69.65794212, 40.79732912],
        [69.65796098, 40.79750843],
        [69.65770612, 40.79734212],
    ]);

    $layer->forceFill(['geojson' => ['type' => 'FeatureCollection', 'features' => [[
        'type' => 'Feature',
        'properties' => [],
        'geometry' => ['type' => 'Polygon', 'coordinates' => [[[69.657706, 40.797342]]]],
    ]]]])->saveQuietly();

    expect(storedRing($layer->fresh())[0])->toBe([69.657706, 40.797342]);

    $this->artisan('coverage:refresh')->assertSuccessful();

    expect(storedRing($layer->fresh())[0])->toBe([69.6577, 40.7973])
        ->and($layer->fresh()->features)->toBe(1);
});

it('leaves a layer alone when its archive is gone', function (): void {
    Storage::fake('public');

    $layer = coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $this->artisan('coverage:refresh')->assertSuccessful();

    expect($layer->fresh()->geojson['features'])->toHaveCount(1);
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

it('serves every feature with an object for its properties', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [
        ['type' => 'Feature', 'properties' => [], 'geometry' => ['type' => 'Point', 'coordinates' => [69.24, 41.3]]],
        ['type' => 'Feature', 'geometry' => ['type' => 'Point', 'coordinates' => [69.25, 41.31]]],
        ['type' => 'Feature', 'properties' => ['name' => 'Ташкент'], 'geometry' => null],
    ]]]);

    $response = $this->getJson(route('api.v1.coverage.show', '5g'))->assertOk();

    expect(substr_count($response->getContent(), '"properties":{}'))->toBe(2)
        ->and($response->json('features.2.properties.name'))->toBe('Ташкент')
        ->and($response->json('features.0.geometry.coordinates'))->toBe([69.24, 41.3]);
});

it('lets a reader revalidate a layer instead of downloading it twice', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $first = $this->getJson(route('api.v1.coverage.show', '5g'))->assertOk();

    expect($first->headers->get('Cache-Control'))->toContain('public')
        ->and($first->headers->get('Cache-Control'))->toContain('max-age=3600')
        ->and($etag = $first->headers->get('ETag'))->not->toBeEmpty();

    $this->withHeader('If-None-Match', $etag)
        ->getJson(route('api.v1.coverage.show', '5g'))
        ->assertStatus(304)
        ->assertNoContent(304);
});

it('answers not modified without reading the geometry', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $etag = $this->getJson(route('api.v1.coverage.show', '5g'))->assertOk()->headers->get('ETag');

    CoverageLayer::retrieved(fn (CoverageLayer $found) => $found->offsetUnset('geojson'));

    $this->withHeader('If-None-Match', $etag)
        ->getJson(route('api.v1.coverage.show', '5g'))
        ->assertStatus(304);
});

it('stops honouring the old validator once the layer is replaced', function (): void {
    $layer = coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => [['type' => 'Feature']]]]);

    $stale = $this->getJson(route('api.v1.coverage.show', '5g'))->assertOk()->headers->get('ETag');

    $layer->update(['geojson' => ['type' => 'FeatureCollection', 'features' => []]]);

    $response = $this->withHeader('If-None-Match', $stale)
        ->getJson(route('api.v1.coverage.show', '5g'))
        ->assertOk()
        ->assertJsonCount(0, 'features');

    expect($response->headers->get('ETag'))->not->toBe($stale);
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

it('hands over the layers alone, without a list of regions', function (): void {
    coverageLayer(['geojson' => ['type' => 'FeatureCollection', 'features' => []]]);
    Region::create(['name' => ['ru' => 'г. Ташкент'], 'latitude' => 41.2995, 'longitude' => 69.2401, 'sort' => 1, 'status' => true]);

    $this->getJson(route('api.v1.coverage'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissingPath('cities');
});

it('carries the centre of a region in the taxonomy the map reads', function (): void {
    Region::create(['name' => ['ru' => 'г. Ташкент', 'uz' => 'Toshkent sh.'], 'latitude' => 41.2995, 'longitude' => 69.2401, 'sort' => 1, 'status' => true]);
    Region::create(['name' => ['ru' => 'Без точки'], 'sort' => 99, 'status' => true]);

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'regions']), ['X-Locale' => 'uz'])
        ->assertOk()
        ->assertJsonPath('data.0.name', 'Toshkent sh.')
        ->assertJsonPath('data.0.center', [41.2995, 69.2401])
        ->assertJsonPath('data.1.center', null);
});

it('leaves a centre off a taxonomy that has no coordinates', function (): void {
    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))->assertOk();

    NewsCategory::create([
        'name' => ['ru' => 'Компания'],
        'slug' => 'kompaniya',
        'sort' => 1,
        'status' => true,
    ]);

    Cache::flush();

    $this->getJson(route('api.v1.categories', ['taxonomy' => 'news-categories']))
        ->assertOk()
        ->assertJsonPath('data.0.center', null);
});
