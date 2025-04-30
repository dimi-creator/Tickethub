<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'transaction_id' => $transaction->id,
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
    
    public function confirmation()
    {
        // Cette méthode sera utilisée après un paiement réussi
        $transaction = Transaction::with(['event', 'user'])->findOrFail(session('transaction_id'));
        
        return view('tickets.confirmation', compact('transaction'));
    }
}