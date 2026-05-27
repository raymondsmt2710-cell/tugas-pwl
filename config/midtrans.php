<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans API Credentials
    |--------------------------------------------------------------------------
    |
    | Server Key: Used for backend API calls (never expose to frontend)
    | Client Key: Used for Snap.js in frontend (safe to expose)
    |
    | Sandbox keys start with "SB-Mid-server-" and "SB-Mid-client-"
    | Production keys start with "Mid-server-" and "Mid-client-"
    |
    */
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Set to false for Sandbox, true for Production.
    | Sandbox Dashboard: https://dashboard.sandbox.midtrans.com
    | Production Dashboard: https://dashboard.midtrans.com
    |
    */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    /*
    |--------------------------------------------------------------------------
    | Webhook / Notification URL
    |--------------------------------------------------------------------------
    |
    | Configure this URL in Midtrans Dashboard → Settings → Payment Notification URL
    | Sandbox: https://dashboard.sandbox.midtrans.com/settings/vtweb_configuration
    |
    | For local development, use ngrok or similar tunneling service:
    | ngrok http 8000 → https://xxxx.ngrok.io/midtrans/webhook
    |
    */
    'webhook_url' => env('MIDTRANS_WEBHOOK_URL', '/midtrans/webhook'),

    /*
    |--------------------------------------------------------------------------
    | Transaction Expiry
    |--------------------------------------------------------------------------
    */
    'expiry_duration' => env('MIDTRANS_EXPIRY_HOURS', 24),
];
