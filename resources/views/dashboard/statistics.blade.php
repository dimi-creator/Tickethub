@extends('layouts.app')

@section('title', 'Statistiques')

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
                    <h2 class="card-title mb-4">Statistiques de vos événements</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Ventes totales</h5>
                                    <p class="card-text fs-2">{{ $totalSales ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Revenus (Fcfa)</h5>
                                    <p class="card-text fs-2">{{ number_format($totalRevenue ?? 0, 2, ',', ' ') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Taux de conversion</h5>
                                    <p class="card-text fs-2">{{ $conversionRate ?? 0 }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Graphique des ventes mensuelles -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Ventes mensuelles</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlySalesChart" height="300"></canvas>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Graphique des événements les plus populaires -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Événements les plus populaires</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="topEventsChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Graphique de répartition des ventes par jour -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Répartition des ventes par jour</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesByDayChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tableau des performances des événements -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Performance des événements</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($eventPerformance) && count($eventPerformance) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Événement</th>
                                                <th>Billets vendus</th>
                                                <th>Revenus (Fcfa)</th>
                                                <th>Taux de remplissage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($eventPerformance as $event)
                                                <tr>
                                                    <td>{{ $event->title }}</td>
                                                    <td>{{ $event->sold_tickets }} / {{ $event->total_tickets }}</td>
                                                    <td>{{ number_format($event->revenue, 2, ',', ' ') }}</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                style="width: {{ $event->fill_rate }}%" 
                                                                aria-valuenow="{{ $event->fill_rate }}" 
                                                                aria-valuemin="0" 
                                                                aria-valuemax="100">{{ $event->fill_rate }}%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-0">Aucune donnée de performance disponible pour le moment. Les statistiques seront affichées une fois que vous aurez vendu des billets.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour les graphiques (à remplacer par les données réelles)
        const monthlySalesData = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Ventes',
                data: {{ json_encode($monthlySales ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) }},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };
        
        const topEventsData = {
            labels: {!! json_encode($topEventsNames ?? ['Aucun événement']) !!},
            datasets: [{
                label: 'Billets vendus',
                data: {{ json_encode($topEventsSales ?? [0]) }},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        };
        
        const salesByDayData = {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Ventes par jour',
                data: {{ json_encode($salesByDay ?? [0, 0, 0, 0, 0, 0, 0]) }},
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };
        
        // Création des graphiques
        const monthlySalesChart = new Chart(
            document.getElementById('monthlySalesChart').getContext('2d'),
            {
                type: 'bar',
                data: monthlySalesData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true
                }
            }
        );
        
        const topEventsChart = new Chart(
            document.getElementById('topEventsChart').getContext('2d'),
            {
                type: 'pie',
                data: topEventsData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            }
        );
        
        const salesByDayChart = new Chart(
            document.getElementById('salesByDayChart').getContext('2d'),
            {
                type: 'line',
                data: salesByDayData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true
                }
            }
        );
    });
</script>
@endpush
@endsection