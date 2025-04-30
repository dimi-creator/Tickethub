<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .ticket {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .ticket-header {
            background-color: #4a6cf7;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .ticket-info {
            margin-bottom: 10px;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <h1>Vos billets pour {{ $event->title }}</h1>
    
    <p>Bonjour {{ $tickets[0]->attendee_name }},</p>
    
    <p>Merci pour votre achat. Veuillez trouver ci-joint vos billets pour l'événement {{ $event->title }}.</p>
    
    @foreach($tickets as $ticket)
        <div class="ticket">
            <div class="ticket-header">
                <h2>{{ $event->title }}</h2>
            </div>
            
            <div class="ticket-info">
                <p><strong>Numéro de billet:</strong> {{ $ticket->ticket_number }}</p>
                <p><strong>Participant:</strong> {{ $ticket->attendee_name }}</p>
                <p><strong>Date:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                <p><strong>Lieu:</strong> {{ $event->venue }}</p>
            </div>
            
            <div class="barcode">
                *{{ $ticket->ticket_number }}*
            </div>
        </div>
    @endforeach
    
    <p>Présentez ce billet (imprimé ou sur votre appareil mobile) à l'entrée de l'événement.</p>
    
    <p>Nous vous souhaitons un excellent événement!</p>
    
    <p>L'équipe TicketHub</p>
</body>
</html>