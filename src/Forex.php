<?php

declare(strict_types=1);

namespace Elegantly\Forex;

class Forex
{
    /**
     * @var array<string, array<string, int|float>>
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
    public function get(string $currency): array
    {
        if (array_key_exists($currency, $this->rates)) {
            return $this->rates[$currency];
        }

        return $this->refresh($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function query(string $currency): array
    {
        return $this->client->rates($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function refresh(string $currency): array
    {
        $this->rates[$currency] = $this->query($currency);

        return $this->rates[$currency];
    }

    /**
     * @return array<string, array<string, int|float>>
     */
    public function getRates(): array
    {
        return $this->rates;
    }
}
