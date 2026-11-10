<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class NovaNotifier
{
    public static function typeLabel(?int $typeId): string
    {
        return match ((int) $typeId) {
            1 => 'Client',
            2 => 'Practitioner',
            3 => 'Admin',
            4 => 'Premium Member',
            5 => 'Free Member',
            default => 'User',
        };
    }

    public static function displayName(User $user): string
    {
        $name = trim((string) $user->name);
        if ($name !== '') {
            return $name;
        }

        $full = trim(trim((string) ($user->first_name ?? '')) . ' ' . trim((string) ($user->last_name ?? '')));
        if ($full !== '') {
            return $full;
        }

        return (string) ($user->email ?: "User #{$user->id}");
    }

    /**
     * Admins + practitioners (Nova staff who should see org activity).
     *
     * @return Collection<int, User>
     */
    public static function staffRecipients(?int $excludeUserId = null): Collection
    {
        $recipients = User::query()
            ->whereIn('type_id', [2, 3])
            ->get();

        return static::uniqueNovaUsers($recipients, $excludeUserId);
    }

    /**
     * @return Collection<int, User>
     */
    public static function recipientsForSessionEvent(User $sessionUser, ?int $excludeUserId = null): Collection
    {
        // Same audience as user-creation alerts: all admins + practitioners.
        return static::staffRecipients($excludeUserId ?? $sessionUser->id);
    }

    /**
     * @param  Collection<int, User>  $recipients
     * @return Collection<int, User>
     */
    public static function uniqueNovaUsers(Collection $recipients, ?int $excludeUserId = null): Collection
    {
        return $recipients
            ->filter(fn ($user) => $user instanceof User)
            ->filter(fn (User $user) => in_array((int) $user->type_id, [2, 3, 4, 5], true))
            ->when($excludeUserId, fn (Collection $c) => $c->reject(fn (User $u) => (int) $u->id === (int) $excludeUserId))
            ->unique('id')
            ->values();
    }

    public static function send(
        Collection $recipients,
        string $message,
        string $icon = 'bell',
        string $type = NovaNotification::INFO_TYPE,
        ?string $actionText = null,
        ?string $actionUrl = null
    ): void {
        if ($recipients->isEmpty()) {
            Log::warning('NovaNotifier: no recipients', ['message' => $message]);

            return;
        }

        foreach ($recipients as $recipient) {
            try {
                $notification = NovaNotification::make()
                    ->message($message)
                    ->icon($icon)
                    ->type($type);

                if ($actionText && $actionUrl) {
                    $notification->action($actionText, URL::make($actionUrl));
                }

                $recipient->notify($notification);
            } catch (\Throwable $e) {
                Log::error('NovaNotifier failed', [
                    'recipient_id' => $recipient->id,
                    'message' => $message,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notify staff when any user type is created (client, practitioner, admin, members).
     */
    public static function userCreated(User $user): void
    {
        $label = static::typeLabel($user->type_id);
        $name = static::displayName($user);

        // All admins + practitioners see every new user; never exclude the creator.
        // Only skip notifying the newly created account itself.
        $recipients = static::staffRecipients($user->id);

        static::send(
            $recipients,
            "New {$label} created: {$name}",
            'user',
            NovaNotification::SUCCESS_TYPE,
            'View user',
            "/resources/users/{$user->id}"
        );
    }

    public static function invitationCreated($invitation): void
    {
        $name = $invitation->name ?? 'Guest';
        $recipients = static::staffRecipients();

        static::send(
            $recipients,
            "Invitation sent to \"{$name}\".",
            'mail',
            NovaNotification::INFO_TYPE
        );
    }

    public static function sessionStarted(UserSession $session): void
    {
        $session->loadMissing('user');
        $user = $session->user;

        if (!$user) {
            Log::warning('NovaNotifier: sessionStarted missing user', ['session_id' => $session->id]);

            return;
        }

        $healingType = $session->healing_type ?: ($session->type ?: 'healing');
        $healingType = ucfirst((string) $healingType);
        $name = static::displayName($user);

        static::send(
            static::recipientsForSessionEvent($user),
            "{$name} started a {$healingType} exercise.",
            'play',
            NovaNotification::INFO_TYPE,
            'View session',
            "/resources/user-sessions/{$session->id}"
        );
    }

    public static function sessionEnded(UserSession $session, bool $completed = false): void
    {
        $session->loadMissing('user');
        $user = $session->user;

        if (!$user) {
            Log::warning('NovaNotifier: sessionEnded missing user', ['session_id' => $session->id]);

            return;
        }

        $completed = $completed || static::isSessionComplete($session);
        $healingType = $session->healing_type ?: ($session->type ?: 'healing');
        $healingType = ucfirst((string) $healingType);
        $name = static::displayName($user);

        $message = $completed
            ? "{$name} completed a {$healingType} exercise."
            : "{$name} saved a {$healingType} exercise.";

        static::send(
            static::recipientsForSessionEvent($user),
            $message,
            $completed ? 'check-circle' : 'save',
            NovaNotification::SUCCESS_TYPE,
            'View session',
            "/resources/user-sessions/{$session->id}"
        );
    }

    public static function isSessionComplete(UserSession $session): bool
    {
        $value = $session->is_complete;

        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
