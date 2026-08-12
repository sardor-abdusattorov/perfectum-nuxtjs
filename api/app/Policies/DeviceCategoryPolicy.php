<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DeviceCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeviceCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DeviceCategory');
    }

    public function view(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('View:DeviceCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DeviceCategory');
    }

    public function update(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('Update:DeviceCategory');
    }

    public function delete(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('Delete:DeviceCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DeviceCategory');
    }

    public function restore(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('Restore:DeviceCategory');
    }

    public function forceDelete(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('ForceDelete:DeviceCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DeviceCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DeviceCategory');
    }

    public function replicate(AuthUser $authUser, DeviceCategory $deviceCategory): bool
    {
        return $authUser->can('Replicate:DeviceCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DeviceCategory');
    }

}