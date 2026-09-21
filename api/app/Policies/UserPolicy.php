<?php

namespace App\Policies;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:User');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:User');
    }

    public function update(AuthUser $authUser, ?User $user = null): bool
    {
        return $authUser->can('Update:User') && $this->mayTouch($authUser, $user);
    }

    public function delete(AuthUser $authUser, ?User $user = null): bool
    {
        return $authUser->can('Delete:User') && $this->mayTouch($authUser, $user);
    }

    /**
     * Роль super_admin выдана списком всех прав, а не гейтом, и лежит в той же
     * выпадашке, что остальные. Без этой проверки право «править пользователей»
     * равнялось ей самой: сотрудник открывал свою карточку, добавлял себе роль
     * и получал всё, включая «Основные настройки», где код счётчиков уходит в
     * <head> сайта как есть. Тронуть супер-админа может только супер-админ.
     */
    private function mayTouch(AuthUser $authUser, ?User $user): bool
    {
        if ($user === null || ! $user->hasRole(Utils::getSuperAdminName())) {
            return true;
        }

        return $authUser instanceof User && $authUser->hasRole(Utils::getSuperAdminName());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:User');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:User');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:User');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:User');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:User');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:User');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:User');
    }
}
