<?php

declare(strict_types=1);

use App\Filament\Resources\CoverageLayers\CoverageLayerResource;
use App\Filament\Resources\CoverageLayers\Pages\EditCoverageLayer;
use App\Filament\Resources\CoverageLayers\Pages\ListCoverageLayers;
use App\Models\CoverageLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $user = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate("{$ability}:CoverageLayer", 'web'));
    }

    $this->actingAs($user->refresh());
});

function heavyLayer(): CoverageLayer
{
    $points = [];
    $longitude = 69.2;
    $latitude = 41.3;

    for ($index = 0; $index < 275211; $index++) {
        $longitude += 0.0002;
        $latitude += 0.0001;
        $points[] = [round($longitude, 4), round($latitude, 4)];
    }

    return CoverageLayer::create([
        'key' => '5g',
        'name' => ['ru' => '5G Standalone', 'uz' => '5G Standalone'],
        'color' => '#e60000',
        'geojson' => ['type' => 'FeatureCollection', 'features' => [[
            'type' => 'Feature',
            'properties' => (object) [],
            'geometry' => ['type' => 'Polygon', 'coordinates' => [$points]],
        ]]],
        'sort' => 1,
    ]);
}

/**
 * Filament fills the form from every attribute of the record, so the contours
 * used to ride into the Livewire component and back on every request. Six
 * megabytes of them, against a payload Livewire refuses past eight — the page
 * answered «Livewire request payload is too large» and the upload field fell
 * back to a bare browser input.
 */
it('keeps the contours out of the form the panel sends to the browser', function (): void {
    $layer = heavyLayer();

    expect(strlen((string) $layer->fresh()->getRawOriginal('geojson')))->toBeGreaterThan(4_000_000);

    $data = Livewire::test(EditCoverageLayer::class, ['record' => $layer->getRouteKey()])->get('data');

    expect($data)->not->toHaveKey('geojson')
        ->and(strlen((string) json_encode($data)))->toBeLessThan(64 * 1024);
});

it('leaves the contours alone when the form is saved without a new archive', function (): void {
    $layer = heavyLayer();
    $before = $layer->fresh()->getRawOriginal('geojson');

    Livewire::test(EditCoverageLayer::class, ['record' => $layer->getRouteKey()])
        ->fillForm(['color' => '#0000ff'])
        ->call('save')
        ->assertHasNoFormErrors();

    $layer->refresh();

    expect($layer->color)->toBe('#0000ff')
        ->and($layer->getRawOriginal('geojson'))->toBe($before)
        ->and($layer->features)->toBe(1);
});

/**
 * The record the panel hands to the save handler no longer carries the geometry
 * column at all, so this is the case worth pinning: attaching an archive to a
 * record loaded that way still reads the contours and counts them.
 */
it('reads a freshly attached archive on a record loaded without the column', function (): void {
    Storage::fake('public');

    CoverageLayer::create([
        'key' => 'voice',
        'name' => ['ru' => 'Голосовая связь', 'uz' => 'Ovozli aloqa'],
        'color' => '#00a651',
        'sort' => 2,
    ]);

    $path = Storage::disk('public')->putFile(
        'uploads/coverage',
        new UploadedFile(coverageZip(), 'coverage.zip', 'application/zip', null, true)
    );

    $fromPanel = CoverageLayerResource::getEloquentQuery()->where('key', 'voice')->firstOrFail();

    expect($fromPanel->getAttributes())->not->toHaveKey('geojson');

    $fromPanel->update(['file' => $path]);

    $stored = CoverageLayer::query()->where('key', 'voice')->firstOrFail();

    expect($stored->features)->toBe(1)
        ->and($stored->geojson['features'][0]['geometry']['coordinates'][0][0])->toBe([69.1, 41.2]);
});

it('tells a layer with contours from one that was never read', function (): void {
    heavyLayer();

    CoverageLayer::create([
        'key' => 'voice',
        'name' => ['ru' => 'Голосовая связь', 'uz' => 'Ovozli aloqa'],
        'color' => '#00a651',
        'sort' => 2,
    ]);

    Livewire::test(ListCoverageLayers::class)
        ->assertSee('1')
        ->assertSee(__('app.label.coverage_unread'));
});
