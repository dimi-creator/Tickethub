<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billets pour {{ $tickets->first()->event->title }}</title>
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
            margin-bottom: 30px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            page-break-inside: avoid;
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
            padding: 15px;
            margin: -30px -30px 30px -30px;
            border-radius: 10px 10px 0 0;
        }
        .ticket-content {
            margin-bottom: 30px;
        }
        .ticket-content p {
            margin: 10px 0;
            font-size: 14px;
        }
        .event-details {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .event-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .event-details table td {
            padding: 10px;
            vertical-align: top;
        }
        .event-details table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        .transaction-info {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
            padding-top: 30px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Billets pour {{ $tickets->first()->event->title }}</h1>
        <p>{{ $tickets->first()->event->start_date->format('d/m/Y H:i') }} - {{ $tickets->first()->event->venue }}</p>
    </div>

    @foreach($tickets as $ticket)
        <div class="ticket">
            <div class="ticket-header">
                <h2>{{ $ticket->event->title }}</h2>
                <p>{{ $ticket->event->venue }}</p>
                <p>{{ $ticket->event->start_date->format('d/m/Y H:i') }}</p>
            </div>
            <div class="ticket-content">
                <p>Numéro de billet: {{ $ticket->ticket_number }}</p>
                <p>Nom: {{ $ticket->attendee_name }}</p>
                <p>Email: {{ $ticket->attendee_email }}</p>
                <p>Téléphone: {{ $ticket->attendee_phone }}</p>
                <!-- QR Code -->
                <div class="qr-code">
                    <img src="{{ $ticket->qr_code }}" 
                        alt="QR Code">
                </div>
            </div>
            
            <div class="event-details">
                <h3>Détails de l'événement</h3>
                <table>
                    <tr>
                        <td>Date:</td>
                        <td>{{ $ticket->event->start_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Heure:</td>
                        <td>{{ $ticket->event->start_date->format('H:i') }} - {{ $ticket->event->end_date->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Lieu:</td>
                        <td>{{ $ticket->event->venue }}</td>
                    </tr>
                    <tr>
                        <td>Organisateur:</td>
                        <td>{{ $ticket->event->organizer->company_name }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="transaction-info">
                <p>Transaction: {{ $ticket->transaction_id }} | Date d'achat: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="page-break"></div>
    @endforeach

    <div class="footer">
        <p>Ce billet est personnel et ne peut être revendu. Un justificatif d'identité pourra être demandé à l'entrée.</p>
        <p> {{ date('Y') }} TicketHub. Tous droits réservés.</p>
    </div>
</body>
</html>