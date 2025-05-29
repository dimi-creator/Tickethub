<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\TicketType;
use App\Mail\TicketConfirmation;
use App\Mail\TicketMail;
use App\Mail\TicketsMail;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class PaymentController extends Controller
{
    private $paypalClientId;
    private $paypalSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->paypalClientId = config('paypal.sandbox.client_id');
        $this->paypalSecret = config('paypal.sandbox.secret');
        $this->baseUrl = 'https://api-m.sandbox.paypal.com';
    }

    public function pay(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'ticket_type_id' => 'required|exists:ticket_types,id',
                'quantity' => 'required|integer|min:1',
                'attendee_name' => 'required|string|max:255',
                'attendee_email' => 'required|email',
                'attendee_phone' => 'required|string'
            ]);

            // Récupération des données
            $event = Event::findOrFail($validated['event_id']);
            $ticketType = TicketType::findOrFail($validated['ticket_type_id']);

            // Vérification de la disponibilité des billets
            if ($event->total_available_tickets < $validated['quantity']) {
                return redirect()->back()->with('error', 'Pas assez de billets disponibles pour cet événement');
            }

            // Vérification que l'utilisateur est connecté
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer un achat');
            }

            // Stockage temporaire des données en session
            session()->put('purchase_data', [
                'event_id' => $validated['event_id'],
                'ticket_type_id' => $validated['ticket_type_id'],
                'quantity' => $validated['quantity'],
                'attendee_name' => $validated['attendee_name'],
                'attendee_email' => $validated['attendee_email'],
                'attendee_phone' => $validated['attendee_phone'],
                'total_price' => $ticketType->price * $validated['quantity']
            ]);

            // Retourner la vue avec les données nécessaires
            return view('payment.pay', [
                'event' => $event,
                'ticketType' => $ticketType,
                'quantity' => $validated['quantity'],
                'totalPrice' => $ticketType->price * $validated['quantity']
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement');
        }
    }


    public function createOrder(Request $request)
    {
        try {
            $data = $request->json()->all();
            
            // Vérifier que les données nécessaires sont présentes
            if (!isset($data['event_id'], $data['quantity'], $data['attendee_name'], $data['attendee_email'], $data['attendee_phone'])) {
                return response()->json(['error' => 'Données manquantes'], 400);
            }

            $event = Event::findOrFail($data['event_id']);
            $ticketType = $event->ticketTypes()->where('is_active', true)->first();

            if (!$ticketType) {
                return response()->json(['error' => 'Type de billet non trouvé'], 404);
            }

            // Vérifier la disponibilité des billets
            if ($ticketType->available_quantity < $data['quantity']) {
                return response()->json(['error' => 'Pas assez de billets disponibles'], 400);
            }

            // Calculer le montant total
            $totalAmount = $ticketType->price * $data['quantity'];

            // Créer l'ordre de paiement
            $provider = new PayPalService;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $orderData = [
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('payment.captureOrder'),
                    'cancel_url' => route('payment.cancel'),
                ],
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'EUR',
                            'value' => number_format($totalAmount, 2, '.', ''),
                        ],
                        'description' => sprintf('%d billets pour %s', $data['quantity'], $event->title),
                    ],
                ],
            ];

            $order = $provider->createOrder($orderData);

            return response()->json($order);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'ordre de paiement: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de l\'ordre de paiement'], 500);
        }
    }

    public function captureOrder(Request $request)
    {
        $provider = new PayPalService;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        // Récupérer l'order ID envoyé par JavaScript
        $orderId = $request->input('orderID');

        if (!$orderId) {
            return response()->json(['error' => 'Order ID manquant.'], 400);
        }

        $result = $provider->capturePaymentOrder($orderId);

        if (!isset($result['status']) || $result['status'] !== 'COMPLETED') {
            return response()->json(['error' => 'Le paiement a échoué.'], 500);
        }

        $data = session('purchase_data', []);

        if (!isset(
            $data['event_id'],
            $data['ticket_type_id'],
            $data['quantity'],
            $data['attendee_name'],
            $data['attendee_email'],
            $data['attendee_phone']
        )) {
            return response()->json(['error' => 'Données de session incomplètes.'], 500);
        }

        $event = Event::findOrFail($data['event_id']);
        $ticketType = TicketType::findOrFail($data['ticket_type_id']);
        $userId = Auth::id();

        // Créer la transaction
        $transaction = Transaction::create([
            'order_id' => $orderId,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => $data['quantity'],
            'attendee_name' => $data['attendee_name'],
            'attendee_email' => $data['attendee_email'],
            'attendee_phone' => $data['attendee_phone'],
            'amount' => $data['total_price'],
            'status' => 'completed',
            'payment_method' => 'paypal',
            'user_id' => $userId
        ]);

        // Créer les tickets avec QR code
        $tickets = collect();
        for ($i = 0; $i < $data['quantity']; $i++) {
            // Générer le QR code
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data(Str::uuid()) // Utiliser un UUID unique pour chaque ticket
                ->size(100)
                ->margin(10)
                ->build();
            
            $qrCode = $result->getDataUri();

            // Générer un numéro de ticket unique
            $ticketNumber = 'TICKET-' . now()->format('YmdHis') . '-' . Str::random(4);
            
            $ticket = Ticket::create([
                'ticket_number' => $ticketNumber,
                'transaction_id' => $transaction->id,
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'attendee_name' => $data['attendee_name'],
                'attendee_email' => $data['attendee_email'],
                'attendee_phone' => $data['attendee_phone'],
                'user_id' => $userId,
                'status' => 'paid',
                'qr_code' => $qrCode // Stocker le QR code dans la base de données
            ]);
            $tickets->push($ticket);
        }

        // Mettre à jour les tickets disponibles de l'événement
        $event->available_tickets -= $data['quantity'];
        $event->save();

        // Envoyer les emails
        Mail::to($data['attendee_email'])->send(new TicketConfirmation($tickets));

        return response()->json([
            'message' => 'Paiement confirmé, billets envoyés par email.'
        ]);
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
}


 





  

 



  

