<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SiteTranslation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SiteTranslationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SiteTranslation');
    }

    public function view(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('View:SiteTranslation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SiteTranslation');
    }

    public function update(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('Update:SiteTranslation');
    }

    public function delete(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('Delete:SiteTranslation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SiteTranslation');
    }

    public function restore(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('Restore:SiteTranslation');
    }

    public function forceDelete(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('ForceDelete:SiteTranslation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SiteTranslation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SiteTranslation');
    }

    public function replicate(AuthUser $authUser, SiteTranslation $siteTranslation): bool
    {
        return $authUser->can('Replicate:SiteTranslation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SiteTranslation');
    }
}
