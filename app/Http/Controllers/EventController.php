<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EventController;

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
            'total_tickets' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }
        
        $validated['organizer_id'] = auth()->user()->organizer->id;
        $validated['available_tickets'] = $validated['total_tickets'];
        
        Event::create($validated);
        
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
