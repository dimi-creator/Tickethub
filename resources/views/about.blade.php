@extends('layouts.app')

@section('title', 'about')

@section('content')


           <div id="about" class="page">
                <div class="container py-5">
                    <h1 class="mb-4">À propos de TicketHub</h1>
                    
                    <!-- Mission Statement -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h3 class="section-title">Notre Mission</h3>
                            <p>TicketHub a été créé avec une vision claire : simplifier l'accès aux événements tout en offrant aux organisateurs les outils pour réussir. Notre plateforme connecte les passionnés d'événements avec les créateurs de contenu culturel, sportif et professionnel.</p>
                            <p>Notre objectif est de devenir la référence en matière de billetterie en ligne, en proposant une expérience fluide et sécurisée à tous nos utilisateurs, qu'ils soient participants ou organisateurs.</p>
                        </div>
                        <div class="col-md-6">
                            <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/mission-image.jpg" alt="Notre mission" class="img-fluid rounded shadow">
                        </div>
                    </div>
                    
                    <!-- Values -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h3 class="section-title">Nos Valeurs</h3>
                            <div class="row mt-4">
                                <div class="col-md-4 mb-4">
                                    <div class="text-center">
                                        <div class="feature-icon">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <h4>Sécurité</h4>
                                        <p>Nous utilisons les dernières technologies de sécurité pour protéger vos données et transactions.</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center">
                                        <div class="feature-icon">
                                            <i class="fas fa-hand-holding-heart"></i>
                                        </div>
                                        <h4>Accessibilité</h4>
                                        <p>Nous croyons que la culture et les événements doivent être accessibles à tous.</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="text-center">
                                        <div class="feature-icon">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <h4>Durabilité</h4>
                                        <p>Nous promouvons les billets électroniques pour réduire notre impact environnemental.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Our Team -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h3 class="section-title">Notre Équipe</h3>
                            <div class="row mt-4">
                                <div class="col-md-3 mb-4">
                                    <div class="card text-center">
                                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/team1.jpg" class="card-img-top" alt="CEO">
                                        <div class="card-body">
                                            <h5 class="card-title">Alexandre Dubois</h5>
                                            <p class="card-text text-muted">Fondateur & CEO</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="card text-center">
                                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/team2.jpg" class="card-img-top" alt="CTO">
                                        <div class="card-body">
                                            <h5 class="card-title">Marie Leroy</h5>
                                            <p class="card-text text-muted">CTO</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="card text-center">
                                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/team3.jpg" class="card-img-top" alt="Marketing">
                                        <div class="card-body">
                                            <h5 class="card-title">Thomas Bernard</h5>
                                            <p class="card-text text-muted">Directeur Marketing</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="card text-center">
                                        <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/team4.jpg" class="card-img-top" alt="Support">
                                        <div class="card-body">
                                            <h5 class="card-title">Sophie Martin</h5>
                                            <p class="card-text text-muted">Responsable Support</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technologies -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <img src="https://cdn.jsdelivr.net/gh/perseusprince070/images_repo/tech-image.jpg" alt="Technologies" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-6">
                            <h3 class="section-title">Nos Technologies</h3>
                            <p>TicketHub est développé avec des technologies modernes et robustes pour vous offrir une expérience optimale :</p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="fab fa-laravel text-danger me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>Laravel 12</strong>
                                        <p class="mb-0">Framework PHP moderne et sécurisé pour notre backend</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="fab fa-bootstrap text-primary me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>Bootstrap</strong>
                                        <p class="mb-0">Interface responsive et adaptée à tous les appareils</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="fab fa-php text-info me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>PHP 8.3</strong>
                                        <p class="mb-0">Version la plus récente de PHP pour des performances optimales</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center">
                                    <i class="fab fa-aws text-warning me-3" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <strong>AWS</strong>
                                        <p class="mb-0">Infrastructure cloud sécurisée et évolutive</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
           </div>

@endsection
