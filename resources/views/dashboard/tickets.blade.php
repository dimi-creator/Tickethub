@extends('layouts.app')

@section('title', 'Mes Billets')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mes Billets</h1>
    
    <div class="row">
        <!-- Sidebar de navigation - Identique à index.blade.php -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Menu</h5>
                    <div class="list-group">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('dashboard.tickets') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.tickets') ? 'active' : '' }}">
                            <i class="fas fa-ticket-alt me-2"></i>Mes billets
                        </a>
                        <a href="{{ route('dashboard.transactions') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.transactions') ? 'active' : '' }}">
                            <i class="fas fa-receipt me-2"></i>Mes transactions
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-user-edit me-2"></i>Mon profil
                        </a>
                        
                        @if(!auth()->user()->isOrganizer())
                            <a href="{{ route('organizer.create') }}" class="list-group-item list-group-item-action mt-2 text-primary">
                                <i class="fas fa-plus-circle me-2"></i>Devenir organisateur
                            </a>
                        @else
                            <a href="{{ route('dashboard.organizer') }}" class="list-group-item list-group-item-action mt-2 text-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Espace organisateur
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ request('filter') !== 'past' ? 'active' : '' }}" href="{{ route('dashboard.tickets') }}">À venir</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('filter') === 'past' ? 'active' : '' }}" href="{{ route('dashboard.tickets', ['filter' => 'past']) }}">Passés</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($tickets->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Aucun billet trouvé dans cette catégorie.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-1"></i> Explorer les événements
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Événement</th>
                                        <th>Date</th>
                                        <th>Numéro de billet</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->event->title }}</td>
                                            <td>{{ $ticket->event->start_date->format('d/m/Y H:i') }}</td>
                                            <td>{{ $ticket->ticket_number }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ticket->status === 'paid' ? 'success' : ($ticket->status === 'reserved' ? 'warning' : 'secondary') }}">
                                                    {{ $ticket->status === 'paid' ? 'Payé' : ($ticket->status === 'reserved' ? 'Réservé' : 'Utilisé') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('dashboard.tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dashboard.tickets.download', $ticket) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tickets->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
