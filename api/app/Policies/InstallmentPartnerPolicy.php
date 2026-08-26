<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InstallmentPartner;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstallmentPartnerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InstallmentPartner');
    }

    public function view(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('View:InstallmentPartner');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InstallmentPartner');
    }

    public function update(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('Update:InstallmentPartner');
    }

    public function delete(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('Delete:InstallmentPartner');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InstallmentPartner');
    }

    public function restore(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('Restore:InstallmentPartner');
    }

    public function forceDelete(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('ForceDelete:InstallmentPartner');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InstallmentPartner');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InstallmentPartner');
    }

    public function replicate(AuthUser $authUser, InstallmentPartner $installmentPartner): bool
    {
        return $authUser->can('Replicate:InstallmentPartner');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InstallmentPartner');
    }

}