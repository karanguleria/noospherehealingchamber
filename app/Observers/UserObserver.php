<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\SendEmailPractitioner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
class UserObserver
{
    // We'll store raw passwords here temporarily
    protected array $rawPasswords = [];

    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        if (!empty($user->password)) {
            // Save raw password in memory (not in the model)
            $this->rawPasswords[spl_object_id($user)] = $user->password;

            // Hash it before saving
            $user->password = Hash::make($user->password);
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->type_id == 2) {
            // Retrieve raw password from internal store
            $id = spl_object_id($user);
            $rawPassword = $this->rawPasswords[$id] ?? null;

            if ($rawPassword) {
                Mail::to($user->email)->send(new SendEmailPractitioner($user, $rawPassword));
                Log::info('SendEmailPractitioner email sent to user.', ['user_id' => $user->id]);
            } else {
                Log::warning('Raw password not found for user.', ['user_id' => $user->id]);
            }

            // Cleanup memory
            unset($this->rawPasswords[$id]);
        } else {
            Log::info('Email not sent. User is not type 2.', ['user_id' => $user->id]);
        }
    }
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
