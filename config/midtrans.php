<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Midtrans payment gateway settings. By default,
    | public Sandbox credentials are provided so it works out-of-the-box.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-z8n3jCj4gU2P8n_d6wK3S_Qp'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-n7uM8dUpzV_D3G7x'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
