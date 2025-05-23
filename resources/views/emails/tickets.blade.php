<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6cf7;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .ticket {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        .ticket-header {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .ticket-details {
            margin-bottom: 15px;
        }
        .ticket-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .ticket-details table td {
            padding: 5px;
        }
        .ticket-details table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a6cf7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Vos billets pour {{ $event->title }}</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $attendeeName }},</p>
            
            <p>Merci pour votre achat ! Veuillez trouver ci-dessous vos billets pour l'événement <strong>{{ $event->title }}</strong>.</p>
            
            <div class="transaction-info">
                <p><strong>Numéro de transaction:</strong> {{ $transactionId }}</p>
                <p><strong>Date d'achat:</strong> {{ $purchaseDate }}</p>
                <p><strong>Montant total:</strong> {{ $amount }} €</p>
            </div>
            
            <h2>Détails de l'événement</h2>
            <div class="event-details">
                <p><strong>Date:</strong> {{ $event->start_date->format('d/m/Y') }}</p>
                <p><strong>Heure:</strong> {{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</p>
                <p><strong>Lieu:</strong> {{ $event->venue }}</p>
                <p><strong>Organisateur:</strong> {{ $event->organizer->company_name }}</p>
            </div>
            
            <h2>Vos billets</h2>
            
            @foreach($tickets as $ticket)
                <div class="ticket">
                    <div class="ticket-header">
                        <h3>Billet #{{ $loop->iteration }}</h3>
                    </div>
                    
                    <div class="ticket-details">
                        <table>
                            <tr>
                                <td>Numéro de billet:</td>
                                <td>{{ $ticket->ticket_number }}</td>
                            </tr>
                            <tr>
                                <td>Participant:</td>
                                <td>{{ $ticket->attendee_name }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{ $ticket->attendee_email }}</td>
                            </tr>
                            <tr>
                                <td>Téléphone:</td>
                                <td>{{ $ticket->attendee_phone }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="qr-code">
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                        <p>Présentez ce code QR à l'entrée de l'événement</p>
                    </div>
                </div>
            @endforeach
            
            <p>Vous pouvez également consulter vos billets à tout moment dans votre espace personnel sur notre site.</p>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('dashboard.tickets') }}" class="button">Accéder à mes billets</a>
            </div>
            
            <p>Nous vous souhaitons un excellent événement !</p>
            
            <p>Cordialement,<br>
            L'équipe TicketHub</p>
        </div>
        
        <div class="footer">
            <p>Cet email contient des informations importantes concernant vos billets. Veuillez le conserver.</p>
            <p>© {{ date('Y') }} TicketHub. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>