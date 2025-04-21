<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;


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
        $tickets = Ticket::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->get();
            
        return view('dashboard.index', compact('tickets'));
    }
    
    public function tickets()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.tickets', compact('tickets'));
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
