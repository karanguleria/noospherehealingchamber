<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

/**
 * Class QuestionPolicy
 *
 * @package App\Policies
 */
class QuestionPolicy
{
    /**
     * Determine whether the user can view any questions.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Allow only users admin to view any questions.
        return $user->type_id == 3;
    }

    /**
     * Determine whether the user can view the specified question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return bool
     */
    public function view(User $user, Question $question): bool
    {
        // Allow any user to view a specific question.
        return true;
    }

    /**
     * Determine whether the user can create questions.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Allow any user to create questions.
        return false;
    }

    /**
     * Determine whether the user can update the specified question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return bool
     */
    public function update(User $user, Question $question): bool
    {
        // Allow any user to update questions.
        return true;
    }

    /**
     * Determine whether the user can delete the specified question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return bool
     */
    public function delete(User $user, Question $question): bool
    {
        // Allow any user to delete questions.
        return false;
    }

    /**
     * Determine whether the user can restore the specified question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return bool
     */
    public function restore(User $user, Question $question): bool
    {
        // Allow any user to restore questions.
        return true;
    }

    /**
     * Determine whether the user can permanently delete the specified question.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return bool
     */
    public function forceDelete(User $user, Question $question): bool
    {
        // Allow any user to permanently delete questions.
        return true;
    }
}
