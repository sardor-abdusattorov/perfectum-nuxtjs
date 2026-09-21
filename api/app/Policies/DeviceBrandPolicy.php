<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DeviceBrand;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DeviceBrandPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DeviceBrand');
    }

    public function view(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('View:DeviceBrand');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DeviceBrand');
    }

    public function update(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('Update:DeviceBrand');
    }

    public function delete(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('Delete:DeviceBrand');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DeviceBrand');
    }

    public function restore(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('Restore:DeviceBrand');
    }

    public function forceDelete(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('ForceDelete:DeviceBrand');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DeviceBrand');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DeviceBrand');
    }

    public function replicate(AuthUser $authUser, DeviceBrand $deviceBrand): bool
    {
        return $authUser->can('Replicate:DeviceBrand');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DeviceBrand');
    }
}
