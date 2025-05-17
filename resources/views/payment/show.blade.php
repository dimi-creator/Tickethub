<!-- @extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Sélectionner un mode de paiement</h2>
                    
                    <div class="alert alert-info mb-4">
                        <h5>Récapitulatif de la commande</h5>
                        <p class="mb-1"><strong>Événement:</strong> {{ $event->title }}</p>
                        <p class="mb-1"><strong>Nombre de billets:</strong> {{ $quantity }}</p>
                        <p class="mb-0"><strong>Montant total:</strong> {{ number_format($amount, 2, ',', ' ') }} fcfa</p>
                    </div>
                    
                    <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="card-tab" data-bs-toggle="tab" data-bs-target="#card-content" type="button" role="tab" aria-controls="card-content" aria-selected="true">
                                <i class="fas fa-credit-card me-2"></i>Carte de crédit
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="paypal-tab" data-bs-toggle="tab" data-bs-target="#paypal-content" type="button" role="tab" aria-controls="paypal-content" aria-selected="false">
                                <i class="fab fa-paypal me-2"></i>PayPal
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content p-4 border border-top-0 rounded-bottom mb-4" id="paymentTabsContent">
                        Onglet Carte de crédit
                        <div class="tab-pane fade show active" id="card-content" role="tabpanel" aria-labelledby="card-tab">
                            <form action="{{ route('payment.process.credit-card') }}" method="POST" id="credit-card-form">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="card_holder_name" class="form-label">Nom du titulaire</label>
                                    <input type="text" class="form-control @error('card_holder_name') is-invalid @enderror" id="card_holder_name" name="card_holder_name" required>
                                    @error('card_holder_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Numéro de carte</label>
                                    <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Date d'expiration</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('card_expiry_month') is-invalid @enderror" name="card_expiry_month" placeholder="MM" maxlength="2" required>
                                            <span class="input-group-text">/</span>
                                            <input type="text" class="form-control @error('card_expiry_year') is-invalid @enderror" name="card_expiry_year" placeholder="AA" maxlength="2" required>
                                        </div>
                                        @error('card_expiry_month')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('card_expiry_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control @error('card_cvv') is-invalid @enderror" id="card_cvv" name="card_cvv" placeholder="123" maxlength="3" required>
                                        @error('card_cvv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Payer {{ number_format($amount, 2, ',', ' ') }} fcfa</button>
                                </div>
                            </form>
                        </div>
                        
                        Onglet PayPal
                        <div class="tab-pane fade" id="paypal-content" role="tabpanel" aria-labelledby="paypal-tab">
                            <div class="text-center py-4">
                                <p>Vous allez être redirigé vers PayPal pour finaliser votre paiement en toute sécurité.</p>
                                <form action="{{ route('payment.process.paypal') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fab fa-paypal me-2"></i>Payer avec PayPal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <p class="mb-0"><small class="text-muted">Paiement sécurisé. Vos données bancaires ne sont pas stockées.</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection -->