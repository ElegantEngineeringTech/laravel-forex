# Forex for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/finller/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/finller/laravel-forex)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/finller/laravel-forex/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/finller/laravel-forex/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/finller/laravel-forex/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/finller/laravel-forex/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/finller/laravel-forex.svg?style=flat-square)](https://packagist.org/packages/finller/laravel-forex)

Easily retreive latest exchange rates value in your app.

## Installation

You can install the package via composer:

```bash
composer require finller/laravel-forex
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-forex-config"
```

This is the contents of the published config file:

```php
return [
    'cache' => [
        'driver' => env('FOREX_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),
        'expiry_seconds' => 86_400,
    ],

    'rate_limit' => [
        'driver' => env('FOREX_RATE_LIMIT_DRIVER', env('CACHE_DRIVER', 'file')),
    ],

    'request' => DefaultForexRequest::class,
];
```

## Usage

```php

$rates = \Finller\Forex\Facades\Forex::get('USD');

echo $rates['EUR'];

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
