@extends('layouts.app')

@section('title', 'Événements')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Tous les événements</h1>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('events.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un événement..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>
        <div class="col-md-4">
            <select name="sort" id="sort" class="form-select" onchange="window.location = this.value">
                <option value="{{ route('events.index', ['sort' => 'upcoming']) }}" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Prochainement</option>
                <option value="{{ route('events.index', ['sort' => 'price-asc']) }}" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Prix croissant</option>
                <option value="{{ route('events.index', ['sort' => 'price-desc']) }}" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Prix décroissant</option>
                <option value="{{ route('events.index', ['sort' => 'popularity']) }}" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularité</option>
            </select>
        </div>
    </div>
    
    @if($events->isEmpty())
        <div class="text-center py-5">
            <h3>Aucun événement trouvé</h3>
            <p>Essayez de modifier vos critères de recherche.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($events as $event)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                        @else
                            <img src="{{ asset('images/event-placeholder.jpg') }}" class="card-img-top" alt="{{ $event->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $event->venue }}
                            </p>
                            <p class="card-text text-muted">
                                <i class="fas fa-calendar-alt me-1"></i> {{ $event->start_date->format('d/m/Y H:i') }}
                            </p>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">{{ number_format($event->price, 2, ',', ' ') }} €</span>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection