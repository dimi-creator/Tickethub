@extends('layouts.app')

@section('title', 'Mes événements')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Navigation</h5>
                    <div class="list-group">
                        <a href="{{ route('dashboard.organizer') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.organizer') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('dashboard.events') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.events') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt me-2"></i>Mes événements
                        </a>
                        <a href="{{ route('events.create') }}" class="list-group-item list-group-item-action {{ request()->routeIs('events.create') ? 'active' : '' }}">
                            <i class="fas fa-plus me-2"></i>Créer un événement
                        </a>
                        <a href="{{ route('dashboard.statistics') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.statistics') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-user-edit me-2"></i>Mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Mes événements</h2>
                        <a href="{{ route('events.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Créer un événement
                        </a>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form action="{{ route('dashboard.events') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un événement..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">Rechercher</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <select name="filter" id="filter" class="form-select" onchange="window.location = this.value">
                                <option value="{{ route('dashboard.events') }}" {{ !request('filter') ? 'selected' : '' }}>Tous les événements</option>
                                <option value="{{ route('dashboard.events', ['filter' => 'upcoming']) }}" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                                <option value="{{ route('dashboard.events', ['filter' => 'past']) }}" {{ request('filter') == 'past' ? 'selected' : '' }}>Passés</option>
                                <option value="{{ route('dashboard.events', ['filter' => 'draft']) }}" {{ request('filter') == 'draft' ? 'selected' : '' }}>Brouillons</option>
                                <option value="{{ route('dashboard.events', ['filter' => 'published']) }}" {{ request('filter') == 'published' ? 'selected' : '' }}>Publiés</option>
                                <option value="{{ route('dashboard.events', ['filter' => 'cancelled']) }}" {{ request('filter') == 'cancelled' ? 'selected' : '' }}>Annulés</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($events->isEmpty())
                        <div class="alert alert-info text-center">
                            <p class="mb-0">Vous n'avez pas encore créé d'événements.</p>
                            <a href="{{ route('events.create') }}" class="btn btn-primary mt-3">Créer votre premier événement</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Événement</th>
                                        <th>Date</th>
                                        <th>Prix</th>
                                        <th>Statut</th>
                                        <th>Billets</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="event-thumbnail me-3">
                                                        @if($event->image)
                                                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" width="60" height="60" class="rounded">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                                <i class="fas fa-calendar-alt text-secondary fa-lg"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $event->title }}</h6>
                                                        <small class="text-muted">{{ Str::limit($event->venue, 30) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $event->start_date->format('d/m/Y H:i') }}</td>
                                            <td>{{ number_format($event->price, 2, ',', ' ') }} fcfa</td>
                                            <td>
                                                @if($event->status == 'published')
                                                    <span class="badge bg-success">Publié</span>
                                                @elseif($event->status == 'draft')
                                                    <span class="badge bg-secondary">Brouillon</span>
                                                @else
                                                    <span class="badge bg-danger">Annulé</span>
                                                @endif
                                            </td>
                                            <td>{{ $event->total_tickets - $event->available_tickets }}/{{ $event->total_tickets }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-confirm" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                            data-event-id="{{ $event->id }}" 
                                                            data-event-title="{{ $event->title }}"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'événement <strong id="eventTitle"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="{{ route('events.destroy', $event->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration du modal de suppression
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const eventId = button.getAttribute('data-event-id');
                const eventTitle = button.getAttribute('data-event-title');
                
                const deleteForm = document.getElementById('deleteForm');
                const eventTitleElement = document.getElementById('eventTitle');
                
                deleteForm.action = `/events/${eventId}`;
                eventTitleElement.textContent = eventTitle;
            });
        }
    });
</script>
@endpush
@endsection