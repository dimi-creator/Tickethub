@extends('layouts.app')

@section('title', 'Modifier l\'événement')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Navigation</h5>
                    <div class="list-group">
                        <a href="{{ route('dashboard.organizer') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('dashboard.events') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-alt me-2"></i>Mes événements
                        </a>
                        <a href="{{ route('events.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i>Créer un événement
                        </a>
                        <a href="{{ route('dashboard.statistics') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Modifier l'événement</h2>
                    
                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Titre de l'événement <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="venue" class="form-label">Lieu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue" value="{{ old('venue', $event->venue) }}" required>
                                @error('venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Date et heure de début <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                    name="start_date"
                                    value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
                                    required>
                               @error('start_date')
                               <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                           </div>
                           <div class="col-md-6">
                               <label for="end_date" class="form-label">Date et heure de fin <span class="text-danger">*</span></label>
                               <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                   name="end_date"
                                   value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" required>
                               @error('end_date')
                               <div class="invalid-feedback">{{ $message }}</div>
                               @enderror
                           </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_tickets" class="form-label">Nombre total de billets <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_tickets') is-invalid @enderror" id="total_tickets" name="total_tickets" value="{{ old('total_tickets', $event->total_tickets) }}" min="{{ $event->total_tickets - $event->available_tickets }}" required>
                                @error('total_tickets')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Minimum: {{ $event->total_tickets - $event->available_tickets }} (billets déjà vendus)
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="price" class="form-label">Prix du billet (fcfa) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $event->price) }}" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Publié</option>
                                    <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="image" class="form-label">Image de l'événement</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($event->image)
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="img-thumbnail me-2" style="height: 50px;">
                                            <span class="small text-muted">Image actuelle</span>
                                        </div>
                                        <div class="form-text">
                                            Télécharger une nouvelle image remplacera l'image actuelle.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard.events') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card shadow-sm mt-4">
                <div class="card-body p-4">
                    <h3 class="card-title text-danger">Zone de danger</h3>
                    <p>Une fois supprimé, cet événement ne pourra pas être restauré.</p>
                    
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Supprimer cet événement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection