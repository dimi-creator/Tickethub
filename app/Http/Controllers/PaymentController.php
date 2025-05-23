<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;

use App\Models\Transaction;
use App\Mail\TicketConfirmation;
use App\Mail\TicketMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use PDF;


class PaymentController extends Controller


{
        
    public function createOrder(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email',
            'attendee_phone' => 'required|string',
        ]);

        $event = Event::findOrFail($request->event_id);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $totalPrice = $event->price * $request->quantity;

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => number_format($totalPrice, 2, '.', ''),
                ]
            ]],
            "application_context" => [
                "return_url" => route('paypal.capture-order'),
                "cancel_url" => route('paypal.cancel'),
            ]
        ]);

        session([
            'purchase_data' => $request->all(),
            'paypal_order_id' => $order['id'],
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }

        return redirect()->back()->with('error', 'Une erreur est survenue avec PayPal.');
     }



       public function success(Request $request)
    {
        // Vérifier que les données sont dans la session
        if (!session()->has('ticket_data')) {
            return redirect()->route('home')->with('error', 'Aucune information de commande trouvée.');
        }
        
        $ticketData = session('ticket_data');
        $event = Event::findOrFail($ticketData['event_id']);
        
        // Capture du paiement PayPal
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        
        $response = $provider->captureOrder($request->token);
        
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            // Création de la transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'payment_id' => $response['id'],
                'payer_id' => $response['payer']['payer_id'] ?? null,
                'amount' => $ticketData['amount'],
                'status' => 'completed',
                'payment_method' => 'paypal',
            ]);
            
            // Création des billets
            $tickets = [];
            for ($i = 0; $i < $ticketData['quantity']; $i++) {
                $ticket = Ticket::create([
                    'ticket_number' => 'TCK-' . strtoupper(Str::random(10)),
                    'event_id' => $event->id,
                    'user_id' => auth()->id(),
                    'attendee_name' => $ticketData['attendee_name'],
                    'attendee_email' => $ticketData['attendee_email'],
                    'attendee_phone' => $ticketData['attendee_phone'],
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
                
                $tickets[] = $ticket;
            }


             // Récupérer les données de paiement
               $paymentData = $request->all();

              // Définir la valeur de transaction_id dans la session
                 session()->flash('transaction_id', $paymentData['transactionId']);
                 session()->flash('attendee_email', $ticketData['attendee_email']);
                 session()->flash('orderID', $ticketData['orderID']);

            
            // Mise à jour du nombre de billets disponibles
            $event->available_tickets -= $ticketData['quantity'];
            $event->save();
            
            // Envoi des billets par email
            Mail::to($ticketData['attendee_email'])->send(new TicketMail($tickets, $event, $transaction->id, $transaction->amount));
            
            // Nettoyage de la session
            session()->forget('ticket_data');
            
            return redirect()->route('tickets.confirmation')->with([
                'success' => 'Le paiement a été complété.',
                'transaction_id' => $transaction->id,
                  
           ]);
        }
        
        return redirect()->route('events.show', $event)->with('error', 'Le paiement n\'a pas été complété.');
    }
    
    public function cancel()
    {
        if (!session()->has('ticket_data')) {
            return redirect()->route('home')->with('error', 'Aucune information de commande trouvée.');
        }
        
        $ticketData = session('ticket_data');
        $event = Event::findOrFail($ticketData['event_id']);
        
        session()->forget('ticket_data');
        
        return redirect()->route('events.show', $event)->with('error', 'Le paiement a été annulé.');
    }

    public function pay()
   {
       if (!session()->has('ticket_data')) {
        return redirect()->route('home')->with('error', 'Aucune information de commande trouvée.');
       }

       $ticketData = session('ticket_data');
       

       return view('payment.pay', compact('ticketData'));
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



  public function __construct()
  {
      $this->paypalClientId = config('services.paypal.client_id');
      $this->paypalSecret = config('services.paypal.secret');
      $this->baseUrl = 'https://api-m.sandbox.paypal.com'; // pour sandbox
  }

  private function getAccessToken()
  {
      $response = Http::withBasicAuth($this->paypalClientId, $this->paypalSecret)
          ->asForm()
          ->post("{$this->baseUrl}/v1/oauth2/token", [
              'grant_type' => 'client_credentials',
          ]);

      if ($response->failed()) {
          Log::error('Échec de génération du token PayPal', ['body' => $response->body()]);
          return response()->json(['error' => 'Erreur d\'authentification PayPal'], 500);
      }

      return $response->json()['access_token'];
  }

  



  



   public function captureOrder(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $orderId = session('paypal_order_id');

        $result = $provider->capturePaymentOrder($orderId);
         
        // dd($result);

        if ($result['status'] !== 'COMPLETED')
        {
            return redirect()->route('home')->with('error', 'Le paiement a échoué.');
        }

        $data = session('purchase_data');
        $event = Event::findOrFail($data['event_id']);

        // Créer le(s) billet(s)
        $tickets = [];
        for ($i = 0; $i < $data['quantity']; $i++) {
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'attendee_name' => $data['attendee_name'],
                'attendee_email' => $data['attendee_email'],
                'attendee_phone' => $data['attendee_phone'],
                'status' => 'paid',
                'ticket_number' => 'TCK-' . strtoupper(Str::random(10)),
                'transaction_id' => $result['id'],

                // 'pdf_path' => à générer si tu veux
            ]);
            $tickets[] = $ticket;
            

        }

        // Envoi de l'email avec le ticket
        foreach ($tickets as $ticket) {
            Mail::to($data['attendee_email'])->send(new TicketMail(
              $tickets, 
               $event, 
               $result['id'],

                $amount = $event->price * $data['quantity']
         ));
        }

        return redirect()->route('home')->with('success', 'Paiement réussi. Vos billets ont été envoyés par email.');
    }



     public function confirmation()
  {
    $transactionId = session('transaction_id');

    if (!$transactionId) {
        return redirect()->route('home')->with('error', 'Aucune transaction trouvée.');
    }

    $transaction = Transaction::with('tickets.event')->findOrFail($transactionId);
    $event = $transaction->event;
    $quantity = $transaction->tickets->count();
    $ticketCount = $transaction->tickets->count();

    return view('tickets.confirmation', compact('transaction', 'event', 'quantity', 'ticketCount'));
  }
    
}




  

 



  

