@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="mb-4">Choisissez votre mode de paiement</h3>

            @php
                $ticket = session('purchase_data');
            @endphp

            @if($ticket)
              

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Résumé de la commande</h5>
                    <p><strong>Événement :</strong> {{ \App\Models\Event::find($ticket['event_id'])->title }}</p>
                    <p><strong>Nom :</strong> {{ $ticket['attendee_name'] }}</p>
                    <p><strong>Email :</strong> {{ $ticket['attendee_email'] }}</p>
                    <p><strong>Nombre de billets :</strong> {{ $ticket['quantity'] }}</p>
                    <p><strong>Prix total :</strong> {{ number_format($ticket['total_price'], 2) }} €</p>
                </div>
            </div>

                <!-- Injecter les données dans JS -->
                <script>
                    const ticketData = @json($ticket);
                </script>


                <!-- Intégration bouton PayPal -->
                <div id="paypal-button-container" class="mb-3"></div>
                

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
            return fetch('{{ route('payment.createOrder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    // Passer les données du ticket à l'API PayPal
                     event_id: ticketData.event_id,
                    quantity: ticketData.quantity,
                    attendee_name: ticketData.attendee_name,
                    attendee_email: ticketData.attendee_email,
                    attendee_phone: ticketData.attendee_phone,
                
                })
            })
             .then(res => res.json())
             .then(data => data.id);
        },
        onApprove: function(data, actions) {
            return fetch('{{ route('payment.captureOrder') }}' , {
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
                
                alert(details.message);
                // Rediriger ou mettre à jour l'interface utilisateur selon les besoins
                window.location.href = '/';
            });
        }
    }).render('#paypal-button-container');
</script>
@endsection
