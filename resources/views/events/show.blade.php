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
                        <span>{{ $event->total_available_tickets }}</span>
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
                    
                    
                </div>
            </div>
            
             <!-- Section des types de billets -->
            @if($event->hasAvailableTickets() && $event->start_date > now())
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Types de billets</h4>
                        
                        @foreach($event->ticketTypes()->where('is_active', true)->get() as $ticketType)
                            <div class="card mb-3 {{ $ticketType->available_quantity == 0 ? 'border-secondary' : 'border-primary' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $ticketType->name }}</h5>
                                        <span class="h5 text-primary mb-0">{{ number_format($ticketType->price, 2, ',', ' ') }} fcfa</span>
                                    </div>
                                    
                                    @if($ticketType->description)
                                        <p class="card-text text-muted small mb-2">{{ $ticketType->description }}</p>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-{{ $ticketType->available_quantity > 0 ? 'success' : 'danger' }}">
                                            {{ $ticketType->available_quantity > 0 ? $ticketType->available_quantity . ' disponibles' : 'Épuisé' }}
                                        </span>
                                        
                                        @if($ticketType->available_quantity > 0)
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#ticketModal"
                                                    data-ticket-type-id="{{ $ticketType->id }}"
                                                    data-ticket-type-name="{{ $ticketType->name }}"
                                                    data-ticket-type-price="{{ $ticketType->price }}"
                                                    data-available-quantity="{{ $ticketType->available_quantity }}">
                                                    Acheter
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                Épuisé
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'achat de billet -->
@if($event->hasAvailableTickets() && $event->start_date > now())
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Acheter un billet pour {{ $event->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="ticket-form" action="{{ route('payment.pay') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="ticket_type_id" id="selected_ticket_type_id">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 id="selected-ticket-type-name">type de billet</h6>
                        <p class="mb-1"><strong>Prix unitaire:</strong> <span id="selected-ticket-type-price"></span></p>
                        <p class="mb-0"><strong>Événement:</strong> {{ $event->title }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Nombre de billets</label>
                        <select name="quantity" id="quantity" class="form-select" required>
                            <!-- Options générées dynamiquement -->
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
                    
                    <div class="alert alert-warning">
                        <h6>Total à payer</h6>
                        <p class="mb-0 h5" id="total-amount">0.00 fcfa</p>

                    </div>
                </div>
                <div id="paypal-button-container" class="mb-3"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Procéder au paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('ticketModal');
    const quantitySelect = document.getElementById('quantity');
    const totalAmountElement = document.getElementById('total-amount');
    let currentPrice = 0;

    modal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const ticketTypeId = button.getAttribute('data-ticket-type-id');
        const ticketTypeName = button.getAttribute('data-ticket-type-name');
        const ticketTypePrice = parseFloat(button.getAttribute('data-ticket-type-price'));
        const ticketTypeMax = parseInt(button.getAttribute('data-available-quantity'));

        // Mettre à jour le type de billet sélectionné
        document.getElementById('selected_ticket_type_id').value = ticketTypeId;

        // Mettre à jour le nom du type de billet
        document.getElementById('selected-ticket-type-name').textContent = ticketTypeName;
        
        // Mettre à jour le prix unitaire
        document.getElementById('selected-ticket-type-price').textContent = `${ticketTypePrice.toFixed(2)} fcfa`;
        currentPrice = ticketTypePrice;

        // Générer les options pour la quantité (max 10 ou la quantité disponible)
        const maxQuantity = Math.min(10, ticketTypeMax);
        const quantityOptions = Array.from({ length: maxQuantity }, (_, i) => i + 1)
            .map(qty => `<option value="${qty}">${qty}</option>`)
            .join('');
        quantitySelect.innerHTML = quantityOptions;

        // Mettre à jour le total
        updateTotal();
    });

    quantitySelect.addEventListener('change', updateTotal);

    function updateTotal() {
        const quantity = parseInt(quantitySelect.value);
        const total = quantity * currentPrice;
        totalAmountElement.textContent = `${total.toFixed(2)} fcfa`;
    }
});
</script>

@endsection
@section('scripts')