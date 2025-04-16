<?php

declare(strict_types=1);

// config for Finller/Forex

use Elegantly\Forex\Integrations\ExchangeRateApi\ExchangeRateApiConnector;

return [

    'cache' => [
        'enabled' => true,
        'driver' => env('FOREX_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),
        'expiry_seconds' => 86_400,
    ],

    'rate_limit' => [
        'enabled' => false,
        'driver' => env('FOREX_RATE_LIMIT_DRIVER', env('CACHE_DRIVER', 'file')),
        'every_seconds' => 3_600,
    ],

    'client' => ExchangeRateApiConnector::class,

];
