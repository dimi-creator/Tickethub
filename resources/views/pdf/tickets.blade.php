<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billets pour {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .ticket {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .ticket:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            border-width: 0 50px 50px 0;
            border-style: solid;
            border-color: #4a6cf7 #fff;
        }
        .ticket-header {
            background-color: #4a6cf7;
            color: white;
            padding: 10px;
            margin: -20px -20px 20px -20px;
            border-radius: 10px 10px 0 0;
        }
        .ticket-info {
            margin-bottom: 15px;
        }
        .ticket-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .ticket-info table td {
            padding: 5px;
            vertical-align: top;
        }
        .ticket-info table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .event-details {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .transaction-info {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Billets pour {{ $event->title }}</h1>
        <p>{{ $event->start_date->format('d/m/Y H:i') }} - {{ $event->venue }}</p>
    </div>

    @foreach($tickets as $ticket)
        <div class="ticket">
            <div class="ticket-header">
                <h2>{{ $event->title }}</h2>
            </div>
            
            <div class="ticket-info">
                <table>
                    <tr>
                        <td>Numéro de billet:</td>
                        <td><strong>{{ $ticket->ticket_number }}</strong></td>
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
            
            <div class="event-details">
                <h3>Détails de l'événement</h3>
                <table>
                    <tr>
                        <td>Date:</td>
                        <td>{{ $event->start_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Heure:</td>
                        <td>{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Lieu:</td>
                        <td>{{ $event->venue }}</td>
                    </tr>
                    <tr>
                        <td>Organisateur:</td>
                        <td>{{ $event->organizer->company_name }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                <p>Présentez ce code QR à l'entrée de l'événement</p>
            </div>
            
            <div class="transaction-info">
                <p>Transaction: {{ $transactionId }} | Date d'achat: {{ $purchaseDate }}</p>
            </div>
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <div class="footer">
        <p>Ce billet est personnel et ne peut être revendu. Un justificatif d'identité pourra être demandé à l'entrée.</p>
        <p>© {{ date('Y') }} TicketHub. Tous droits réservés.</p>
    </div>
</body>
</html>