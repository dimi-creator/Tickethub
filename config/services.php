<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mode'    => env('PAYPAL_MODE', 'sandbox'),

    'sandbox' => [
        'client_id' => env('AdPdpfFkEh6GE6I2Xo8B-0dc1kGVPfScSQa24cdOD1GxpAXqcgTTK51WO5mJeCa-tvab0a5eiEWOagL7'),
        'client_secret' => env('EK67tcsNQ9dDTteqYpyQlEy5Ni0ocddCwJPVSyykPabDL0kOq9357fp_Olatpo1_HOmBlxB8EEnbzqc9'),

    ],

    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
    ],

    'payment_action' => 'Sale',
    'currency'       => 'EUR',
    'notify_url'     => '',
    'locale'         => 'fr_FR',

    'paypal' => [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_CLIENT_SECRET'),
],
    

];