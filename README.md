# Forex for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elegantly/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-forex)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elegantly/laravel-forex/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ElegantEngineeringTech/laravel-forex/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elegantly/laravel-forex/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ElegantEngineeringTech/laravel-forex/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elegantly/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/elegantly/laravel-forex)

Easily retreive latest exchange rates value in your app.

By default, this package use the free endpoint provided by [exchangerate-api.com](https://www.exchangerate-api.com/) but you can use it with any forex provider.

## Installation

You can install the package via composer:

```bash
composer require elegantly/laravel-forex
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-forex-config"
```

This is the contents of the published config file:

```php

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
```

## Usage

```php

$rates = \Elegantly\Forex\Facades\Forex::get('USD');

$USD_to_EUR_rate = $rates['EUR'];

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Quentin Gabriele](https://github.com/QuentinGab)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
