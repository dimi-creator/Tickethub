<?php

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'currency' => env('PAYPAL_CURRENCY', 'EUR'),
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'currency' => env('PAYPAL_CURRENCY', 'EUR'),
    ],
    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    'notify_url' => env('PAYPAL_NOTIFY_URL'),
    'locale' => env('PAYPAL_LOCALE', 'fr_FR'),
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true),
];
