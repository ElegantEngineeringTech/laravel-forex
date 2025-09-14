<?php

declare(strict_types=1);

namespace Elegantly\Forex\Integrations\ExchangeRateApi\Requests;

use Carbon\CarbonInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class HistoryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly CarbonInterface $date,
        protected readonly string $currency,
    ) {
        //
    }

    public function resolveEndpoint(): string
    {
        return "/history/{$this->currency}/{$this->date->year}/{$this->date->month}/{$this->date->day}";
    }
}
