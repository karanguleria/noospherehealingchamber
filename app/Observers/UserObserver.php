<?php

namespace App\Observers;

use App\Models\User;
use App\Mail\SendEmailPractitioner;
use App\Mail\WelcomeEmailPractitioner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
class UserObserver
{
  
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if ($user->type_id == 2) {
            $rawPassword = $user->plain_password ?? null;
            if($user->is_first_login == 1){
                $token = Password::createToken($user);
                $resetPasswordLink = url("/nova/password/reset/{$token}?email=" . urlencode($user->email));
                Mail::to($user->email)->send(new WelcomeEmailPractitioner($user, $rawPassword, $resetPasswordLink));
            }else{
                Mail::to($user->email)->send(new SendEmailPractitioner($user, $rawPassword));
            }
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
