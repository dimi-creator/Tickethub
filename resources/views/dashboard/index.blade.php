@extends('layouts.app')

@section('title', 'Mon Tableau de Bord')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mon Tableau de Bord</h1>
    
    <div class="row">
        <!-- Sidebar de navigation -->
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
            <!-- Résumé -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Billets à venir</h6>
                                    <h2 class="mt-2 mb-0">{{ $upcomingTickets->count() }}</h2>
                                </div>
                                <i class="fas fa-ticket-alt fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-primary bg-opacity-75">
                            <a href="{{ route('dashboard.tickets') }}" class="text-white text-decoration-none small">
                                Voir tous mes billets
                            </a>
                            <i class="fas fa-angle-right text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Billets passés</h6>
                                    <h2 class="mt-2 mb-0">{{ $pastTickets->count() }}</h2>
                                </div>
                                <i class="fas fa-history fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-success bg-opacity-75">
                            <a href="{{ route('dashboard.tickets', ['filter' => 'past']) }}" class="text-white text-decoration-none small">
                                Historique des billets
                            </a>
                            <i class="fas fa-angle-right text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Transactions</h6>
                                    <h2 class="mt-2 mb-0">{{ $recentTransactions->count() }}</h2>
                                </div>
                                <i class="fas fa-receipt fa-2x opacity-50"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-info bg-opacity-75">
                            <a href="{{ route('dashboard.transactions') }}" class="text-white text-decoration-none small">
                                Voir les transactions
                            </a>
                            <i class="fas fa-angle-right text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Billets à venir -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Billets à venir</h5>
                    <a href="{{ route('dashboard.tickets') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    @if($upcomingTickets->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Vous n'avez pas de billets pour des événements à venir.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-1"></i> Explorer les événements
                            </a>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($upcomingTickets as $ticket)
                                <a href="{{ route('dashboard.tickets.show', $ticket) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $ticket->event->title }}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-calendar-alt me-1"></i> {{ $ticket->event->start_date->format('d/m/Y H:i') }}
                                                <span class="mx-1">|</span>
                                                <i class="fas fa-map-marker-alt me-1"></i> {{ $ticket->event->venue }}
                                            </p>
                                        </div>
                                        <span class="badge bg-primary">{{ $ticket->ticket_number }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Transactions récentes -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transactions récentes</h5>
                    <a href="{{ route('dashboard.transactions') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Vous n'avez pas encore effectué de transactions.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Événement</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $transaction->event->title }}</td>
                                            <td>{{ number_format($transaction->amount, 2, ',', ' ') }} €</td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection