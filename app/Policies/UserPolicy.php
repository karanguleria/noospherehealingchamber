<?php

namespace App\Policies;

use App\Models\User;

/**
 * Class UserPolicy
 *
 * @package App\Policies
 */
class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow any user to view any users.
        return true;
    }

    /**
     * Determine whether the user can view the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        // Allow any user to view a specific user.
        return true;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow only admin users to create users.
        return is_admin();
    }

    /**
     * Determine whether the user can update the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        // Allow only admin users to update users.
        return is_admin();
    }

    /**
     * Determine whether the user can delete the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        // Allow only super admin users to delete users.
        return is_admin();
    }

    /**
     * Determine whether the user can restore the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        // Allow only admin users to restore users.
        return is_admin();
    }

    /**
     * Determine whether the user can permanently delete the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Allow only super admin users to permanently delete users.
        return is_admin();
    }
}
