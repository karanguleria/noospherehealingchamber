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
        // Admins and practitioners can list users (indexQuery scopes practitioner results).
        return in_array($user->type_id, [2, 3]);
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
        if ($user->is_admin()) {
            return true;
        }

        // Practitioners can view only their own clients.
        if ($user->is_practitioner()) {
            return (int) $model->type_id === 1 && (int) $model->practitioner_id === (int) $user->id;
        }

        // Members can only view themselves.
        return (int) $model->id === (int) $user->id;
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
