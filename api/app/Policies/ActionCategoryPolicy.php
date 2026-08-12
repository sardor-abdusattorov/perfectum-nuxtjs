<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ActionCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ActionCategory');
    }

    public function view(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('View:ActionCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ActionCategory');
    }

    public function update(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('Update:ActionCategory');
    }

    public function delete(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('Delete:ActionCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ActionCategory');
    }

    public function restore(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('Restore:ActionCategory');
    }

    public function forceDelete(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('ForceDelete:ActionCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ActionCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ActionCategory');
    }

    public function replicate(AuthUser $authUser, ActionCategory $actionCategory): bool
    {
        return $authUser->can('Replicate:ActionCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ActionCategory');
    }

}