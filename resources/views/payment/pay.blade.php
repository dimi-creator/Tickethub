@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4">Choisissez votre mode de paiement</h3>

            @php
                $ticket = session('ticket_data');
            @endphp

            @if($ticket)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Résumé de la commande</h5>
                        <p><strong>Événement :</strong> {{ \App\Models\Event::find($ticket['event_id'])->title }}</p>
                        <p><strong>Nom :</strong> {{ $ticket['attendee_name'] }}</p>
                        <p><strong>Email :</strong> {{ $ticket['attendee_email'] }}</p>
                        <p><strong>Nombre de billets :</strong> {{ $ticket['quantity'] }}</p>
                        <p><strong>Total :</strong> {{ number_format($ticket['amount'], 2, ',', ' ') }} fcfa</p>
                    </div>
                </div>

                <!-- Intégration bouton PayPal -->
                <div id="paypal-button-container" class="mb-3"></div>
                <!-- <div id="card-button-container" class="mb-3"></div> -->

            @else
                <div class="alert alert-warning">
                    Aucune donnée de commande trouvée.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Script SDK PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=AdPdpfFkEh6GE6I2Xo8B-0dc1kGVPfScSQa24cdOD1GxpAXqcgTTK51WO5mJeCa-tvab0a5eiEWOagL7&currency=EUR"></script>


 

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('{{ route('paypal.create-order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    event_id: '{{ $ticket['event_id'] }}',
                    quantity: '{{ $ticket['quantity'] }}',
                    attendee_name: '{{ $ticket['attendee_name'] }}',
                    attendee_email: '{{ $ticket['attendee_email'] }}',
                    attendee_phone: '{{ $ticket['attendee_phone'] }}',
                    amount: '{{ $ticket['amount'] }}',
                })
            })
             .then(res => res.json())
             .then(data => data.id);
        },
        onApprove: function(data, actions) {
            return fetch('{{ route('paypal.capture-order') }}' , {
               method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
             },
             body: JSON.stringify({
            orderID: data.orderID
              })
        
            })
            .then(res => res.json())
             .then(details => {
                alert('Transaction complétée avec succès');
                // Rediriger ou mettre à jour l'interface utilisateur selon les besoins
                window.location.href = '{{ route('ticket.confirmation') }}';
            });
        }
    }).render('#paypal-button-container');
</script>
@endsection
