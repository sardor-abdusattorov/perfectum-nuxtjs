<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Social;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Social');
    }

    public function view(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('View:Social');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Social');
    }

    public function update(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('Update:Social');
    }

    public function delete(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('Delete:Social');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Social');
    }

    public function restore(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('Restore:Social');
    }

    public function forceDelete(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('ForceDelete:Social');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Social');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Social');
    }

    public function replicate(AuthUser $authUser, Social $social): bool
    {
        return $authUser->can('Replicate:Social');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Social');
    }

}