<?php

declare(strict_types=1);

namespace Elegantly\Forex;

use Brick\Math\RoundingMode;
use Brick\Money\Currency;
use Brick\Money\CurrencyConverter;
use Brick\Money\ExchangeRateProvider\BaseCurrencyProvider;
use Brick\Money\ExchangeRateProvider\ConfigurableProvider;
use Brick\Money\Money;
use Carbon\CarbonInterface;

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
    public function rates(CarbonInterface $date, string $currency): array
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
    public function refreshRates(CarbonInterface $date, string $currency): array
    {
        $datetime = $date->format('Y-m-d');

        return $this->rates[$datetime][$currency] = $this->queryRates($date, $currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function queryRates(CarbonInterface $date, string $currency): array
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

    public function convert(
        Money $money,
        string|Currency $currency,
        RoundingMode $roundingMode = RoundingMode::HALF_UP,
        ?CarbonInterface $date = null,
    ): Money {

        $currency = is_string($currency) ? $currency : $currency->getCurrencyCode();

        $sourceCurrency = $money->getCurrency()->getCurrencyCode();

        if ($sourceCurrency === $currency) {
            return $money;
        }

        $rates = $date ? $this->rates($date, $sourceCurrency) : $this->latest($sourceCurrency);

        $provider = new BaseCurrencyProvider(
            provider: (new ConfigurableProvider)->setExchangeRate(
                $money->getCurrency()->getCurrencyCode(),
                $currency,
                $rates[$currency]
            ),
            baseCurrencyCode: $money->getCurrency()->getCurrencyCode()
        );

        $converter = new CurrencyConverter($provider);

        return $converter->convert(
            $money,
            $currency,
            roundingMode: $roundingMode
        );

    }
}
