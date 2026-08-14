<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\ApplicationTheme;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->theme = ApplicationTheme::factory()->create(['slug' => 'podklyuchenie']);
});

it('stores an application with a normalised phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '+998 (90) 123-45-67',
        'theme' => 'podklyuchenie',
        'message' => 'Хочу подключить интернет',
    ])
        ->assertCreated()
        ->assertJsonPath('data.submitted', true);

    assertDatabaseHas(Application::class, [
        'phone' => '+998 (90) 123-45-67',
        'theme_id' => $this->theme->id,
        'message' => 'Хочу подключить интернет',
        'status' => Application::STATUS_NEW,
    ]);
});

it('accepts bare digits as the phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '998901234567',
        'theme' => 'podklyuchenie',
        'message' => 'Вопрос',
    ])->assertCreated();

    assertDatabaseHas(Application::class, ['phone' => '+998 (90) 123-45-67']);
});

it('turns down a subject that is not on the list', function (): void {
    ApplicationTheme::factory()->create(['slug' => 'snyata-s-publikacii', 'status' => false]);

    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '+998 (90) 123-45-67',
        'theme' => 'snyata-s-publikacii',
        'message' => 'Вопрос',
    ])->assertStatus(422)->assertJsonValidationErrors('theme');
});

it('lists the subjects the form offers', function (): void {
    ApplicationTheme::factory()->create(['slug' => 'drugoe', 'name' => ['ru' => 'Другое'], 'sort' => 2]);

    $this->getJson(route('api.v1.categories', 'application-themes'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.1.slug', 'drugoe')
        ->assertJsonPath('data.1.name', 'Другое');
});

it('requires every field', function (array $payload, string $broken): void {
    $this->postJson(route('api.v1.applications.store'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors($broken);
})->with([
    'missing phone' => [['theme' => 'podklyuchenie', 'message' => 'x'], 'phone'],
    'short phone' => [['phone' => '+998 (90) 123', 'theme' => 'podklyuchenie', 'message' => 'x'], 'phone'],
    'foreign phone' => [['phone' => '+7 900 123-45-67', 'theme' => 'podklyuchenie', 'message' => 'x'], 'phone'],
    'missing theme' => [['phone' => '+998 (90) 123-45-67', 'message' => 'x'], 'theme'],
    'unknown theme' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'spam', 'message' => 'x'], 'theme'],
    'missing message' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'podklyuchenie'], 'message'],
    'long message' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'podklyuchenie', 'message' => str_repeat('а', 501)], 'message'],
]);
