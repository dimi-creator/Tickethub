<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use illuminate\support\str ;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take(6)
            ->get();
            
        return view('home', compact('upcomingEvents'));
    }
    
    public function about()
    {
        return view('about');
    }
    
    public function contact()
    {
        return view('contact');
    }
    
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Logique pour stocker le message ou envoyer un email
        
        return back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}

