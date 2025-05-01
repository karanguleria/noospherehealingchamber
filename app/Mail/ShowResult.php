<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ShowResult extends Mailable
{
    use Queueable, SerializesModels;
    public $result;
    public $excess,$balance,$insufficiency;
    public $practitioner_name, $name, $excess_message, $insufficiency_message, $balance_message,$result_id;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $practitioner_name, $excess, $insufficiency, $balance, $excess_message, $insufficiency_message, $balance_message,$result_id)
    {
      $this->practitioner_name = $practitioner_name;
      $this->name = $name;
      $this->excess = $excess;
      $this->insufficiency = $insufficiency;
      $this->balance = $balance;
      $this->excess_message = $excess_message;
      $this->insufficiency_message = $insufficiency_message;
      $this->balance_message = $balance_message;
      $this->result_id = $result_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Nosphere Healing Results',
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
                ->as('Nosphere Healing Results.pdf')
                    ->withMime('application/pdf'),
            ];
        } else {
            Log::error('Attachment file not found: ' . $attachmentPath);
            return []; // Or handle the error differently
        }
    }
    
     /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {              
        return $this->subject('Your Nosphere Healing Results')
            ->from(env('MAIL_FROM_ADDRESS','invitation@example.com'),'Nosphere Healing')
            ->bcc('himekaraguleria@gmail.com')
            ->markdown('email.result')
            ->with([
                'practitioner_name'  => $this->practitioner_name,
                'name' => $this->name,
                'excess'  => $this->excess,
                'balance'  => $this->balance,
                'insufficiency'  => $this->insufficiency,
                'excess_message'  => $this->excess_message,
                'balance_message'  => $this->balance_message,
                'insufficiency_message'  => $this->insufficiency_message,
        ]);    
    }
}
