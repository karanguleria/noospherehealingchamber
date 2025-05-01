<?php

namespace App\Mail;

use app\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

        protected $practitioner_name, $name, $id;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $practitioner_name,$id)
    {
         $this->name = $name;
         $this->practitioner_name = $practitioner_name;
         $this->id = $id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to Your Nosphere Healing: Uncover Your Body\'s Harmony',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invitation')
            ->from(env('MAIL_FROM_ADDRESS','invitation@example.com'), 'Nosphere Healing')
            ->bcc('himekaraguleria@gmail.com')
            ->markdown('email.invitation')
            ->with([
                'name'  => ucfirst($this->name),
                'practitioner_name'  => ucfirst($this->practitioner_name),
                'id'  => Crypt::encrypt($this->id),
        ]);    
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
