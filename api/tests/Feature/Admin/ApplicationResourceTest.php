<?php

declare(strict_types=1);

use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Models\Application;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();

    $this->admin = User::factory()->create();

    foreach (['ViewAny', 'View'] as $verb) {
        $this->admin->givePermissionTo(Permission::findOrCreate("{$verb}:Application", 'web'));
    }

    $this->admin->refresh();
});

it('lists the applications', function (): void {
    $applications = Application::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get('/admin/applications')
        ->assertOk();

    Livewire::test(ListApplications::class)
        ->assertCanSeeTableRecords($applications);
});

it('has no create page', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/applications/create')
        ->assertNotFound();
});

it('leaves every status untouched when one application is opened', function (): void {
    $applications = Application::factory()->count(5)->create();
    $opened = $applications->get(2);

    $this->actingAs($this->admin)
        ->get("/admin/applications/{$opened->id}")
        ->assertOk();

    foreach ($applications as $application) {
        expect($application->fresh()->status)->toBe(Application::STATUS_NEW);
    }
});

it('changes the status of one application only', function (): void {
    $this->admin->givePermissionTo(Permission::findOrCreate('Update:Application', 'web'));

    $applications = Application::factory()->count(3)->create();
    $target = $applications->first();

    $this->actingAs($this->admin->refresh());

    Livewire::test(ListApplications::class)
        ->callAction(TestAction::make('changeStatus')->table($target), [
            'status' => Application::STATUS_PROCESSED,
        ]);

    expect($target->fresh()->status)->toBe(Application::STATUS_PROCESSED);
    expect($applications->get(1)->fresh()->status)->toBe(Application::STATUS_NEW);
    expect($applications->get(2)->fresh()->status)->toBe(Application::STATUS_NEW);
});

it('hides the status change from an account without the update permission', function (): void {
    $application = Application::factory()->create();

    $this->actingAs($this->admin);

    Livewire::test(ListApplications::class)
        ->assertActionHidden(TestAction::make('changeStatus')->table($application));

    expect($application->fresh()->status)->toBe(Application::STATUS_NEW);
});
