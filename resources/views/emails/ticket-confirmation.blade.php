@component('mail::message')
# Confirmation de votre achat de billets

Bonjour {{ $tickets->first()->attendee_name }},

Merci pour votre achat ! Voici les détails de vos billets :

@foreach($tickets as $ticket)
@component('mail::panel')
**Événement :** {{ $ticket->event->title }}

**Type de billet :** {{ $ticket->ticketType->name }}

**Numéro de billet :** {{ $ticket->ticket_number }}

**Date :** {{ $ticket->event->start_date->format('d/m/Y H:i') }}

**Lieu :** {{ $ticket->event->venue }}
@endcomponent
@endforeach

@component('mail::button', ['url' => route('dashboard')])
Voir mes billets
@endcomponent

Merci de votre achat,
L'équipe TicketHub
@endcomponent
