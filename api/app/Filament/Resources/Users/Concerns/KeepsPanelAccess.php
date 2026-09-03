<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Concerns;

use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\Models\Role;

/**
 * The roles field syncs the relationship, so saving an account with only its
 * own role ticked used to detach `panel_user` — the role Shield checks before
 * it lets anyone through the door. The picker no longer offers that role, and
 * it is put back once the sync has run.
 */
trait KeepsPanelAccess
{
    protected function afterCreate(): void
    {
        $this->keepPanelAccess();
    }

    protected function afterSave(): void
    {
        $this->keepPanelAccess();
    }

    private function keepPanelAccess(): void
    {
        if (! Utils::isPanelUserRoleEnabled()) {
            return;
        }

        $name = Utils::getPanelUserRoleName();
        $guard = Utils::getFilamentAuthGuard() ?: (string) config('auth.defaults.guard');

        $role = Role::query()->where('name', $name)->where('guard_name', $guard)->first();

        if ($role !== null && ! $this->record->hasRole($role)) {
            $this->record->assignRole($role);
        }
    }
}
