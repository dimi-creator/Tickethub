<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use BaconQrCode\Renderer\Image\GdImageBackend; // Backend GD
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class TicketController extends Controller
{
    public function purchase(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'attendee_phone' => 'required|string|max:20',
        ]);
        
        // Récupérer l'événement
        $event = Event::findOrFail($validated['event_id']);
        
        // Vérifier si l'événement est publié et s'il reste assez de billets
        if ($event->status !== 'published' || $event->available_tickets < $validated['quantity']) {
            return redirect()->back()->with('error', 'Désolé, ces billets ne sont plus disponibles.');
        }
        
        // Calculer le montant total
        $totalAmount = $event->price * $validated['quantity'];
        
        // Créer une transaction en attente
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'credit_card' // Valeur par défaut, sera mise à jour
        ]);
        
        // Enregistrer les infos de transaction en session pour le paiement
        session([
            'orderID' => $transaction->id,
            'event_id' => $event->id,
            'quantity' => $validated['quantity'],
            'attendee_name' => $validated['attendee_name'],
            'attendee_email' => $validated['attendee_email'],
            'attendee_phone' => $validated['attendee_phone'],
            'amount' => $totalAmount
        ]);
        
        // Rediriger vers la page de paiement
        return redirect()->route('payment.show');
    }
    
   public function show()
  {
    $transactionId = session('transaction_id');

    if (!$transactionId) {
        return redirect()->route('home')->with('error', 'Aucune transaction trouvée.');
    }

    $transaction = Transaction::with(['tickets.event'])->findOrFail($transactionId);

    return view('tickets.confirmation', compact('transaction'));
  }


    public function sendTickets(Request $request)
    {
        // Récupérer l'orderID depuis la session
        $orderID = session('orderID');

        // Vérifier si l'orderID existe dans la session
        if (!$orderID) {
            return redirect()->route('home')->with('error', 'Aucune transaction trouvée.');
        }

        try {
            // Récupérer la transaction avec ses relations
            $transaction = Transaction::with(['event', 'user'])
                ->where('payment_id', $orderID)
                ->where('user_id', auth()->id()) // Vérifier que la transaction appartient à l'utilisateur
                ->firstOrFail();

            // Créer les billets pour la transaction
            for ($i = 0; $i < $transaction->quantity; $i++) {
                Ticket::create([
                    'transaction_id' => $transaction->id,
                    'event_id' => $transaction->event_id,
                    'attendee_name' => $transaction->attendee_name,
                    'attendee_email' => $transaction->attendee_email,
                    'attendee_phone' => $transaction->attendee_phone,
                    'ticket_code' => Str::random(10), // Générer un code de billet aléatoire
                ]);
            }

            // Envoyer les billets par email
            Mail::to($transaction->user->email)->send(new TicketMail($transaction));

            // Retourner une réponse de succès
            return redirect()->route('home')->with('success', 'Les billets ont été envoyés par email.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Gérer le cas où la transaction n'est pas trouvée
            return redirect()->route('home')->with('error', 'Transaction introuvable.');
        }
    }

    

public function confirmation()
  {
    $transactionId = session('transaction_id');

    if (!$transactionId) {
        return redirect()->route('home')->with('error', 'Aucune transaction trouvée.');
    }

    $transaction = Transaction::with('tickets.event')->findOrFail($transactionId);
    $event = $transaction->tickets->first()->event; // Récupérer l'événement associé au premier billet
    $ticketCount = $transaction->tickets->count(); // Compter le nombre de billets
    $quantity = session('quantity', 1); // Récupérer la quantité depuis la session

    return view('tickets.confirmation', compact('transaction', 'event', 'quantity', 'ticketCount'));
 }

}   
    