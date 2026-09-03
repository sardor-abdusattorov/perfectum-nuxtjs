<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->withoutVite();
});

/**
 * Shield offers the guard as a free text field, and the team typed the name of
 * the department into it. Spatie then matched nothing, so the account opened
 * the panel to an empty sidebar.
 */
it('keeps a role on the panel guard whatever is written into the field', function (): void {
    $role = Role::create(['name' => 'HR', 'guard_name' => 'HR']);

    expect($role->fresh()->guard_name)->toBe('web');

    $role->forceFill(['guard_name' => 'Отдел кадров'])->save();

    expect($role->fresh()->guard_name)->toBe('web');
});

it('keeps a permission on the panel guard too', function (): void {
    $permission = Permission::create(['name' => 'ViewAny:Vacancy', 'guard_name' => 'HR']);

    expect($permission->fresh()->guard_name)->toBe('web');
});

it('grants what the role carries once the guard is right', function (): void {
    $role = Role::create(['name' => 'HR', 'guard_name' => 'HR']);
    $role->givePermissionTo(Permission::findOrCreate('ViewAny:Vacancy', 'web'));

    $user = User::factory()->create();
    $user->assignRole($role, Role::findOrCreate(Utils::getPanelUserRoleName(), 'web'));

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    expect($user->fresh()->can('ViewAny:Vacancy'))->toBeTrue();
});

/**
 * The state the running site was actually in: three roles filed under their own
 * label, their permissions duplicated beneath them, and the accounts left
 * without the role that opens the door.
 */
it('repairs the guards, the duplicates and the panel access of a live database', function (): void {
    $canonical = collect(['ViewAny:Vacancy', 'View:Vacancy', 'Update:Vacancy'])
        ->map(fn (string $name): Permission => Permission::findOrCreate($name, 'web'));

    $strayIds = $canonical->map(function (Permission $permission): int {
        $id = DB::table('permissions')->insertGetId([
            'name' => $permission->name,
            'guard_name' => 'HR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id;
    });

    $roleId = DB::table('roles')->insertGetId([
        'name' => 'HR', 'guard_name' => 'HR', 'created_at' => now(), 'updated_at' => now(),
    ]);

    foreach ($strayIds as $permissionId) {
        DB::table('role_has_permissions')->insert(['permission_id' => $permissionId, 'role_id' => $roleId]);
    }

    $user = User::factory()->create();
    DB::table('model_has_roles')->insert([
        'role_id' => $roleId, 'model_type' => User::class, 'model_id' => $user->getKey(),
    ]);
    DB::table('model_has_roles')->where('model_id', $user->getKey())->where('role_id', '!=', $roleId)->delete();

    Role::findOrCreate(Utils::getPanelUserRoleName(), 'web');
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    expect($user->fresh()->can('ViewAny:Vacancy'))->toBeFalse();

    $this->artisan('roles:repair')->assertSuccessful();

    $user = $user->fresh();

    expect(DB::table('roles')->where('name', 'HR')->value('guard_name'))->toBe('web')
        ->and(DB::table('permissions')->where('guard_name', '!=', 'web')->count())->toBe(0)
        ->and(DB::table('permissions')->where('name', 'ViewAny:Vacancy')->count())->toBe(1)
        ->and($user->can('ViewAny:Vacancy'))->toBeTrue()
        ->and($user->hasRole(Utils::getPanelUserRoleName()))->toBeTrue()
        ->and($user->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))->toBeTrue();
});

/**
 * Saving an account with only its own role ticked used to detach panel_user and
 * lock the person out of the panel entirely.
 */
it('does not let the roles field take panel access away', function (): void {
    $hr = Role::findOrCreate('HR', 'web');
    $panel = Role::findOrCreate(Utils::getPanelUserRoleName(), 'web');

    $target = User::factory()->create();
    $target->assignRole($hr, $panel);

    $this->actingAs(panelUser(['ViewAny:User', 'View:User', 'Update:User']));

    Livewire::test(EditUser::class, ['record' => $target->getKey()])
        ->fillForm(['roles' => [$hr->getKey()]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($target->fresh()->hasRole('HR'))->toBeTrue()
        ->and($target->fresh()->hasRole(Utils::getPanelUserRoleName()))->toBeTrue();
});

/**
 * shield:super-admin hands the role every permission id there is, without
 * looking at the guard, so a single row left under another one aborts the whole
 * deploy with «There is no [permission] with ID …». The repair runs first in
 * project:update for exactly this reason.
 */
it('lets the deploy finish after a permission was left under another guard', function (): void {
    User::factory()->create();
    Permission::findOrCreate('ViewAny:Vacancy', 'web');

    DB::table('permissions')->insert([
        'name' => 'ViewAny:Vacancy', 'guard_name' => 'Tender',
        'created_at' => now(), 'updated_at' => now(),
    ]);

    expect(fn () => $this->artisan('shield:super-admin', ['--user' => '1', '--panel' => 'admin'])->run())
        ->toThrow(PermissionDoesNotExist::class);

    $this->artisan('roles:repair')->assertSuccessful();

    expect(DB::table('permissions')->where('guard_name', '!=', 'web')->count())->toBe(0);

    $this->artisan('shield:super-admin', ['--user' => '1', '--panel' => 'admin'])
        ->assertSuccessful();
});
