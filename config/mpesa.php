<?php
// config/mpesa.php

return [
    'consumer_key'    => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'shortcode'       => env('MPESA_SHORTCODE'),
    'passkey'         => env('MPESA_PASSKEY'),
    'callback_url'    => env('MPESA_CALLBACK_URL'),    // e.g. https://yourdomain.com/mpesa/callback
    'sandbox'         => env('MPESA_SANDBOX', true),   // set false in production
];
