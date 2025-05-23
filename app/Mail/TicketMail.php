<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $this->amount = number_format($amount, 2, ',', ' ');
        $this->attendeeName = $tickets[0]->attendee_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Générer le PDF des billets
        $pdf = PDF::loadView('pdf.tickets', [
            'tickets' => $this->tickets,
            'event' => $this->event,
            'transactionId' => $this->transactionId,
            'purchaseDate' => $this->purchaseDate,
            'amount' => $this->amount
        ]);

        return $this->subject('Vos billets pour ' . $this->event->title)
                    ->view('emails.tickets')
                    ->attachData($pdf->output(), 'billets-' . $this->transactionId . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
