<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Mail\InvitationEmail;
use App\Services\NovaNotifier;
use Illuminate\Support\Facades\Mail;

class InvitationObserver
{
    /**
     * Handle the invitation "created" event.
     *
     * @param  \App\Invitation  $invitation
     * @return void
     */
    public function created(Invitation $invitation)
    {
        Mail::to($invitation->email)->send(new InvitationEmail($invitation->name,$invitation->practitioner->name,$invitation->practitioner->id));

        NovaNotifier::invitationCreated($invitation);
    }


    /**
     * Handle the Invitation "updated" event.
     */
    public function updated(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "deleted" event.
     */
    public function deleted(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "restored" event.
     */
    public function restored(Invitation $invitation): void
    {
        //
    }

    /**
     * Handle the Invitation "force deleted" event.
     */
    public function forceDeleted(Invitation $invitation): void
    {
        //
    }
}
