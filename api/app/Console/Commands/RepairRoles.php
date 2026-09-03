<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Spatie only honours a permission whose guard matches the guard the visitor is
 * authenticated with. A role saved under any other guard therefore grants
 * nothing at all, and the account it belongs to opens the panel to an empty
 * sidebar. This puts every role and permission back on the panel's own guard,
 * folds the duplicates that were created alongside them into the originals, and
 * hands every account back the role that lets it through the door.
 */
final class RepairRoles extends Command
{
    protected $signature = 'roles:repair {--dry-run : only report what would change}';

    protected $description = 'Put roles and permissions back on the panel guard';

    private bool $dry = false;

    public function handle(): int
    {
        $this->dry = (bool) $this->option('dry-run');
        $guard = Utils::getFilamentAuthGuard() ?: (string) config('auth.defaults.guard');

        $this->line('Гвард панели: '.$guard);

        if ($this->dry) {
            $this->warn('Пробный прогон — ничего не записывается.');
        }

        $this->repairPermissions($guard);
        $this->repairRoles($guard);
        $this->restorePanelAccess($guard);

        if (! $this->dry) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $this->line('Кэш прав сброшен.');
        }

        return self::SUCCESS;
    }

    private function repairPermissions(string $guard): void
    {
        $stray = Permission::query()->where('guard_name', '!=', $guard)->get();

        if ($stray->isEmpty()) {
            $this->info('Права: все на нужном гварде.');

            return;
        }

        $moved = $merged = 0;

        foreach ($stray as $permission) {
            $canonical = Permission::query()
                ->where('name', $permission->name)
                ->where('guard_name', $guard)
                ->first();

            if ($canonical === null) {
                $moved++;

                if (! $this->dry) {
                    $permission->forceFill(['guard_name' => $guard])->save();
                }

                continue;
            }

            $merged++;

            if ($this->dry) {
                continue;
            }

            $this->repoint('role_has_permissions', 'permission_id', 'role_id', $permission->getKey(), $canonical->getKey());
            $this->repoint('model_has_permissions', 'permission_id', 'model_id', $permission->getKey(), $canonical->getKey());

            $this->drop('permissions', $permission->getKey());
        }

        $this->info("Права: перенесено на {$guard} — {$moved}, дубликатов слито — {$merged}.");
    }

    private function repairRoles(string $guard): void
    {
        $stray = Role::query()->where('guard_name', '!=', $guard)->get();

        if ($stray->isEmpty()) {
            $this->info('Роли: все на нужном гварде.');

            return;
        }

        foreach ($stray as $role) {
            $canonical = Role::query()
                ->where('name', $role->name)
                ->where('guard_name', $guard)
                ->first();

            $this->line("  «{$role->name}»: гвард {$role->guard_name} → {$guard}".
                ($canonical !== null ? ' (сливается с существующей)' : ''));

            if ($this->dry) {
                continue;
            }

            if ($canonical === null) {
                $role->forceFill(['guard_name' => $guard])->save();

                continue;
            }

            $this->repoint('role_has_permissions', 'role_id', 'permission_id', $role->getKey(), $canonical->getKey());
            $this->repoint('model_has_roles', 'role_id', 'model_id', $role->getKey(), $canonical->getKey());

            $this->drop('roles', $role->getKey());
        }
    }

    /**
     * Moves the rows of a pivot from one id to another without tripping over the
     * pair it is already keyed by.
     */
    private function repoint(string $table, string $column, string $other, int $from, int $to): void
    {
        $taken = DB::table($table)->where($column, $to)->pluck($other)->all();

        DB::table($table)->where($column, $from)->whereIn($other, $taken)->delete();
        DB::table($table)->where($column, $from)->update([$column => $to]);
    }

    /**
     * Spatie builds the `users` relation from the guard, so a row carrying one
     * no provider answers for cannot be deleted through Eloquent at all — its
     * own deleting hook throws. The pivots are already detached by hand above,
     * so the row goes straight out.
     */
    private function drop(string $table, int $id): void
    {
        DB::table($table)->where('id', $id)->delete();
    }

    private function restorePanelAccess(string $guard): void
    {
        if (! Utils::isPanelUserRoleEnabled()) {
            return;
        }

        $name = Utils::getPanelUserRoleName();
        $role = Role::query()->where('name', $name)->where('guard_name', $guard)->first();

        if ($role === null) {
            $this->warn("Роли «{$name}» нет — панель не пустит никого, кроме super_admin.");

            return;
        }

        $without = User::query()
            ->whereDoesntHave('roles', fn ($query) => $query->where('roles.id', $role->getKey()))
            ->get();

        if ($without->isEmpty()) {
            $this->info("Доступ в панель: у всех есть «{$name}».");

            return;
        }

        foreach ($without as $user) {
            $this->line("  «{$name}» возвращена: {$user->email}");

            if (! $this->dry) {
                $user->assignRole($role);
            }
        }
    }
}
