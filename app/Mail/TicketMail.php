<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tickets;
    public $event;

    /**
     * Create a new message instance.
     *
     * @param array $tickets
     * @param Event $event
     * @return void
     */
    public function __construct(array $tickets, Event $event)
    {
        
        $this->tickets = $tickets;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

     public function build()
    {
        return $this->subject('Vos billets pour ' . $this->event->title)
                    ->view('emails.tickets');
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket Mail',
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
        return [];
    }
}
