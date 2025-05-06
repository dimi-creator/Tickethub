<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Mail\TicketMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller


{
        

    public function process(Request $request)
    {
        if ($request->expectsJson()) {
            // Générer et retourner l'order ID JSON pour PayPal SDK
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
        
            $totalAmount = session('ticket_data.amount') ?? 0;
        
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('payment.success'),
                    "cancel_url" => route('payment.cancel'),
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($totalAmount, 2, '.', '')
                        ],
                        "description" => "Paiement billet",
                    ]
                ]
            ]);
        
            if (!empty($response['id'])) {
                return response()->json(['id' => $response['id']]);
            }
        
            return response()->json(['error' => 'Erreur PayPal'], 500);
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'attendee_phone' => 'required|string|max:20',
        ]);
        
        $event = Event::findOrFail($validated['event_id']);
        
        // Vérifier la disponibilité des billets
        if ($event->available_tickets < $validated['quantity']) {
            return back()->with('error', 'Désolé, il ne reste pas assez de billets disponibles.');
        }
        
        // Calcul du montant total
        $totalAmount = $event->price * $validated['quantity'];
        
        // Stockage temporaire des infos dans la session
        session([
            'ticket_data' => [
                'event_id' => $event->id,
                'quantity' => $validated['quantity'],
                'attendee_name' => $validated['attendee_name'],
                'attendee_email' => $validated['attendee_email'],
                'attendee_phone' => $validated['attendee_phone'],
                'amount' => $totalAmount,
            ]
        ]);

        return redirect()->route('payments.pay');
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
        
        $response = $provider->capturePaymentOrder($request->token);
        
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
                    'ticket_number' => 'TIX-' . uniqid(),
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
            
            // Mise à jour du nombre de billets disponibles
            $event->available_tickets -= $ticketData['quantity'];
            $event->save();
            
            // Envoi des billets par email
            Mail::to($ticketData['attendee_email'])->send(new TicketMail($tickets, $event));
            
            // Nettoyage de la session
            session()->forget('ticket_data');
            
            return redirect()->route('tickets.confirmation')->with('transaction_id', $transaction->id);
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
    if (!session()->has('ticket_data')) {
        return redirect()->route('home')->with('error', 'Aucune information de commande trouvée.');
    }

    $ticketData = session('ticket_data');
    $event = \App\Models\Event::findOrFail($ticketData['event_id']);

    return view('payment.show', compact('ticketData', 'event'));
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

  public function createOrder(Request $request)
  {
      $accessToken = $this->getAccessToken();

      $response = Http::withToken($accessToken)
          ->post("{$this->baseUrl}/v2/checkout/orders", [
              'intent' => 'CAPTURE',
              'purchase_units' => [[
                  'amount' => [
                      'currency_code' => 'EUR',
                      'value' => '10.00',
                      'breakdown' => [
                          'item_total' => [
                              'currency_code' => 'EUR',
                              'value' => '10.00',
                          ]
                      ],
                  ],
                  'items' => [[
                      'name' => 'T-Shirt',
                      'description' => 'Super Fresh Shirt',
                      'sku' => 'sku01',
                      'unit_amount' => [
                          'currency_code' => 'EUR',
                          'value' => '10.00',
                      ],
                      'quantity' => '1',
                      'category' => 'PHYSICAL_GOODS',
                  ]]
              ]],
              'application_context' => [
                  'return_url' => url('/paypal/return'),
                  'cancel_url' => url('/paypal/cancel'),
                  'brand_name' => 'TicketHub',
                  'landing_page' => 'BILLING',
                  'user_action' => 'PAY_NOW',
              ]
          ]);

      if ($response->failed()) {
          Log::error('Erreur lors de la création de la commande PayPal', ['body' => $response->body()]);
          return response()->json(['error' => 'Erreur PayPal'], 500);
      }

      return response()->json($response->json());
  }

  public function captureOrder($orderId)
  {

    $orderID = $request->input('orderID');

    if (!$orderID) {
        return response()->json(['error' => 'ID de commande manquant'], 400);
    }
      $accessToken = $this->getAccessToken();

      $response = Http::withToken($accessToken)
          ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

      if ($response->failed()) {
          Log::error("Erreur lors de la capture de la commande PayPal: {$orderId}", ['body' => $response->body()]);
          return response()->json(['error' => 'Erreur lors de la capture'], 500);
      }

      return response()->json($response->json());
  }
}

  

