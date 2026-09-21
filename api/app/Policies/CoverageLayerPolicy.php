<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CoverageLayer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CoverageLayerPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CoverageLayer');
    }

    public function view(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('View:CoverageLayer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CoverageLayer');
    }

    public function update(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('Update:CoverageLayer');
    }

    public function delete(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('Delete:CoverageLayer');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CoverageLayer');
    }

    public function restore(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('Restore:CoverageLayer');
    }

    public function forceDelete(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('ForceDelete:CoverageLayer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CoverageLayer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CoverageLayer');
    }

    public function replicate(AuthUser $authUser, CoverageLayer $coverageLayer): bool
    {
        return $authUser->can('Replicate:CoverageLayer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CoverageLayer');
    }
}
