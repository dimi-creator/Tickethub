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
use Srmklive\PayPal\Services\PayPal as PayPalClient;

use BaconQrCode\Renderer\Image\GdImageBackend; // Backend GD
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use App\Mail\TicketsMail;

use App\Models\TicketType;




class TicketController extends Controller
{
     public function purchase(Request $request)
{
    $validated = $request->validate([
        'event_id' => 'required|exists:events,id',
        'ticket_type_id' => 'required|exists:ticket_types,id',
        'quantity' => 'required|integer|min:1',
        'attendee_name' => 'required|string|max:255',
        'attendee_email' => 'required|email|max:255',
        'attendee_phone' => 'required|string|max:20',
    ]);

    $event = Event::findOrFail($validated['event_id']);
    $ticketType = TicketType::findOrFail($validated['ticket_type_id']);
    $totalAmount = $ticketType->price * $validated['quantity'];

    // Crée un ordre PayPal ici (avec ton SDK PHP ou API REST)
    $provider = new PayPalClient();
    $provider->setApiCredentials(config('paypal'));
    $token = $provider->getAccessToken();
    $provider->setAccessToken($token);

    $order = $provider->createOrder([
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "EUR", // Convertis à l'euro ici
                "value" => number_format($totalAmount / 655.957, 2, '.', '') // FCFA -> EUR
            ]
        ]]
    ]);

    // Stocke les données en session
    session([
        'purchase_data' => $validated,
        'event_id' => $event->id,
        'ticket_type_id' => $ticketType->id,
        'orderID' => $order['id'],
    ]);
    // Redirige vers PayPal pour le paiement

    return response()->json(['orderID' => $order['id']]);
}

    

    /**
     * Page de confirmation après paiement réussi
     */
    public function confirmation()
    {
        $transaction = Transaction::with('tickets.event')->findOrFail(session('transaction_id'));

        if (!$transaction->tickets->count() || !$transaction->tickets->first()->event) {
            return redirect()->route('home')->with('error', 'Événement lié introuvable.');
        }

        $event = $transaction->tickets->first()->event;
        $ticketCount = $transaction->tickets->count();

        // // Nettoyer la session après confirmation
        session()->forget(['transaction_id', 'orderID', 'purchase_data']);


        return view('tickets.confirmation', [
           'transaction' => $transaction,
            'event' => $event,
             'quantity' => $ticketCount, // 👈 ici on passe bien "quantity"
         ]);

    }
}
 
    