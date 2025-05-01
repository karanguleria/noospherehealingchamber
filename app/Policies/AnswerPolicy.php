<?php

namespace App\Policies;

use App\Models\User;

/**
 * Class AnswerPolicy
 *
 * @package App\Policies
 */
class AnswerPolicy
{
    /**
     * Determine whether the user can view any answers.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // By default, do not allow users to view any answers.
        return false;
    }
}
