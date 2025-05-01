<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\User;

/**
 * Class InvitationPolicy
 *
 * @package App\Policies
 */
class InvitationPolicy
{
    /**
     * Determine whether the user can view any invitations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow any user to view any invitations.
        return true;
    }

    /**
     * Determine whether the user can view the specified invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function view(User $user, Invitation $invitation): bool
    {
        // Allow any user to view a specific invitation.
        return true;
    }

    /**
     * Determine whether the user can create invitations.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow any user to create invitations.
        return true;
    }

    /**
     * Determine whether the user can update the specified invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function update(User $user, Invitation $invitation): bool
    {
        // Do not allow users to update invitations.
        return false;
    }

    /**
     * Determine whether the user can delete the specified invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function delete(User $user, Invitation $invitation): bool
    {
        // Do not allow users to delete invitations.
        return false;
    }

    /**
     * Determine whether the user can restore the specified invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function restore(User $user, Invitation $invitation): bool
    {
        // Do not allow users to restore invitations.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the specified invitation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invitation  $invitation
     * @return bool
     */
    public function forceDelete(User $user, Invitation $invitation): bool
    {
        // Do not allow users to permanently delete invitations.
        return false;
    }
}
