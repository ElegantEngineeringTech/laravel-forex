<?php

namespace Finller\Forex;

use Finller\Forex\Integrations\ExchangeRateApi\ExchangeRateApiConnector;

class DefaultForexRequest implements ForexRequest
{
    public static function get(string $currency): array
    {
        $connector = new ExchangeRateApiConnector();

        return $connector->latest($currency)->json('rates', []);
    }
}
