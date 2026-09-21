<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TariffFile;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TariffFilePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TariffFile');
    }

    public function view(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('View:TariffFile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TariffFile');
    }

    public function update(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('Update:TariffFile');
    }

    public function delete(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('Delete:TariffFile');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TariffFile');
    }

    public function restore(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('Restore:TariffFile');
    }

    public function forceDelete(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('ForceDelete:TariffFile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TariffFile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TariffFile');
    }

    public function replicate(AuthUser $authUser, TariffFile $tariffFile): bool
    {
        return $authUser->can('Replicate:TariffFile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TariffFile');
    }
}
