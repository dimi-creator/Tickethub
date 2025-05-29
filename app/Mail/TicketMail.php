<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tickets;
    public $event;
    public $transactionId;
    public $purchaseDate;
    public $amount;
    public $attendeeName;

    /**
     * Create a new message instance.
     *
     * @param array $tickets
     * @param Event $event
     * @param string $transactionId
     * @param float $amount
     * @return void
     */
    public function __construct(array $tickets, Event $event, $transactionId, $amount)
  {
    $this->tickets = $tickets;
    $this->event = $event;
    $this->transactionId = $transactionId;
    $this->purchaseDate = now()->format('d/m/Y H:i');
    $this->amount = $amount;
    
    $this->attendeeName = $tickets[0]->attendee_name ?? 'Participant inconnu';
  }


    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
 {
    $email = $this->subject('Vos billets pour ' . $this->event->title)
                  ->view('emails.tickets', [
                      'event' => $this->event,
                      'transactionId' => $this->transactionId,
                      'amount' => $this->amount,
                      'purchaseDate' => now()->format('d/m/Y H:i'),
                  ]);

    // Attacher tous les billets PDF
    foreach ($this->tickets as $ticket) {
        $pdf = PDF::loadView('pdf.tickets', [
            'ticket' => $ticket,
            'event' => $this->event,
            'transactionId' => $this->transactionId,
            'purchaseDate' => now()->format('d/m/Y H:i'),
            'amount' => $this->amount,
            'qrcode' => $ticket->qrcode ?? '', // Assure-toi que chaque ticket a son QR code
        ]);

        $email->attachData(
            $pdf->output(),
            'billet_' . $ticket->ticket_number . '.pdf',
            ['mime' => 'application/pdf']
        );
    }

    return $email;
 }

}
