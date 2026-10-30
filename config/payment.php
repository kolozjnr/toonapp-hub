<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    | Supported: "paystack", "flutterwave", "monnify"
    */
    'default' => env('PAYMENT_GATEWAY', 'paystack'),

    /*
    |--------------------------------------------------------------------------
    | Paystack
    |--------------------------------------------------------------------------
    */
    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'callback_url' => env('PAYSTACK_CALLBACK_URL', env('APP_URL').'/payment/paystack/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Flutterwave
    |--------------------------------------------------------------------------
    */
    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
        'webhook_hash' => env('FLUTTERWAVE_WEBHOOK_HASH'),
        'callback_url' => env('FLUTTERWAVE_CALLBACK_URL', env('APP_URL').'/payment/flutterwave/callback'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monnify
    |--------------------------------------------------------------------------
    */
    'monnify' => [
        'api_key' => env('MONNIFY_API_KEY'),
        'secret_key' => env('MONNIFY_SECRET_KEY'),
        'contract_code' => env('MONNIFY_CONTRACT_CODE'),
        'sandbox' => env('MONNIFY_SANDBOX', true),
        'callback_url' => env('MONNIFY_CALLBACK_URL', env('APP_URL').'/payment/monnify/callback'),
    ],

];
