<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Models\Organizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Mail\TicketsMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    public function index()
    {
        $events = Event::where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->paginate(12);
            
        return view('events.index', compact('events'));
    }
    
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }
                
    public function create()
    {

       
        // Vérifier si l'utilisateur est un organisateur
        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour créer un événement.');
        }
        
        return view('events.create');
    
        
    }
    
    
   
    
    public function store(Request $request)
    {
        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour créer un événement.');
        }
        
        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'venue' => 'required|string',
        'start_date' => 'required|date|after:now',
        'end_date' => 'required|date|after:start_date',
        'image' => 'nullable|image|max:2048',
        'status' => 'required|in:draft,published',
        'ticket_types' => 'required|array|min:1',
        'ticket_types.*.name' => 'required|string|max:255',
        'ticket_types.*.price' => 'required|numeric|min:0',
        'ticket_types.*.total_quantity' => 'required|integer|min:1',
        'ticket_types.*.description' => 'nullable|string|max:500',
        'ticket_types.*.sort_order' => 'integer',
     ]);
    
     if ($request->hasFile('image')) {
        $path = $request->file('image')->store('events', 'public');
        $validated['image'] = $path;
     }
    
     $validated['organizer_id'] = auth()->user()->organizer->id;

     // Calcul automatique du total des billets
     $totalTickets = collect($validated['ticket_types'])->sum('total_quantity');
     $validated['total_tickets'] = $totalTickets;
     $validated['available_tickets'] = $totalTickets; // Facultatif, si ce champ existe
     $validated['status'] = 'draft'; // Par défaut, l'événement est créé en brouillon
     $minPrice = collect($validated['ticket_types'])->min('price');
        $validated['price'] = $minPrice;


    
     // Créer l'événement
     $event = Event::create($validated);
    
     // Créer les types de billets
     foreach ($validated['ticket_types'] as $ticketTypeData) {
        $event->ticketTypes()->create([
            'name' => $ticketTypeData['name'],
            'description' => $ticketTypeData['description'] ?? null,
            'price' => $ticketTypeData['price'],
            'total_quantity' => $ticketTypeData['total_quantity'],
            'available_quantity' => $ticketTypeData['total_quantity'],
            'sort_order' => $ticketTypeData['sort_order'] ?? 0,
            'is_active' => true,


        ]);
    }
    
    return redirect()->route('dashboard.events')->with('success', 'Événement créé avec succès.');
    }
    
    public function edit(Event $event)
    {  
        // $this->authorize('update', $event);
        
        // Vérifier si l'utilisateur est un organisateur
       

        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour créer un événement.');
        }
        
        return view('events.edit', compact('event'));
        
    }
    
    public function update(Request $request, Event $event)
    {

        if (!auth()->user()->isOrganizer()) {
            return redirect()->route('organizer.create')
                ->with('info', 'Vous devez d\'abord devenir organisateur pour créer un événement.');
        }
        
       
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_tickets' => 'required|integer|min:' . ($event->total_tickets - $event->available_tickets),
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,cancelled',
        ]);
        
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }
        
        // Mise à jour des tickets disponibles si le total change
        if ($validated['total_tickets'] != $event->total_tickets) {
            $soldTickets = $event->total_tickets - $event->available_tickets;
            $validated['available_tickets'] = $validated['total_tickets'] - $soldTickets;
        }
        
        $event->update($validated);
        // $event->save();
        
        return redirect()->route('dashboard.events')->with('success', 'Événement mis à jour avec succès.');
    }
    
    public function destroy(Event $event)
    {
        // $this->authorize('delete', $event);
        
        // Vérifier si l'événement a des tickets vendus
        if ($event->tickets()->whereIn('status', ['paid', 'used'])->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet événement car des billets ont été vendus.');
        }
        
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();
        
        return redirect()->route('dashboard.events')->with('success', 'Événement supprimé avec succès.');
    }
}
