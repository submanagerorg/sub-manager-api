<?php 

return [
    'default' => env('PAY_FOR_SERVICE_DEFAULT_DRIVER', 'vtpass'),

    'drivers' => [
        'vtpass' => [
            'api_key' => env('VTPASS_API_KEY')
        ],
        'flutterwave' => [
            'api_key' => env('FLUTTERWAVE_API_KEY')
        ]
    ],

];