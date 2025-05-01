<?php

namespace App\Policies;

use App\Models\Bodypart;
use App\Models\User;

/**
 * Class BodypartPolicy
 *
 * @package App\Policies
 */
class BodypartPolicy
{
    /**
     * Determine whether the user can view any body parts.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow only admins to view any body parts.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can view the specified body part.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bodypart  $bodypart
     * @return bool
     */
    public function view(User $user, Bodypart $bodypart): bool
    {
        // Allow only admins to view a specific body part.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can create body parts.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow only admins to create body parts.
        // return $user->is_admin();
        return false;
    }

    /**
     * Determine whether the user can update the specified body part.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bodypart  $bodypart
     * @return bool
     */
    public function update(User $user, Bodypart $bodypart): bool
    {
        // Allow only admins to update body parts.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can delete the specified body part.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bodypart  $bodypart
     * @return bool
     */
    public function delete(User $user, Bodypart $bodypart): bool
    {
        // Allow only super admins to delete body parts.
        return is_super_admin();
    }

    /**
     * Determine whether the user can restore the specified body part.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bodypart  $bodypart
     * @return bool
     */
    public function restore(User $user, Bodypart $bodypart): bool
    {
        // Allow only super admins to restore body parts.
        return is_super_admin();
    }

    /**
     * Determine whether the user can permanently delete the specified body part.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bodypart  $bodypart
     * @return bool
     */
    public function forceDelete(User $user, Bodypart $bodypart): bool
    {
        // Allow only super admins to permanently delete body parts.
        return is_super_admin();
    }
}
