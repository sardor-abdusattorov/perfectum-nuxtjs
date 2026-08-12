<?php

declare(strict_types=1);

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
    ['categories', 'Category'],
    ['devices', 'Device'],
    ['faqs', 'Faq'],
    ['menus', 'Menu'],
    ['news', 'News'],
    ['pages', 'Page'],
    ['services', 'Service'],
    ['site-settings', 'SiteSettings'],
    ['site-translations', 'SiteTranslation'],
    ['socials', 'Social'],
    ['tariffs', 'Tariff'],
    ['tenders', 'Tender'],
    ['users', 'User'],
    ['vacancies', 'Vacancy'],
]);

it('forgets the cached category options when a category changes', function (): void {
    $type = App\Enums\CategoryType::Tariff;

    App\Models\Category::create(['type' => $type, 'name' => ['ru' => 'Первая'], 'slug' => 'pervaya', 'sort' => 1, 'status' => true]);

    expect(App\Models\Category::options($type))->toHaveCount(1);

    App\Models\Category::create(['type' => $type, 'name' => ['ru' => 'Вторая'], 'slug' => 'vtoraya', 'sort' => 2, 'status' => true]);

    expect(App\Models\Category::options($type))->toHaveCount(2);
});
