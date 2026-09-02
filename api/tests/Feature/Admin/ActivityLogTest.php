<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

it('renders the activity log page', function (): void {
    $this->withoutVite();

    $user = panelUser();
    foreach (['ViewAny:Activity', 'View:Activity'] as $ability) {
        $user->givePermissionTo(Permission::findOrCreate($ability, 'web'));
    }

    $this->actingAs($user->refresh())
        ->get(panel('/activity-logs'))
        ->assertOk()
        ->assertSee('ActivityOverviewWidget')
        ->assertSee('ActivityTrendChartWidget');
});
