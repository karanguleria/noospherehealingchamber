<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShowResultPractitioner extends Mailable
{
    use Queueable, SerializesModels;

        protected $practitioner_name, $name,$result_id;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $practitioner_name,$result_id)
    {
         $this->name = $name;
         $this->practitioner_name = $practitioner_name;
         $this->result_id = $result_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Nosphere Healing Submitted - Review Results',
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
            ->markdown('email.resultMentor')
            ->with([
                'name'  => ucfirst($this->name),
                'practitioner_name'  => ucfirst($this->practitioner_name),
                'result_id'  => $this->result_id
                
        ]);    
    }
    
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachmentPath = storage_path('/app/public/pdf/Nosphere Healing Results-'.$this->result_id.'.pdf'); // Replace with actual path
        if (file_exists($attachmentPath)) {
            return [
                
                Attachment::fromPath($attachmentPath)
                ->as('Nosphere Healing Results for '.$this->name.'.pdf')
                    ->withMime('application/pdf'),
            ];
        } else {
            Log::error('Attachment file not found: ' . $attachmentPath);
            return []; // Or handle the error differently
        }
    }
}
