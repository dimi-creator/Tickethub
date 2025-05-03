<?php

namespace App\Http\Controllers;
use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;




class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create()
    {
        // Vérifier si l'utilisateur est déjà un organisateur
        if (auth()->user()->isOrganizer()) {
            return redirect()->route('dashboard.organizer')
                ->with('info', 'Vous êtes déjà enregistré comme organisateur.');
        }
        
        return view('organizers.create');
    }
    
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est déjà un organisateur
        if (auth()->user()->isOrganizer()) {
            return redirect()->route('dashboard.organizer')
                ->with('info', 'Vous êtes déjà enregistré comme organisateur.');
        }
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizers', 'public');
            $validated['logo'] = $path;
        }
        
        $validated['user_id'] = auth()->id();
        
        Organizer::create($validated);
        
        return redirect()->route('dashboard.organizer')
            ->with('success', 'Félicitations ! Vous êtes maintenant un organisateur et pouvez créer des événements.');
    }


     /**
          * Update the organizer's profile information.
        */
   public function update(Request $request): RedirectResponse
   {
        $validated = $request->validate([
       'company_name' => 'required|string|max:255',
       'description' => 'nullable|string',
       'website' => 'nullable|url|max:255',
       'logo' => 'nullable|image|max:2048',
      ]);
   
         $organizer = auth()->user()->organizer;
   
         if ($request->hasFile('logo')) {
          // Supprimer l'ancien logo si existant
          if ($organizer->logo) {
           Storage::disk('public')->delete($organizer->logo);
         }
       
       $path = $request->file('logo')->store('organizers', 'public');
       $validated['logo'] = $path;
     }
   
      $organizer->update($validated);
   
     return Redirect::route('profile.edit')->with('success', 'Informations de l\'organisation mises à jour avec succès.');
   }
}