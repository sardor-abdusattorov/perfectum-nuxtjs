<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TariffCategory;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TariffCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TariffCategory');
    }

    public function view(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('View:TariffCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TariffCategory');
    }

    public function update(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('Update:TariffCategory');
    }

    public function delete(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('Delete:TariffCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TariffCategory');
    }

    public function restore(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('Restore:TariffCategory');
    }

    public function forceDelete(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('ForceDelete:TariffCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TariffCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TariffCategory');
    }

    public function replicate(AuthUser $authUser, TariffCategory $tariffCategory): bool
    {
        return $authUser->can('Replicate:TariffCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TariffCategory');
    }
}
