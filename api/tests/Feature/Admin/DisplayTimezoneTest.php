<?php

declare(strict_types=1);

use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Models\Application;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

it('stores in utc and shows tashkent time', function (): void {
    expect(config('app.timezone'))->toBe('UTC')
        ->and(FilamentTimezone::get())->toBe('Asia/Tashkent');
});

/**
 * The list showed 04:26 for an application sent at 09:26 in Tashkent, which is
 * the stored UTC value printed as-is.
 */
it('prints an application five hours later than it is stored', function (): void {
    $application = Application::factory()->create();

    $application->forceFill(['created_at' => '2026-09-03 04:26:00'])->saveQuietly();

    $this->actingAs(panelUser(['ViewAny:Application', 'View:Application']));

    Livewire::test(ListApplications::class)
        ->assertSee('03.09.2026 09:26')
        ->assertDontSee('03.09.2026 04:26');
});
