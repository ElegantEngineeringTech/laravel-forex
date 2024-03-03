<?php

// config for Finller/Forex

use Finller\Forex\DefaultForexRequest;

return [

    'cache' => [
        'driver' => env('FOREX_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),
        'expiry_seconds' => 86_400,
    ],

    'rate_limit' => [
        'driver' => env('FOREX_RATE_LIMIT_DRIVER', env('CACHE_DRIVER', 'file')),
        'every_seconds' => 3_600,
    ],

    'request' => DefaultForexRequest::class,

];
