<?php

namespace App\Policies;

use App\Models\Element;
use App\Models\User;

/**
 * Class ElementPolicy
 *
 * @package App\Policies
 */
class ElementPolicy
{
    /**
     * Determine whether the user can view any elements.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow only admins to view any elements.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can view the specified element.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Element  $element
     * @return bool
     */
    public function view(User $user, Element $element): bool
    {
        // Allow only admins to view a specific element.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can create elements.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow only admins to create elements.
        // return $user->is_admin();
        return false;
    }

    /**
     * Determine whether the user can update the specified element.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Element  $element
     * @return bool
     */
    public function update(User $user, Element $element): bool
    {
        // Allow only admins to update elements.
        return $user->is_admin();
    }

    /**
     * Determine whether the user can delete the specified element.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Element  $element
     * @return bool
     */
    public function delete(User $user, Element $element): bool
    {
        // Allow only super admins to delete elements.
        return is_super_admin();
    }

    /**
     * Determine whether the user can restore the specified element.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Element  $element
     * @return bool
     */
    public function restore(User $user, Element $element): bool
    {
        // Allow only super admins to restore elements.
        return is_super_admin();
    }

    /**
     * Determine whether the user can permanently delete the specified element.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Element  $element
     * @return bool
     */
    public function forceDelete(User $user, Element $element): bool
    {
        // Allow only super admins to permanently delete elements.
        return is_super_admin();
    }
}
