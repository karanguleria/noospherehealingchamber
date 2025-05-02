<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\SendEmailPractitioner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserObserver
{
  
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
        if ($user->type_id == 2) {
            $rawPassword = request()->password ?? null;
            Mail::to($user->email)->send(new SendEmailPractitioner($user, $rawPassword));
            Log::info('SendEmailPractitioner email sent to user.', ['user_id' => $user->id]);
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
