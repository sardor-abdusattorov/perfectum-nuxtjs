<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FreeNumberFilter;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class FreeNumberFilterPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FreeNumberFilter');
    }

    public function view(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('View:FreeNumberFilter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FreeNumberFilter');
    }

    public function update(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('Update:FreeNumberFilter');
    }

    public function delete(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('Delete:FreeNumberFilter');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FreeNumberFilter');
    }

    public function restore(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('Restore:FreeNumberFilter');
    }

    public function forceDelete(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('ForceDelete:FreeNumberFilter');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FreeNumberFilter');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FreeNumberFilter');
    }

    public function replicate(AuthUser $authUser, FreeNumberFilter $freeNumberFilter): bool
    {
        return $authUser->can('Replicate:FreeNumberFilter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FreeNumberFilter');
    }
}
