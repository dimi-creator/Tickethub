@extends('layouts.app')

@section('title', 'about')

@section('content')


<div class="container py-5">
    <!-- En-tête avec image de fond -->
    <div class="bg-primary text-white p-5 rounded-3 mb-5 position-relative overflow-hidden">
        <div class="position-relative z-2">
            <h1 class="display-4 fw-bold mb-4">À propos de TicketHub</h1>
            <p class="lead mb-0">Une plateforme pensée pour simplifier l'organisation et la participation aux
                événements.</p>
        </div>
        <div class="position-absolute top-0 end-0 opacity-25 z-1">
            <i class="fas fa-ticket-alt fa-10x text-white"></i>
        </div>
    </div>

    <!-- Notre mission -->
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="{{ asset('storage/images/notre-mission.jpg') }}" alt="Notre mission" class="img-fluid rounded-3 shadow">
        </div>
        <div class="col-lg-6">
            <span class="badge bg-primary mb-2">Notre mission</span>
            <h2 class="mb-4">Connecter les personnes à travers des expériences mémorables</h2>
            <p class="lead text-muted">Chez TicketHub, nous croyons au pouvoir rassembleur des événements.</p>
            <p>Notre plateforme est née d'une conviction simple : l'organisation et l'accès aux événements devraient
                être aussi simples que possible, pour que chacun puisse se concentrer sur l'essentiel – vivre des
                moments inoubliables.</p>
            <p>Que vous soyez un organisateur cherchant à partager votre passion ou un participant à la recherche de
                nouvelles expériences, TicketHub vous offre un espace où l'aventure commence en quelques clics.</p>
        </div>
    </div>

    <!-- Nos valeurs -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-primary mb-2">Nos valeurs</span>
            <h2 class="mb-4">Ce qui nous guide au quotidien</h2>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                        <i class="fas fa-handshake text-primary fa-2x"></i>
                    </div>
                    <h3 class="h4 card-title">Simplicité</h3>
                    <p class="card-text">Nous concevons chaque fonctionnalité avec l'objectif de rendre votre expérience
                        fluide et intuitive, pour que la technologie soit un facilitateur et jamais un obstacle.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                        <i class="fas fa-shield-alt text-primary fa-2x"></i>
                    </div>
                    <h3 class="h4 card-title">Sécurité</h3>
                    <p class="card-text">La confiance est au cœur de notre service. Nous utilisons les technologies de
                        paiement les plus sécurisées et protégeons vos données avec les standards les plus élevés de
                        l'industrie.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                        <i class="fas fa-users text-primary fa-2x"></i>
                    </div>
                    <h3 class="h4 card-title">Communauté</h3>
                    <p class="card-text">Nous croyons à la richesse des connexions humaines. Notre plateforme n'est pas
                        qu'un outil, mais un espace où se construit une communauté d'organisateurs et de participants
                        passionnés.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment ça marche -->
    <div class="bg-light p-5 rounded-3 mb-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <span class="badge bg-primary mb-2">Comment ça marche</span>
                <h2 class="mb-4">Une expérience pensée pour tous</h2>
                <p class="lead text-muted mb-5">Grâce à notre plateforme intuitive, organiser ou participer à un
                    événement n'a jamais été aussi simple.</p>
            </div>

            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="h4 mb-3">Pour les organisateurs</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">1</span>
                        <span>Inscrivez-vous gratuitement et créez votre profil d'organisateur</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">2</span>
                        <span>Publiez vos événements en quelques minutes avec toutes les informations nécessaires</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">3</span>
                        <span>Gérez vos ventes et suivez vos statistiques en temps réel</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">4</span>
                        <span>Recevez vos paiements de manière sécurisée et automatisée</span>
                    </li>
                </ul>
            </div>

            <div class="col-md-6">
                <h3 class="h4 mb-3">Pour les participants</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">1</span>
                        <span>Parcourez facilement les événements disponibles</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">2</span>
                        <span>Achetez vos billets en toute sécurité via notre plateforme</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">3</span>
                        <span>Recevez vos billets directement par email</span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex align-items-center px-0">
                        <span class="badge bg-primary rounded-circle me-3">4</span>
                        <span>Accédez à vos billets à tout moment depuis votre espace personnel</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Engagement sécurité -->
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
            <img src="{{ asset('storage/images/securité.jpg') }}" alt="Sécurité et confiance"
                class="img-fluid rounded-3 shadow">
        </div>
        <div class="col-lg-6 order-lg-1">
            <span class="badge bg-primary mb-2">Sécurité et confiance</span>
            <h2 class="mb-4">Votre tranquillité d'esprit est notre priorité</h2>
            <p>Nous comprenons l'importance de la confiance lorsqu'il s'agit de transactions en ligne. C'est pourquoi
                nous avons mis en place les mesures de sécurité les plus robustes :</p>
            <ul class="list-unstyled">
                <li class="mb-2 d-flex align-items-center">
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <span>Paiements 100% sécurisés via des partenaires de confiance comme PayPal</span>
                </li>
                <li class="mb-2 d-flex align-items-center">
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <span>Protection des données personnelles conforme au RGPD</span>
                </li>
                <li class="mb-2 d-flex align-items-center">
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <span>Billets nominatifs avec code unique pour éviter les fraudes</span>
                </li>
                <li class="mb-2 d-flex align-items-center">
                    <i class="fas fa-check-circle text-primary me-2"></i>
                    <span>Système de vérification des organisateurs pour garantir la légitimité des événements</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- L'équipe (optionnel) -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-primary mb-2">Notre équipe</span>
            <h2 class="mb-4">Des passionnés à votre service</h2>
            <p class="lead text-muted mb-5">Derrière TicketHub, une équipe dévouée qui partage la même passion pour les
                événements et la technologie.</p>
        </div>

        <!-- Exemple de membre d'équipe - à répliquer ou remplacer selon vos besoins -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle overflow-hidden mb-3 mx-auto" style="width: 120px; height: 120px;">
                        <img src="{{ asset('images/team-1.jpg') }}" alt="Membre de l'équipe" class="img-fluid">
                    </div>
                    <h3 class="h5 card-title">Raoul NGUIMBOUS</h3>
                    <p class="card-subtitle text-muted mb-3">Fondateur & CEO</p>
                    <p class="card-text">Passionné d'événements culturels, Raoul a créé TicketHub pour rendre
                        l'organisation d'événements accessible à tous.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle overflow-hidden mb-3 mx-auto" style="width: 120px; height: 120px;">
                        <img src="{{ asset('images/team-2.jpg') }}" alt="Membre de l'équipe" class="img-fluid">
                    </div>
                    <h3 class="h5 card-title">Dora TOUKOT</h3>
                    <p class="card-subtitle text-muted mb-3">Directrice Technique</p>
                    <p class="card-text">Experte en développement web, Dora veille à ce que la plateforme soit
                        toujours performante, sécurisée et innovante.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle overflow-hidden mb-3 mx-auto" style="width: 120px; height: 120px;">
                        <img src="{{ asset('images/team-3.jpg') }}" alt="Membre de l'équipe" class="img-fluid">
                    </div>
                    <h3 class="h5 card-title">Hermann ESSAKO</h3>
                    <p class="card-subtitle text-muted mb-3">Responsable Expérience Client</p>
                    <p class="card-text">Fort de son expérience dans l'événementiel, Hermann s'assure que chaque
                        utilisateur vive une expérience exceptionnelle sur TicketHub.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Appel à l'action -->
    <div class="text-center bg-primary text-white p-5 rounded-3">
        <h2 class="mb-3">Prêt à vivre l'expérience TicketHub ?</h2>
        <p class="lead mb-4">Rejoignez notre communauté d'organisateurs et de participants passionnés dès aujourd'hui.
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <a href="{{ route('events.index') }}" class="btn btn-light btn-lg">Découvrir les événements</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Créer un compte</a>
        </div>
    </div>
</div>
           
@endsection
