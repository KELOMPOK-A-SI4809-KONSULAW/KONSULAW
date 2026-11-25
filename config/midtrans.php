<?php

return [
    'server_key'    => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-demo-konsulaw-key'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-demo-konsulaw-key'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'  => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'        => env('MIDTRANS_IS_3DS', true),
];
