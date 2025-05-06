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
            return fetch("{{ route('paypal.create-order') }}", {
                method: 'POST',
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => res.json())
              .then(data => {
                  if (!data.id) throw new Error("Order ID manquant");
                  return data.id;
              });
        },
        onApprove: function(data, actions) {
            return fetch("{{ route('paypal.capture-order') }}", {
                method: 'POST',
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            }).then(res => res.json())
              .then(details => {
                  alert("Paiement effectué avec succès !");
                  window.location.href = "{{ route('tickets.confirmation') }}";
              });
        },
        
        onCancel: function (data) {
            alert('Paiement annulé.');
        },
        onError: function(err) {
            console.error(err);
            alert('Erreur lors du traitement du paiement: ' + err);
        }
    }).render('#paypal-button-container');
</script>
@endsection
