<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasAbility('user.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user): bool
    {
        return $user->hasAbility('user.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasAbility('user.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, User $users): bool
    {
        return $user->hasAbility('user.create');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, User $users): bool
    {
        return $user->hasAbility('user.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, User $users): bool
    {
        return $user->hasAbility('user.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $users): bool
    {
        return $user->hasAbility('user.force-delete');
    }
}
