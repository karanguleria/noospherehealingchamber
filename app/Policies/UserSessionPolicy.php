<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSession;

class UserSessionPolicy
{
    /**
     * Admin bypass.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->is_admin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->type_id, [2, 4, 5]);
    }

    public function view(User $user, UserSession $session): bool
    {
        // Practitioners see only their own clients' sessions.
        if ($user->is_practitioner()) {
            return (int) $session->user?->practitioner_id === (int) $user->id;
        }

        // Members see only their own sessions.
        return (int) $session->user_id === (int) $user->id;
    }

    public function update(User $user, UserSession $session): bool
    {
        if ($user->is_practitioner()) {
            return (int) $session->user?->practitioner_id === (int) $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function delete(User $user, UserSession $session): bool
    {
        return false;
    }
}
