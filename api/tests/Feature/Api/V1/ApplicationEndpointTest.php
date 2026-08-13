<?php

declare(strict_types=1);

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('stores an application with a normalised phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '+998 (90) 123-45-67',
        'theme' => Application::THEME_CONNECTION,
        'message' => 'Хочу подключить интернет',
    ])
        ->assertCreated()
        ->assertJsonPath('data.submitted', true);

    assertDatabaseHas(Application::class, [
        'phone' => '+998 (90) 123-45-67',
        'theme' => Application::THEME_CONNECTION,
        'message' => 'Хочу подключить интернет',
        'status' => Application::STATUS_NEW,
    ]);
});

it('accepts bare digits as the phone', function (): void {
    $this->postJson(route('api.v1.applications.store'), [
        'phone' => '998901234567',
        'theme' => Application::THEME_OTHER,
        'message' => 'Вопрос',
    ])->assertCreated();

    assertDatabaseHas(Application::class, ['phone' => '+998 (90) 123-45-67']);
});

it('requires every field', function (array $payload, string $broken): void {
    $this->postJson(route('api.v1.applications.store'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors($broken);
})->with([
    'missing phone' => [['theme' => 'connection', 'message' => 'x'], 'phone'],
    'short phone' => [['phone' => '+998 (90) 123', 'theme' => 'connection', 'message' => 'x'], 'phone'],
    'foreign phone' => [['phone' => '+7 900 123-45-67', 'theme' => 'connection', 'message' => 'x'], 'phone'],
    'missing theme' => [['phone' => '+998 (90) 123-45-67', 'message' => 'x'], 'theme'],
    'unknown theme' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'spam', 'message' => 'x'], 'theme'],
    'missing message' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'connection'], 'message'],
    'long message' => [['phone' => '+998 (90) 123-45-67', 'theme' => 'connection', 'message' => str_repeat('а', 501)], 'message'],
]);
