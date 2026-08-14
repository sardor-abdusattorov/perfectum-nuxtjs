<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ApplicationTheme;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ApplicationThemePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ApplicationTheme');
    }

    public function view(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('View:ApplicationTheme');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ApplicationTheme');
    }

    public function update(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('Update:ApplicationTheme');
    }

    public function delete(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('Delete:ApplicationTheme');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ApplicationTheme');
    }

    public function restore(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('Restore:ApplicationTheme');
    }

    public function forceDelete(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('ForceDelete:ApplicationTheme');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ApplicationTheme');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ApplicationTheme');
    }

    public function replicate(AuthUser $authUser, ApplicationTheme $applicationTheme): bool
    {
        return $authUser->can('Replicate:ApplicationTheme');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ApplicationTheme');
    }
}
