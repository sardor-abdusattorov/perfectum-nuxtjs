<?php

declare(strict_types=1);

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    Application::factory()->count(3)->create();

    $this->actingAs($this->admin)
        ->get('/admin/applications')
        ->assertOk();
});

it('has no create page', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/applications/create')
        ->assertNotFound();
});

it('marks a new application processed on view', function (): void {
    $application = Application::factory()->create();

    $this->actingAs($this->admin)
        ->get("/admin/applications/{$application->id}")
        ->assertOk();

    expect($application->refresh()->status)->toBe(Application::STATUS_PROCESSED);
});
