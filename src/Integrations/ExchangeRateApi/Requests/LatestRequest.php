<?php

namespace Finller\Forex\Integrations\ExchangeRateApi\Request;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class LatestRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected readonly string $currency)
    {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/latest/{$this->currency}";
    }
}
