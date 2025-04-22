@extends('layouts.app')

@section('title', 'Dashboard Organisateur')

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
                    <h2 class="card-title mb-4">Tableau de bord organisateur</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total des événements</h5>
                                    <p class="card-text fs-2">{{ $totalEvents ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Billets vendus</h5>
                                    <p class="card-text fs-2">{{ $totalTickets ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Revenus totaux</h5>
                                    <p class="card-text fs-2">{{ number_format($totalRevenue ?? 0, 2, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="mb-3">Événements à venir</h3>
                    
                    @if(isset($upcomingEvents) && count($upcomingEvents) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Événement</th>
                                        <th>Date</th>
                                        <th>Billets vendus</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingEvents as $event)
                                        <tr>
                                            <td>{{ $event->title }}</td>
                                            <td>{{ $event->start_date->format('d/m/Y H:i') }}</td>
                                            <td>{{ $event->total_tickets - $event->available_tickets }} / {{ $event->total_tickets }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">Vous n'avez pas d'événements à venir. <a href="{{ route('events.create') }}">Créez votre premier événement</a>.</p>
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('dashboard.events') }}" class="btn btn-outline-primary">Voir tous mes événements</a>
                        <a href="{{ route('events.create') }}" class="btn btn-primary">Créer un événement</a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Dernières ventes</h3>
                    
                    @if(isset($recentSales) && count($recentSales) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Événement</th>
                                        <th>Acheteur</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                        <tr>
                                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $sale->event->title }}</td>
                                            <td>{{ $sale->user->name }}</td>
                                            <td>{{ number_format($sale->amount, 2, ',', ' ') }} fcfa</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">Aucune vente récente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection