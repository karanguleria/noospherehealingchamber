<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Mail\InvitationEmail;
use App\Mail\InvitationPractitionerEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // dd($invitation);
        // Log::debug('An informational message.',$invitation->name);
        // Log::stack(['single'])->info('Sending email invitation to '. $invitation->practitioner);
        // dd($invitation);
        // Mail::to($invitation->email)->queue(new InvitationEmail($invitation));
        // Mail::to($invitation->email)->queue(new InvitationEmail($invitation->name,$invitation->practitioner->name,$invitation->practitioner->id));
        Mail::to("himekaranguleria@gmail.com")->queue(new InvitationEmail("karan","practiciner karan",69));
        // Mail::to($invitation->email)->queue(new InvitationPractitionerEmail($invitation->name,$invitation->practitioner->name));
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
