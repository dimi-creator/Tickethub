@extends('layouts.app')

@section('title', 'Mes Transactions')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mes Transactions</h1>
    
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
                <div class="card-body">
                    @if($transactions->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">Vous n'avez pas encore effectué de transactions.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search me-1"></i> Explorer les événements
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Événement</th>
                                        <th>Référence</th>
                                        <th>Montant</th>
                                        <th>Méthode</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $transaction->event->title }}</td>
                                            <td>{{ $transaction->payment_id }}</td>
                                            <td>{{ number_format($transaction->amount, 2, ',', ' ') }} €</td>
                                            <td>{{ ucfirst($transaction->payment_method) }}</td>
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection