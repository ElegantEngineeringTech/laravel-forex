<?php

declare(strict_types=1);

namespace Elegantly\Forex;

use Carbon\Carbon;

class Forex
{
    /**
     * @var array<string, array<string, int|float>>
     */
    protected array $latest = [];

    /**
     * @var array<string, array<string, array<string, int|float>>>
     */
    protected array $rates = [];

    public function __construct(
        public ForexClient $client
    ) {
        //
    }

    /**
     * @return array<string, int|float>
     */
    public function latest(string $currency): array
    {
        return $this->latest[$currency] ?? $this->refreshLatest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function rates(Carbon $date, string $currency): array
    {
        $datetime = $date->format('Y-m-d');

        return $this->rates[$datetime][$currency] ?? $this->refreshRates($date, $currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function refreshLatest(string $currency): array
    {
        return $this->latest[$currency] = $this->queryLatest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function queryLatest(string $currency): array
    {
        return $this->client->latest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function refreshRates(Carbon $date, string $currency): array
    {
        $datetime = $date->format('Y-m-d');

        return $this->rates[$datetime][$currency] = $this->queryRates($date, $currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function queryRates(Carbon $date, string $currency): array
    {
        return $this->client->rates($date, $currency);
    }

    /**
     * @return array<string, array<string, int|float>>
     */
    public function getLatest(): array
    {
        return $this->latest;
    }

    /**
     * @return array<string, array<string, array<string, int|float>>>
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    public function getClient(): ForexClient
    {
        return $this->client;
    }
}
