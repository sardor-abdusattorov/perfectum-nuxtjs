<?php

declare(strict_types=1);

use App\Filament\Resources\ApplicationThemes\Pages\ListApplicationThemes;
use App\Models\Application;
use App\Models\ApplicationTheme;
use Filament\Actions\Testing\TestAction;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = panelUser();

    foreach (['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'] as $verb) {
        $this->admin->givePermissionTo(Permission::findOrCreate("{$verb}:ApplicationTheme", 'web'));
    }

    $this->admin->refresh();
});

it('deletes a subject nothing was filed under', function (): void {
    $theme = ApplicationTheme::factory()->create();

    $this->actingAs($this->admin);

    Livewire::test(ListApplicationThemes::class)
        ->callAction(TestAction::make('delete')->table($theme));

    expect(ApplicationTheme::count())->toBe(0);
});

it('refuses to delete a subject that already has applications', function (): void {
    $theme = ApplicationTheme::factory()->create();
    Application::factory()->create(['theme_id' => $theme->id]);

    $this->actingAs($this->admin);

    Livewire::test(ListApplicationThemes::class)
        ->callAction(TestAction::make('delete')->table($theme))
        ->assertActionDisabled(TestAction::make('delete')->table($theme));

    expect(ApplicationTheme::whereKey($theme->id)->exists())->toBeTrue();
    expect(Application::count())->toBe(1);
});

it('leaves the applications alone when the bulk delete skips a used subject', function (): void {
    $used = ApplicationTheme::factory()->create();
    $free = ApplicationTheme::factory()->create();
    Application::factory()->create(['theme_id' => $used->id]);

    $this->actingAs($this->admin);

    Livewire::test(ListApplicationThemes::class)
        ->selectTableRecords([$used->getKey(), $free->getKey()])
        ->callAction(TestAction::make('delete')->table()->bulk());

    expect(ApplicationTheme::whereKey($used->id)->exists())->toBeTrue();
    expect(ApplicationTheme::whereKey($free->id)->exists())->toBeFalse();
    expect(Application::count())->toBe(1);
});

/**
 * The database is the last line: whatever route a delete arrives by, an
 * application is never left without the subject it was filed under.
 */
it('will not let the database drop a subject out from under an application', function (): void {
    $theme = ApplicationTheme::factory()->create();
    Application::factory()->create(['theme_id' => $theme->id]);

    expect(fn () => $theme->delete())->toThrow(QueryException::class);

    expect(Application::count())->toBe(1);
});

it('keeps a retired subject on its old applications and off the form', function (): void {
    $theme = ApplicationTheme::factory()->create(['status' => true]);
    $application = Application::factory()->create(['theme_id' => $theme->id]);

    $theme->update(['status' => false]);

    expect($application->fresh()->theme->is($theme))->toBeTrue();

    $this->getJson(route('api.v1.categories', 'application-themes'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
