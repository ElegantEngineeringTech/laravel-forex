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
    public function latest(string|Currency $currency): array
    {
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        return $this->latest[$currency] ?? $this->refreshLatest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function rates(CarbonInterface $date, string|Currency $currency): array
    {
        $datetime = $date->format('Y-m-d');
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        return $this->rates[$datetime][$currency] ?? $this->refreshRates($date, $currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function refreshLatest(string|Currency $currency): array
    {
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        return $this->latest[$currency] = $this->queryLatest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function queryLatest(string|Currency $currency): array
    {
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        return $this->client->latest($currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function refreshRates(CarbonInterface $date, string|Currency $currency): array
    {
        $datetime = $date->format('Y-m-d');
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

        return $this->rates[$datetime][$currency] = $this->queryRates($date, $currency);
    }

    /**
     * @return array<string, int|float>
     */
    public function queryRates(CarbonInterface $date, string|Currency $currency): array
    {
        $currency = $currency instanceof Currency ? $currency->getCurrencyCode() : $currency;

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

    public function getCurrencyConverter(
        string|Currency $sourceCurrency,
        ?CarbonInterface $date = null,
    ): CurrencyConverter {

        $rates = $date ? $this->rates($date, $sourceCurrency) : $this->latest($sourceCurrency);

        $builder = ConfigurableProvider::builder();

        foreach ($rates as $targetCurrency => $exchangeRate) {
            $builder->addExchangeRate($sourceCurrency, $targetCurrency, (string) $exchangeRate);
        }

        return new CurrencyConverter(
            new BaseCurrencyProvider(
                $builder->build(),
                $sourceCurrency
            )
        );
    }

    public function convert(
        Money $money,
        string|Currency $currency,
        ?RoundingMode $roundingMode = null,
        ?CarbonInterface $date = null,
    ): Money {

        if ($money->getCurrency()->isEqualTo($currency)) {
            return $money;
        }

        $roundingMode ??= config('forex.roundingMode', RoundingMode::HalfUp);

        $converter = $this->getCurrencyConverter(
            sourceCurrency: $money->getCurrency(),
            date: $date
        );

        return $converter->convert(
            $money,
            $currency,
            // @phpstan-ignore-next-line
            roundingMode: $roundingMode
        );

    }
}
