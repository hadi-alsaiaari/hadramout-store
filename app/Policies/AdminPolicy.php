<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return $user->hasAbility('admin.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user): bool
    {
        return $user->hasAbility('admin.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasAbility('admin.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Admin $admin): bool
    {
        return $user->hasAbility('admin.create');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Admin $admin): bool
    {
        return $user->hasAbility('admin.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Admin $admin): bool
    {
        return $user->hasAbility('admin.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Admin $admin): bool
    {
        return $user->hasAbility('admin.force-delete');
    }
}
