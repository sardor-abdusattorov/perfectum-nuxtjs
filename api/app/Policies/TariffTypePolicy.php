<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TariffType;
use Illuminate\Auth\Access\HandlesAuthorization;

class TariffTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TariffType');
    }

    public function view(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('View:TariffType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TariffType');
    }

    public function update(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('Update:TariffType');
    }

    public function delete(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('Delete:TariffType');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TariffType');
    }

    public function restore(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('Restore:TariffType');
    }

    public function forceDelete(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('ForceDelete:TariffType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TariffType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TariffType');
    }

    public function replicate(AuthUser $authUser, TariffType $tariffType): bool
    {
        return $authUser->can('Replicate:TariffType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TariffType');
    }

}