@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                @else
                    <img src="{{ asset('images/event-placeholder.jpg') }}" class="card-img-top" alt="{{ $event->title }}">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $event->title }}</h1>
                    <p class="card-text text-muted">
                        Organisé par: {{ $event->organizer->company_name }}
                    </p>
                    
                    <div class="d-flex mb-3">
                        <div class="me-4">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <strong>Date:</strong> {{ $event->start_date->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Durée:</strong> {{ $event->start_date->diffForHumans($event->end_date, true) }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <strong>Lieu:</strong> {{ $event->venue }}
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description de l'événement</h5>
                        <p>{{ $event->description }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-{{ $event->available_tickets > 0 ? 'success' : 'danger' }}">
                                {{ $event->available_tickets > 0 ? $event->available_tickets . ' billets disponibles' : 'Complet' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary me-2">Retour</a>
                            @if($event->start_date > now() && $event->available_tickets > 0)
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                                    Acheter un billet
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Informations</h4>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Prix:</span>
                        <span class="text-primary fw-bold">{{ number_format($event->price, 2, ',', ' ') }} fcfa</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Début:</span>
                        <span>{{ $event->start_date->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Fin:</span>
                        <span>{{ $event->end_date->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Billets disponibles:</span>
                        <span>{{ $event->available_tickets }}</span>
                    </div>
                    
                    <hr>
                    
                    <h4 class="card-title mb-3">Organisateur</h4>
                    
                    <p>{{ $event->organizer->company_name }}</p>
                    
                    @if($event->organizer->website)
                        <p>
                            <i class="fas fa-globe me-2"></i>
                            <a href="{{ $event->organizer->website }}" target="_blank">Site web</a>
                        </p>
                    @endif
                    
                    @if($event->start_date > now() && $event->available_tickets > 0)
                        <div class="d-grid mt-4">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                                Acheter un billet
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($event->start_date > now() && $event->available_tickets > 0)
    <!-- Modal d'achat de billet -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Acheter un billet pour {{ $event->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('tickets.purchase') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Nombre de billets</label>
                            <select name="quantity" id="quantity" class="form-select" required>
                                @for($i = 1; $i <= min(10, $event->available_tickets); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attendee_name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="attendee_name" name="attendee_name" value="{{ Auth::user()->name ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attendee_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="attendee_email" name="attendee_email" value="{{ Auth::user()->email ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attendee_phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="attendee_phone" name="attendee_phone" value="{{ Auth::user()->phone ?? '' }}" required>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6>Récapitulatif</h6>
                            <p class="mb-1"><strong>Événement:</strong> {{ $event->title }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                            <p class="mb-1"><strong>Lieu:</strong> {{ $event->venue }}</p>
                            <p class="mb-0"><strong>Prix unitaire:</strong> {{ number_format($event->price, 2, ',', ' ') }} fcfa</p>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Procéder au paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection