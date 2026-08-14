<?php

declare(strict_types=1);

use App\Enums\PageKey;
use App\Models\PageSettings;
use App\Models\TariffCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

it('renders the list and create page of every resource', function (string $resource, string $model): void {
    $user = User::factory()->create();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $verb) {
        $user->givePermissionTo(Permission::findOrCreate("{$verb}:{$model}", 'web'));
    }

    $this->actingAs($user->refresh())
        ->get("/admin/{$resource}")
        ->assertOk();

    $this->actingAs($user)
        ->get("/admin/{$resource}/create")
        ->assertOk();
})->with([
    ['actions', 'Action'],
    ['application-themes', 'ApplicationTheme'],
    ['news-categories', 'NewsCategory'],
    ['action-categories', 'ActionCategory'],
    ['faq-categories', 'FaqCategory'],
    ['device-categories', 'DeviceCategory'],
    ['tariff-categories', 'TariffCategory'],
    ['tariff-types', 'TariffType'],
    ['service-categories', 'ServiceCategory'],
    ['regions', 'Region'],
    ['offices', 'Office'],
    ['devices', 'Device'],
    ['documents', 'Document'],
    ['document-categories', 'DocumentCategory'],
    ['coverage-layers', 'CoverageLayer'],
    ['faqs', 'Faq'],
    ['free-number-filters', 'FreeNumberFilter'],
    ['menus', 'Menu'],
    ['news', 'News'],
    ['pages', 'Page'],
    ['page-settings', 'PageSettings'],
    ['services', 'Service'],
    ['site-settings', 'SiteSettings'],
    ['site-translations', 'SiteTranslation'],
    ['socials', 'Social'],
    ['tariffs', 'Tariff'],
    ['tariff-files', 'TariffFile'],
    ['tenders', 'Tender'],
    ['users', 'User'],
    ['vacancies', 'Vacancy'],
]);

it('opens the edit page of a page settings row', function (): void {
    $user = User::factory()->create();

    foreach (['ViewAny', 'View', 'Create', 'Update'] as $verb) {
        $user->givePermissionTo(Permission::findOrCreate("{$verb}:PageSettings", 'web'));
    }

    $record = PageSettings::create(['key' => PageKey::Tariffs, 'is_indexed' => true]);

    $this->actingAs($user->refresh())
        ->get("/admin/page-settings/{$record->id}/edit")
        ->assertOk();
});

it('forgets the cached category options when a category changes', function (): void {
    TariffCategory::create(['name' => ['ru' => 'Первая'], 'sort' => 1, 'status' => true]);

    expect(TariffCategory::options())->toHaveCount(1);

    TariffCategory::create(['name' => ['ru' => 'Вторая'], 'sort' => 2, 'status' => true]);

    expect(TariffCategory::options())->toHaveCount(2);
});
