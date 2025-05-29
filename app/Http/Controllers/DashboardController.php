<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\ResponseException;








class DashboardController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // Rediriger les organisateurs vers leur tableau de bord spécifique
        if (auth()->user()->isOrganizer()) {
            return redirect()->route('dashboard.organizer');
        }

        // Dashboard pour utilisateurs réguliers
        $upcomingTickets = Ticket::where('user_id', auth()->id())
            ->whereHas('event', function($query) {
                $query->where('start_date', '>', now());
            })
            ->with('event')
            ->latest()
            ->take(5)
            ->get();
        
        $pastTickets = Ticket::where('user_id', auth()->id())
            ->whereHas('event', function($query) {
                $query->where('start_date', '<=', now());
            })
            ->with('event')
            ->latest()
            ->take(5)
            ->get();
        
        $recentTransactions = Transaction::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard.index', compact('upcomingTickets', 'pastTickets', 'recentTransactions'));
    }
    
    public function tickets()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.tickets', compact('tickets'));
    }
    

    // Ajout d'une méthode pour afficher les transactions
    public function transactions()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.transactions', compact('transactions'));
    }

      // Afficher un billet spécifique avec QR code
    public function showTicket(Ticket $ticket)
    {
        // Vérifier que l'utilisateur est bien propriétaire du billet
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }
        
        // Générer le QR code
        $qrCode = $this->generateQrCode($ticket->ticket_number);
        
        return view('dashboard.ticket-details', compact('ticket', 'qrCode'));
    }
    
    // Téléchargement d'un billet en PDF
    public function downloadTicket(Ticket $ticket)
    {
        // Vérifier que l'utilisateur est bien propriétaire du billet
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Non autorisé');
        }
        
        // Générer le QR code
        $qrCode = $this->generateQrCode($ticket->ticket_number);

        $pdf = Pdf::loadView('pdf.tickets', [
        'ticket' => $ticket,
        'qrCode' => $qrCode,
        'event' => $ticket->event,
        'transactionId' => $ticket->transaction ? $ticket->transaction->id : '---',
        'purchaseDate' => $ticket->created_at->format('d/m/Y') ?? now()->format('d/m/Y'),
        'amount' => $ticket->transaction ? $ticket->transaction->amount : '---',
        


       ]);
        
       return $pdf->download('ticket-'.$ticket->ticket_number.'.pdf');
        // return response()->download($pdfPath, 'ticket-'.$ticket->ticket_number.'.pdf');
    }


    /**
     * Génère un QR code pour un texte donné
     *
     * @param string $text Texte à encoder dans le QR code
     * @return string QR code encodé en base64
     */
    private function generateQrCode(string $text): string
    {
        try {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($text)
                ->size(200)
                ->margin(10)
                ->build();

            return $result->getDataUri();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du QR code: ' . $e->getMessage());
            throw new ResponseException('Erreur lors de la génération du QR code', 500);
        }
    }


    public function organizerDashboard()
    {
        // Vérifier si l'utilisateur est un organisateur
        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour accéder à cette section.');
        }
        
        $organizerId = auth()->user()->organizer->id;
        
        $totalEvents = Event::where('organizer_id', $organizerId)->count();
        
        $upcomingEvents = Event::where('organizer_id', $organizerId)
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        $totalTickets = 0;
        $totalRevenue = 0;
        
        $events = Event::where('organizer_id', $organizerId)->get();
        
        foreach ($events as $event) {
            $soldTickets = $event->total_tickets - $event->available_tickets;
            $totalTickets += $soldTickets;
            $totalRevenue += $soldTickets * $event->price;
        }
        
        $recentSales = Transaction::whereIn('event_id', $events->pluck('id'))
            ->where('status', 'completed')
            ->with(['event', 'user'])
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard.organizer', compact('totalEvents', 'upcomingEvents', 'totalTickets', 'totalRevenue', 'recentSales'));
    }
    
    public function events()
    {
        // Vérifier si l'utilisateur est un organisateur
        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour accéder à cette section.');
        }
        
        $organizerId = auth()->user()->organizer->id;
        
        $events = Event::where('organizer_id', $organizerId)
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('dashboard.events', compact('events'));
    }
    
    public function statistics()
    {
        // Vérifier si l'utilisateur est un organisateur
        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour accéder à cette section.');
        }
        
        $organizerId = auth()->user()->organizer->id;
        
        // Logique pour calculer les statistiques
        // ...
        
        return view('dashboard.statistics');
    }
}
