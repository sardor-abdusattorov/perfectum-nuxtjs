<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->theme = ApplicationTheme::factory()->create(['name' => ['ru' => 'Подключение']]);
});

it('stores an application with a normalised phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '+998 (90) 123-45-67',
        'theme' => $this->theme->id,
        'message' => 'Хочу подключить интернет',
    ])
        ->assertCreated()
        ->assertJsonPath('data.submitted', true);

    assertDatabaseHas(Application::class, [
        'phone' => '+998 (90) 123-45-67',
        'theme_id' => $this->theme->id,
        'message' => 'Хочу подключить интернет',
    ]);
});

it('accepts bare digits as the phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '998901234567',
        'theme' => $this->theme->id,
        'message' => 'Вопрос',
    ])->assertCreated();

    assertDatabaseHas(Application::class, ['phone' => '+998 (90) 123-45-67']);
});

it('turns down a subject that is not on the list', function (): void {
    $retired = ApplicationTheme::factory()->create(['status' => false]);

    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '+998 (90) 123-45-67',
        'theme' => $retired->id,
        'message' => 'Вопрос',
    ])->assertStatus(422)->assertJsonValidationErrors('theme');
});

it('lists the subjects the form offers', function (): void {
    ApplicationTheme::factory()->create(['name' => ['ru' => 'Другое'], 'sort' => 2]);

    $this->getJson(route('api.v1.categories', 'application-themes'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.1.name', 'Другое');
});

it('requires every field', function (array $overrides, string $broken): void {
    $payload = array_filter([
        'phone' => '+998 (90) 123-45-67',
        'theme' => $this->theme->id,
        'message' => 'x',
        ...$overrides,
    ], fn (mixed $value): bool => $value !== null);

    $this->postJson(route('api.v1.applications.store'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors($broken);
})->with([
    'missing phone' => [['phone' => null], 'phone'],
    'short phone' => [['phone' => '+998 (90) 123'], 'phone'],
    'foreign phone' => [['phone' => '+7 900 123-45-67'], 'phone'],
    'missing theme' => [['theme' => null], 'theme'],
    'unknown theme' => [['theme' => 9999], 'theme'],
    'missing message' => [['message' => null], 'message'],
    'long message' => [['message' => str_repeat('а', 501)], 'message'],
]);
