<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('renders the activity log page', function (): void {
    $this->withoutVite();

    $user = User::factory()->create();
    foreach (['ViewAny:Activity', 'View:Activity'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate($ability, 'web'));
    }

    $this->actingAs($user->refresh())
        ->get('/admin/activity-logs')
        ->assertOk()
        ->assertSee('ActivityOverviewWidget')
        ->assertSee('ActivityTrendChartWidget');
});
