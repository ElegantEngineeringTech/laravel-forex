# Laravel Forex

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-forex.svg)](https://packagist.org/packages/elegantly/laravel-forex)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-forex.svg)](https://packagist.org/packages/elegantly/laravel-forex)
[![Tests](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/run-tests.yml/badge.svg)](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/run-tests.yml)
[![Laravel Pint](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/pint.yml/badge.svg)](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/pint.yml)
[![PHPStan](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/phpstan.yml/badge.svg)](https://github.com/ElegantEngineeringTech/laravel-forex/actions/workflows/phpstan.yml)

**Laravel Forex** is a simple and flexible package for retrieving the latest and historical foreign exchange rates in your Laravel application.

By default, it uses the free tier from [exchangerate-api.com](https://www.exchangerate-api.com/), but you can easily configure it to use any other Forex provider.

---

## Contents

-   [Features](#features)
-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
    -   [Latest Rates](#latest-rates)
    -   [Historical Rates](#historical-rates)
    -   [Converting Money](#converting-money)
    -   [Refreshing Rates](#refreshing-rates)
-   [Providers](#providers)
    -   [ExchangeRate-Api.com](#exchangerate-apicom)
    -   [Custom Client](#custom-client)
-   [Caching & Rate Limiting](#caching--rate-limiting)
-   [Testing](#testing)
-   [Changelog](#changelog)
-   [Contributing](#contributing)
-   [Security](#security)
-   [Credits](#credits)
-   [License](#license)

---

## Features

-   Retrieve latest exchange rates for any base currency.
-   Retrieve historical exchange rates for a specific date.
-   Convert `Money` instances between currencies with high precision using [`brick/money`](https://github.com/brick/money).
-   Built-in caching and optional rate limiting to keep API usage under control.
-   Swap the default provider with a custom implementation via a simple interface.

---

## Requirements

-   PHP `^8.3`
-   Laravel `^13.0`

---

## Installation

Install the package via Composer:

```bash
composer require elegantly/laravel-forex
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="forex-config"
```

---

## Configuration

The published configuration file lives at `config/forex.php`:

```php
use Brick\Math\RoundingMode;
use Elegantly\Forex\Integrations\ExchangeRateApiFree\ExchangeRateApiFreeConnector;

return [

    /**
     * Rounding mode used when converting money.
     */
    'rounding_mode' => RoundingMode::HalfUp,

    'cache' => [
        'enabled' => true,
        'driver' => env('FOREX_CACHE_DRIVER', env('CACHE_STORE', env('CACHE_DRIVER', 'file'))),
        'expiry_seconds' => 86_400, // 1 day
    ],

    'rate_limit' => [
        'enabled' => false,
        'driver' => env('FOREX_RATE_LIMIT_DRIVER', env('CACHE_STORE', env('CACHE_DRIVER', 'file'))),
        'every_seconds' => 3_600, // 1 hour
    ],

    'client' => ExchangeRateApiFreeConnector::class,

    'clients' => [
        'exchange-rate-api' => [
            'token' => env('EXCHANGE_RATE_API_TOKEN'),
        ],
    ],

];
```

### Environment Variables

| Variable | Description |
| --- | --- |
| `EXCHANGE_RATE_API_TOKEN` | Your API token when using the paid `exchangerate-api.com` connector. |
| `FOREX_CACHE_DRIVER` | The cache store used for Forex responses. |
| `FOREX_RATE_LIMIT_DRIVER` | The cache store used for rate limiting. |

---

## Usage

All public methods are available through the `Forex` facade:

```php
use Elegantly\Forex\Facades\Forex;
```

### Latest Rates

Fetch the latest rates for a given base currency:

```php
$rates = Forex::latest('USD');

$usdToEur = $rates['EUR'];
```

### Historical Rates

Fetch historical rates for a specific date:

```php
use Carbon\Carbon;

$rates = Forex::rates(Carbon::create(2022, 4, 25), 'USD');

$usdToEur = $rates['EUR'];
```

### Converting Money

Convert a `Money` instance from one currency to another:

```php
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Elegantly\Forex\Facades\Forex;

$convertedMoney = Forex::convert(
    money: Money::of(100, 'USD'),
    currency: 'EUR',
);

$convertedMoney->__toString(); // (EUR) 88.84
```

You can also convert against historical rates and override the rounding mode:

```php
use Carbon\Carbon;

$convertedMoney = Forex::convert(
    money: Money::of(100, 'USD'),
    currency: 'EUR',
    roundingMode: RoundingMode::Down,
    date: Carbon::create(2022, 4, 25),
);
```

### Refreshing Rates

By default, rates are cached according to your configuration. To bypass the cache and fetch fresh data:

```php
// Refresh latest rates
Forex::refreshLatest('USD');

// Refresh historical rates
Forex::refreshRates(Carbon::create(2022, 4, 25), 'USD');
```

---

## Providers

### ExchangeRate-Api.com

The package ships with two ready-to-use connectors for [exchangerate-api.com](https://www.exchangerate-api.com/):

-   `ExchangeRateApiFreeConnector` — uses the free public endpoint. Rates are updated once a day and historical data is not supported.
-   `ExchangeRateApiConnector` — uses the authenticated v6 API. Requires a token and supports historical rates.

To use the paid connector, update your config:

```php
use Elegantly\Forex\Integrations\ExchangeRateApi\ExchangeRateApiConnector;

'client' => ExchangeRateApiConnector::class,
```

And add your token to `.env`:

```bash
EXCHANGE_RATE_API_TOKEN=your-api-token
```

### Custom Client

Want to use a different provider? Implement the `ForexClient` interface:

```php
use Carbon\CarbonInterface;
use Elegantly\Forex\ForexClient;

class MyCustomForexClient implements ForexClient
{
    public function latest(string $currency): array
    {
        // Return an associative array of currency code => rate
    }

    public function rates(CarbonInterface $date, string $currency): array
    {
        // Return historical rates for the given date
    }
}
```

Then set it as the active client:

```php
'client' => \App\Services\MyCustomForexClient::class,
```

---

## Caching & Rate Limiting

The package uses Saloon's cache and rate-limit plugins to reduce API calls and avoid hitting provider limits.

-   **Caching** is enabled by default and stores responses for the configured `expiry_seconds`.
-   **Rate limiting** is disabled by default. Enable it to throttle requests to one per `every_seconds` interval.

Both features use the cache store configured in `forex.php`.

---

## Testing

Run the test suite with:

```bash
composer test
```

Run the static analysis suite with:

```bash
composer analyse
```

Format the code with:

```bash
composer format
```

---

## Changelog

See the [CHANGELOG](CHANGELOG.md) for details on recent updates.

---

## Contributing

Contributions are welcome! Please read the [CONTRIBUTING](CONTRIBUTING.md) guide for details.

---

## Security

If you discover any security-related issues, please refer to our [security policy](../../security/policy).

---

## Credits

-   [Quentin Gabriele](https://github.com/QuentinGab)
-   [All Contributors](../../contributors)

---

## License

This package is open-source software licensed under the [MIT license](LICENSE.md).
