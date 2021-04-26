<?php

return [

    'client_id' => env('PAYPAL_CLIENT_ID_LOCAL'),
    'secret' => env('PAYPAL_SECRET_LOCAL'),

    'url' => array(
        'redirect' => env('APP_URL') . '/paypal/execute-payment',
        'cancel' => env('APP_URL') . '/paypal/cancel'
    ),

    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),

];
