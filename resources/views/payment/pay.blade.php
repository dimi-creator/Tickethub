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
                <div id="paypal-button-container"></div>
            @else
                <div class="alert alert-warning">
                    Aucune donnée de commande trouvée.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Script SDK PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}&currency=EUR"></script>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return fetch("{{ route('payment.process') }}", {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                event_id: "{{ $ticket['event_id'] }}",
                quantity: "{{ $ticket['quantity'] }}",
                attendee_name: "{{ $ticket['attendee_name'] }}",
                attendee_email: "{{ $ticket['attendee_email'] }}",
                attendee_phone: "{{ $ticket['attendee_phone'] }}"
            })
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            return orderData.id; // ID de l'ordre PayPal
        });
    },
    onApprove: function(data, actions) {
        return fetch("{{ route('payment.success') }}?token=" + data.orderID)
            .then(function(res) {
                return res.json();
            })
            .then(function(details) {
                window.location.href = "{{ route('tickets.confirmation') }}";
            });
    },
    onCancel: function (data) {
        window.location.href = "{{ route('payment.cancel') }}";
    }
}).render('#paypal-button-container');
</script>
@endsection
