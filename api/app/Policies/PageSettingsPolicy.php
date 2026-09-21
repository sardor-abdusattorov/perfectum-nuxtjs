<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PageSettings;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PageSettingsPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PageSettings');
    }

    public function view(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('View:PageSettings');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PageSettings');
    }

    public function update(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('Update:PageSettings');
    }

    public function delete(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('Delete:PageSettings');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PageSettings');
    }

    public function restore(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('Restore:PageSettings');
    }

    public function forceDelete(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('ForceDelete:PageSettings');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PageSettings');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PageSettings');
    }

    public function replicate(AuthUser $authUser, PageSettings $pageSettings): bool
    {
        return $authUser->can('Replicate:PageSettings');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PageSettings');
    }
}
