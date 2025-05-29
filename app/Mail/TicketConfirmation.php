<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $tickets;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($tickets)
    {
        $this->tickets = $tickets;
        
        // Générer le PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.tickets', ['tickets' => $tickets]);
        
        // Sauvegarder temporairement le PDF dans le dossier temporaire système
        $this->pdfPath = sys_get_temp_dir() . '/tickets-' . now()->timestamp . '.pdf';
        $pdf->save($this->pdfPath);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre achat de billets')
                    ->markdown('emails.ticket-confirmation')
                    ->attach($this->pdfPath, [
                        'as' => 'billets.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Clean up after sending the email.
     */
    public function __destruct()
    {
        // Supprimer le fichier PDF temporaire
        if (file_exists($this->pdfPath)) {
            unlink($this->pdfPath);
        }
    }
}
