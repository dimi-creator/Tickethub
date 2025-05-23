@extends('layouts.app')

@section('title', 'Détails du billet')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="bg-primary text-white p-4">
                        <h1 class="h3 mb-0">{{ $ticket->event->title }}</h1>
                        <p class="mb-0">{{ $ticket->event->venue }}</p>
                    </div>
                    
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h5>Informations de l'événement</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Date:</strong> {{ $ticket->event->start_date->format('d/m/Y') }}</li>
                                    <li class="mb-2"><strong>Heure:</strong> {{ $ticket->event->start_date->format('H:i') }}</li>
                                    <li class="mb-2"><strong>Lieu:</strong> {{ $ticket->event->venue }}</li>
                                    <li class="mb-2"><strong>Organisateur:</strong> {{ $ticket->event->organizer->company_name }}</li>
                                </ul>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Informations du billet</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Numéro:</strong> {{ $ticket->ticket_number }}</li>
                                    <li class="mb-2"><strong>Participant:</strong> {{ $ticket->attendee_name }}</li>
                                    <li class="mb-2"><strong>Email:</strong> {{ $ticket->attendee_email }}</li>
                                    <li class="mb-2">
                                        <strong>Statut:</strong> 
                                        <span class="badge bg-{{ $ticket->status === 'paid' ? 'success' : ($ticket->status === 'reserved' ? 'warning' : 'secondary') }}">
                                            {{ $ticket->status === 'paid' ? 'Payé' : ($ticket->status === 'reserved' ? 'Réservé' : 'Utilisé') }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center mb-4">
                            <div class="qr-code mb-3">
                                <!-- QR code du billet -->
                                @if ($qrCode)
                                  <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                                 @else
                                  <p>Le QR code n'a pas pu être généré.</p>
                                @endif
                            </div>
                            <p class="text-muted small">Présentez ce code QR à l'entrée de l'événement</p>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('dashboard.tickets') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour aux billets
                            </a>
                            <a href="{{ route('dashboard.tickets.download', $ticket) }}" class="btn btn-primary">
                                <i class="fas fa-download me-1"></i> Télécharger le billet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Détails de l'événement</h5>
                    <p class="card-text">{{ $ticket->event->description }}</p>
                    
                    <a href="{{ route('events.show', $ticket->event) }}" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-1"></i> Voir la page de l'événement
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection