<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;

/**
 * Class ResultPolicy
 *
 * @package App\Policies
 */
class ResultPolicy
{
    /**
     * Determine whether the user can view any results.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow any user to view any results.
        return true;
    }

    /**
     * Determine whether the user can view the specified result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function view(User $user, Result $result): bool
    {
        // Do not allow users to view a specific result.
        return false;
    }

    /**
     * Determine whether the user can create results.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow any user to create results.
        return false;
    }

    /**
     * Determine whether the user can update the specified result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function update(User $user, Result $result): bool
    {
        // Do not allow users to update results.
        return false;
    }

    /**
     * Determine whether the user can delete the specified result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function delete(User $user, Result $result): bool
    {
        // Do not allow users to delete results.
        return false;
    }

    /**
     * Determine whether the user can restore the specified result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function restore(User $user, Result $result): bool
    {
        // Do not allow users to restore results.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the specified result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return bool
     */
    public function forceDelete(User $user, Result $result): bool
    {
        // Do not allow users to permanently delete results.
        return false;
    }
}
