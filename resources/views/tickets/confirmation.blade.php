@extends('layouts.app')

@section('title', 'Confirmation de l\'achat')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    
                    <h1 class="card-title mb-4">Merci pour votre achat !</h1>
                    
                    <p class="lead mb-4"> billet(s) pour <strong>{{ $event->title }}</strong>  réservé(s) avec succès. </p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-1">Un email contenant vos billets a été envoyé à l'adresse: <strong>{{ session('attendee_email') ?? auth()->user()->email }}</strong></p>
                        <p class="mb-0">Numéro de transaction: <strong>{{ session('orderID') }}</strong></p>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('dashboard.tickets') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-ticket-alt me-2"></i>Voir mes billets
                        </a>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-calendar-alt me-2"></i>Voir d'autres événements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


