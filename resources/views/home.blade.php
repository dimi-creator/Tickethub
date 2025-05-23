@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Trouvez et créez des événements incroyables</h1>
                <p class="lead">TicketHub est la plateforme idéale pour découvrir des événements près de chez vous ou pour organiser vos propres événements et vendre des billets.</p>
                <div class="mt-4">
                    <a href="{{ route('events.index') }}" class="btn btn-light btn-lg me-2 rounded-pill">Parcourir les événements</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg rounded-pill">Créer un compte</a>
                    @else
                        @if(Auth::user()->isOrganizer())
                            <a href="{{ route('events.create') }}" class="btn btn-outline-light btn-lg rounded-pill">Créer un événement</a>
                        @else
                            <a href="{{ route('organizer.create') }}" class="btn btn-outline-light btn-lg rounded-pill">Devenir organisateur</a>
                        @endif
                    @endguest
                </div>
            </div>
            <div class="col-md-6">
                <!-- <img src="{{ asset('images/hero-image.jpg') }}" alt="Événements" class="img-fluid rounded"> -->
            </div>
        </div>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Événements à venir</h2>
        
        @if($upcomingEvents->isEmpty())
            <div class="text-center py-5">
                <h3>Aucun événement à venir pour le moment</h3>
                <p>Revenez bientôt pour découvrir nos nouveaux événements!</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($upcomingEvents as $event)
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
                                <p class="card-text">{{ \illuminate\Support\Str::limit($event->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">{{ number_format($event->price, 2, ',', ' ') }} fcfa</span>
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('events.index') }}" class="btn btn-primary">Voir tous les événements</a>
            </div>
        @endif
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="text-center">
                    <i class="fas fa-ticket-alt fa-3x text-primary mb-3"></i>
                    <h3>Achetez des billets</h3>
                    <p>Trouvez des événements et achetez des billets en quelques clics. Recevez-les directement par email.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="text-center">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h3>Organisez des événements</h3>
                    <p>Créez et gérez facilement vos propres événements. Suivez les ventes en temps réel.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h3>Analysez vos performances</h3>
                    <p>Accédez à des statistiques détaillées sur vos ventes et optimisez vos événements.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="page-section bg-white">
    <div class="container">
        <h2 class="section-title text-center">Ce que disent nos utilisateurs</h2>
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/avatar1.jpg" alt="Avatar" class="testimonial-img">
                        <div>
                            <h5 class="mb-0">Marie NGO MOUN</h5>
                            <small class="text-muted">Utilisatrice régulière</small>
                        </div>
                    </div>
                    <p>"J'utilise TicketHub depuis plus d'un an et je suis toujours impressionnée par la facilité d'utilisation et la diversité des événements proposés."</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/avatar2.jpg" alt="Avatar" class="testimonial-img">
                        <div>
                            <h5 class="mb-0">Jean Martin</h5>
                            <small class="text-muted">Organisateur d'événements</small>
                        </div>
                    </div>
                    <p>"En tant qu'organisateur, TicketHub m'a permis de simplifier considérablement la gestion de mes événements et d'augmenter mes ventes de 30%."</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/avatar3.jpg" alt="Avatar" class="testimonial-img">
                        <div>
                            <h5 class="mb-0">Sophie EDIMO</h5>
                            <small class="text-muted">Cliente satisfaite</small>
                        </div>
                    </div>
                    <p>"Le processus d'achat est fluide et la réception des billets par email est instantanée. Je recommande vivement cette plateforme !"</p>
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
    