<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

pest()->extend(TestCase::class)

    ->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function panelUser(array $permissions = []): User
{
    $user = User::factory()->create();

    $user->assignRole(Role::findOrCreate('panel_user', 'web'));

    foreach ($permissions as $permission) {
        $user->givePermissionTo(Permission::findOrCreate($permission, 'web'));
    }

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    return $user->refresh();
}

function panel(string $path = ''): string
{
    return '/'.Filament\Facades\Filament::getPanel('admin')->getPath().$path;
}
