<?php

declare(strict_types=1);

use App\Filament\Resources\Regions\Pages\ListRegions;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Region;
use App\Models\Service;
use App\Support\PreviewToken;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser();

    foreach (['Service', 'Region'] as $resource) {
        foreach (['ViewAny', 'View', 'Update', 'Delete'] as $verb) {
            $this->admin->givePermissionTo(Permission::findOrCreate("{$verb}:{$resource}", 'web'));
        }
    }

    $this->admin->refresh();

    $this->actingAs($this->admin);
});

function draftService(): Service
{
    return Service::create([
        'name' => ['ru' => 'Черновик услуги', 'uz' => 'Xizmat qoralamasi'],
        'slug' => 'chernovik-uslugi',
        'content' => ['ru' => '<p>Ещё не опубликовано</p>'],
        'status' => false,
    ]);
}

it('offers the preview from the listing, not only from the form', function (): void {
    $service = draftService();

    Livewire::test(ListServices::class)
        ->assertActionVisible(TestAction::make('preview')->table($service));
});

/**
 * The action lives in the shared row actions, so every listing carries it. A
 * record with no address on the site has nothing to open and must not show it.
 */
it('stays hidden for a record that has no page on the site', function (): void {
    $region = Region::create(['name' => ['ru' => 'Ташкент'], 'status' => true]);

    Livewire::test(ListRegions::class)
        ->assertActionHidden(TestAction::make('preview')->table($region));
});

it('points at the record on the site with a token that opens it', function (): void {
    config(['app.frontend_url' => 'https://v2.perfectum.uz']);

    $service = draftService();

    $url = Livewire::test(ListServices::class)
        ->instance()
        ->getTable()
        ->getAction('preview')
        ->record($service)
        ->getUrl();

    expect($url)->toStartWith('https://v2.perfectum.uz/'.app()->getLocale().'/services/chernovik-uslugi?'.PreviewToken::PARAM.'=');

    parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

    $this->getJson(route('api.v1.services.show', ['service' => 'chernovik-uslugi']).'?'.PreviewToken::PARAM.'='.urlencode($query[PreviewToken::PARAM]))
        ->assertOk()
        ->assertJsonPath('data.slug', 'chernovik-uslugi');
});
