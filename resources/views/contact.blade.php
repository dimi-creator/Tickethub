@extends('layouts.app')

@section('title', 'contact')

@section('content')

<div id="contact" class="page">
    <div class="container py-5">
        <h1 class="mb-4">Contactez-nous</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Formulaire de contact</h4>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" placeholder="Votre nom">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="votre@email.com">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Sujet</label>
                                <select class="form-select" id="subject">
                                    <option selected>Choisir un sujet</option>
                                    <option>Support technique</option>
                                    <option>Remboursement</option>
                                    <option>Partenariat</option>
                                    <option>Autre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Votre message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Informations de contact</h4>
                        <div class="d-flex mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-map-marker-alt fa-2x"></i>
                            </div>
                            <div>
                                <h5>Adresse</h5>
                                <p>123 Boulevard de la liberté<br>Douala , Akwa</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-phone fa-2x"></i>
                            </div>
                            <div>
                                <h5>Téléphone</h5>
                                <p>+237 680 34 59 55</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3 text-primary">
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                            <div>
                                <h5>Email</h5>
                                <p>contact@tickethub.fr</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div>
                                <h5>Heures d'ouverture</h5>
                                <p>Lun - Ven: 9h - 18h<br>Sam: 10h - 15h</p>
                            </div>
                        </div>
                        
                        <h5 class="mt-4 mb-3">Suivez-nous</h5>
                        <div class="d-flex">
                            <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-danger me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">FAQ</h4>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Comment obtenir un remboursement ?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Pour obtenir un remboursement, veuillez contacter l'organisateur de l'événement directement via notre plateforme. Si l'événement est annulé, les remboursements sont automatiques.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Comment devenir organisateur ?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Pour devenir organisateur, il vous suffit de créer un compte et de sélectionner l'option "Je suis un organisateur" lors de l'inscription.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Les billets sont-ils transférables ?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Cela dépend de la politique de l'organisateur. Certains événements permettent le transfert de billets, d'autres non. Cette information est indiquée sur la page de l'événement.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection